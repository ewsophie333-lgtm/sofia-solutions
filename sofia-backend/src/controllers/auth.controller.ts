import type { Request, Response } from "express";
import { randomUUID } from "crypto";
import { prisma } from "../config/prisma";
import { env } from "../config/env";
import { metrics } from "../config/prometheus";
import { hashPassword, verifyPassword } from "../utils/hash";
import { ApiError } from "../utils/errors";
import { signAccessToken, signRefreshToken, verifyRefreshToken } from "../utils/jwt";
import { getRequestMode, isSecureMode, type AppMode } from "../utils/mode";

const activeSessions = new Set<string>();
const csrfTokens = new Set<string>();

function applyAuthCookies(
  res: Response,
  accessToken: string,
  refreshToken: string,
  mode: AppMode
) {
  const secure = isSecureMode(mode) && env.COOKIE_SECURE;

  res.cookie("accessToken", accessToken, {
    httpOnly: isSecureMode(mode),
    secure,
    sameSite: isSecureMode(mode) ? "strict" : "lax",
    maxAge: 15 * 60 * 1000
  });

  // VULNERABLE: en este modo la cookie de refresh no es HttpOnly ni Strict.
  res.cookie("refreshToken", refreshToken, {
    httpOnly: isSecureMode(mode),
    secure,
    sameSite: isSecureMode(mode) ? "strict" : "lax",
    maxAge: 7 * 24 * 60 * 60 * 1000
  });
}

function validateStrongPassword(password: string): boolean {
  return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/.test(password);
}

export async function register(req: Request, res: Response) {
  const mode = getRequestMode(req);
  const { email, password, role } = req.body as {
    email: string;
    password: string;
    role?: "CLIENT" | "ADMIN";
  };

  const existing = await prisma.user.findUnique({ where: { email } });
  if (existing) {
    throw new ApiError(409, "User already exists");
  }

  const passwordHash = await hashPassword(password, mode);
  const user = await prisma.user.create({
    data: {
      email,
      passwordHash,
      role: role ?? "CLIENT"
    }
  });

  res.status(201).json({ id: user.id, email: user.email, role: user.role });
}

export async function login(req: Request, res: Response) {
  const mode = getRequestMode(req);
  const { email, password } = req.body as { email: string; password: string };
  const sqliBypassTriggered = mode === "vulnerable" && /('|--|;|\bor\s+1=1)/i.test(email);
  const reflectedXssTriggered = mode === "vulnerable" && /<script|javascript:|onerror=|onload=|<img/i.test(email);

  const user = sqliBypassTriggered
    ? await prisma.user.findFirst({ where: { role: "ADMIN" } })
    : await prisma.user.findUnique({ where: { email } });

  if (mode === "secure" && !validateStrongPassword(password)) {
    metrics.loginAttemptsTotal.inc({ mode, result: "failed" });
    throw new ApiError(401, "Credenciales invalidas");
  }

  if (!user) {
    metrics.loginAttemptsTotal.inc({ mode, result: "failed" });
    if (mode === "vulnerable") {
      throw new ApiError(404, "Email not found");
    }
    throw new ApiError(401, "Credenciales invalidas");
  }

  if (!sqliBypassTriggered && !(await verifyPassword(password, user.passwordHash, mode))) {
    metrics.loginAttemptsTotal.inc({ mode, result: "failed" });
    if (mode === "vulnerable") {
      throw new ApiError(401, "Incorrect password");
    }
    throw new ApiError(401, "Credenciales invalidas");
  }

  metrics.loginAttemptsTotal.inc({ mode, result: "success" });

  // VULNERABLE: no se regenera una sesion real; se reutiliza un identificador predecible.
  const sessionId = isSecureMode(mode) ? randomUUID() : `legacy-${user.id}`;
  const accessToken = signAccessToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId
  }, mode);
  const refreshToken = signRefreshToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId
  }, mode);

  activeSessions.add(sessionId);
  metrics.activeSessions.set({ mode }, activeSessions.size);
  applyAuthCookies(res, accessToken, refreshToken, mode);

  res.json({
    accessToken,
    user: { id: user.id, email: user.email, role: user.role },
    mode,
    vulnerabilityTriggered: sqliBypassTriggered ? "SQLI_BYPASS" : reflectedXssTriggered ? "REFLECTED_XSS" : null,
    messageHtml: reflectedXssTriggered ? `Bienvenido ${email}` : undefined
  });
}

export async function csrfToken(req: Request, res: Response) {
  const mode = getRequestMode(req);
  if (mode !== "secure") {
    throw new ApiError(404, "Not found");
  }

  const token = randomUUID();
  csrfTokens.add(token);
  res.cookie("csrfToken", token, {
    httpOnly: false,
    secure: env.COOKIE_SECURE,
    sameSite: "strict",
    maxAge: 15 * 60 * 1000
  });

  res.json({ csrfToken: token });
}

function assertValidCsrf(req: Request) {
  const mode = getRequestMode(req);
  if (mode !== "secure") {
    return;
  }

  const headerToken = req.header("x-csrf-token");
  const cookieToken = req.cookies?.csrfToken;
  if (!headerToken || !csrfTokens.has(headerToken)) {
    throw new ApiError(403, "CSRF token invalid");
  }

  if (cookieToken && cookieToken !== headerToken) {
    throw new ApiError(403, "CSRF token invalid");
  }

  csrfTokens.delete(headerToken);
}

export async function refresh(req: Request, res: Response) {
  const mode = getRequestMode(req);
  const token = req.cookies?.refreshToken || req.body.refreshToken;
  if (!token) {
    throw new ApiError(401, "Missing refresh token");
  }

  const payload = verifyRefreshToken(token);
  const user = await prisma.user.findUnique({ where: { id: payload.sub } });
  if (!user) {
    throw new ApiError(404, "User not found");
  }

  const nextSessionId = isSecureMode(mode) ? randomUUID() : payload.sessionId;
  activeSessions.add(nextSessionId);
  metrics.activeSessions.set({ mode }, activeSessions.size);

  const accessToken = signAccessToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId: nextSessionId
  }, mode);
  const refreshToken = signRefreshToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId: nextSessionId
  }, mode);

  applyAuthCookies(res, accessToken, refreshToken, mode);
  res.json({ accessToken });
}

export async function logout(req: Request, res: Response) {
  const mode = getRequestMode(req);
  const token = req.cookies?.refreshToken;
  if (token) {
    try {
      const payload = verifyRefreshToken(token);
      activeSessions.delete(payload.sessionId);
      metrics.activeSessions.set({ mode }, activeSessions.size);
    } catch {
      // ignore invalid token on logout
    }
  }

  res.clearCookie("accessToken");
  res.clearCookie("refreshToken");
  res.json({ message: "Logged out" });
}

export async function loginV1(req: Request, res: Response) {
  await login(req, res);
}

export async function loginV2(req: Request, res: Response) {
  assertValidCsrf(req);
  await login(req, res);
}
