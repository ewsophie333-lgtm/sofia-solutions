import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { env } from "../config/env";
import { metrics } from "../config/prometheus";
import { hashPassword, verifyPassword } from "../utils/hash";
import { ApiError } from "../utils/errors";
import { signAccessToken, signRefreshToken, verifyRefreshToken } from "../utils/jwt";
import { isSecureMode } from "../utils/mode";
import { randomUUID } from "crypto";

const activeSessions = new Set<string>();

function applyAuthCookies(res: Response, accessToken: string, refreshToken: string) {
  const secure = isSecureMode() && env.COOKIE_SECURE;

  res.cookie("accessToken", accessToken, {
    httpOnly: isSecureMode(),
    secure,
    sameSite: isSecureMode() ? "strict" : "lax",
    maxAge: 15 * 60 * 1000
  });

  // VULNERABLE: en este modo la cookie de refresh no es HttpOnly ni Strict.
  res.cookie("refreshToken", refreshToken, {
    httpOnly: isSecureMode(),
    secure,
    sameSite: isSecureMode() ? "strict" : "lax",
    maxAge: 7 * 24 * 60 * 60 * 1000
  });
}

export async function register(req: Request, res: Response) {
  const { email, password, role } = req.body as { email: string; password: string; role?: "CLIENT" | "ADMIN" };
  const existing = await prisma.user.findUnique({ where: { email } });
  if (existing) {
    throw new ApiError(409, "User already exists");
  }

  const passwordHash = await hashPassword(password);
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
  const { email, password } = req.body as { email: string; password: string };
  const user = await prisma.user.findUnique({ where: { email } });

  if (!user || !(await verifyPassword(password, user.passwordHash))) {
    metrics.loginAttemptsTotal.inc({ mode: env.APP_MODE, result: "failed" });
    throw new ApiError(401, "Invalid credentials");
  }

  metrics.loginAttemptsTotal.inc({ mode: env.APP_MODE, result: "success" });

  // VULNERABLE: no se regenera una sesión real; se reutiliza un identificador predecible.
  const sessionId = isSecureMode() ? randomUUID() : `legacy-${user.id}`;
  const accessToken = signAccessToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId
  });
  const refreshToken = signRefreshToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId
  });

  activeSessions.add(sessionId);
  metrics.activeSessions.set({ mode: env.APP_MODE }, activeSessions.size);
  applyAuthCookies(res, accessToken, refreshToken);

  res.json({
    accessToken,
    user: { id: user.id, email: user.email, role: user.role },
    mode: env.APP_MODE
  });
}

export async function refresh(req: Request, res: Response) {
  const token = req.cookies?.refreshToken || req.body.refreshToken;
  if (!token) {
    throw new ApiError(401, "Missing refresh token");
  }

  const payload = verifyRefreshToken(token);
  const user = await prisma.user.findUnique({ where: { id: payload.sub } });
  if (!user) {
    throw new ApiError(404, "User not found");
  }

  const nextSessionId = isSecureMode() ? randomUUID() : payload.sessionId;
  activeSessions.add(nextSessionId);
  metrics.activeSessions.set({ mode: env.APP_MODE }, activeSessions.size);

  const accessToken = signAccessToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId: nextSessionId
  });
  const refreshToken = signRefreshToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId: nextSessionId
  });

  applyAuthCookies(res, accessToken, refreshToken);
  res.json({ accessToken });
}

export async function logout(req: Request, res: Response) {
  const token = req.cookies?.refreshToken;
  if (token) {
    try {
      const payload = verifyRefreshToken(token);
      activeSessions.delete(payload.sessionId);
      metrics.activeSessions.set({ mode: env.APP_MODE }, activeSessions.size);
    } catch {
      // ignore invalid token on logout
    }
  }

  res.clearCookie("accessToken");
  res.clearCookie("refreshToken");
  res.json({ message: "Logged out" });
}
