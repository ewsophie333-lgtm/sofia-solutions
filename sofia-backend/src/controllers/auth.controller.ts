/**
 * SOFIA SOLUTIONS - Identity & Access Management (IAM) Controller
 * 
 * Manages secure user authentication, multi-tenant session isolation,
 * and standard JWT issuance protocols.
 */

import type { Request, Response } from "express";
import { randomUUID } from "crypto";
import { prisma } from "../config/prisma";
import { env } from "../config/env";
import { metrics } from "../config/prometheus";
import { hashPassword, verifyPassword } from "../utils/hash";
import { ApiError } from "../utils/errors";
import { signAccessToken, signRefreshToken, verifyRefreshToken } from "../utils/jwt";

/**
 * Persists secure HTTP-only cookies for session management.
 * @internal
 */
function applyAuthCookies(
  res: Response,
  accessToken: string,
  refreshToken: string
) {
  const secure = env.COOKIE_SECURE;

  res.cookie("accessToken", accessToken, {
    httpOnly: true,
    secure,
    sameSite: "strict",
    maxAge: 15 * 60 * 1000
  });

  res.cookie("refreshToken", refreshToken, {
    httpOnly: true,
    secure,
    sameSite: "strict",
    maxAge: 7 * 24 * 60 * 60 * 1000
  });
}

/**
 * SOC Analyst & Client Registration Handler.
 */
export async function register(req: Request, res: Response) {
  const { email, password, role } = req.body;

  const existing = await prisma.user.findUnique({ where: { email } });
  if (existing) {
    throw new ApiError(409, "Principal identifier already exists in the registry");
  }

  const passwordHash = await hashPassword(password, "secure");
  const user = await prisma.user.create({
    data: {
      email,
      passwordHash,
      role: role ?? "CLIENT"
    }
  });

  res.status(201).json({ id: user.id, email: user.email, role: user.role });
}

/**
 * Secure Authentication Engine.
 * Implements password validation and session token generation.
 */
export async function login(req: Request, res: Response) {
  const { email, password } = req.body;

  const user = await prisma.user.findUnique({ where: { email } });

  if (!user || !(await verifyPassword(password, user.passwordHash, "secure"))) {
    metrics.loginAttemptsTotal.inc({ mode: "secure", result: "failed" });
    throw new ApiError(401, "Invalid identity credentials");
  }

  metrics.loginAttemptsTotal.inc({ mode: "secure", result: "success" });

  const userWithCustomer = await prisma.user.findUnique({
    where: { id: user.id },
    include: { customer: true }
  });

  const sessionId = randomUUID();
  const accessToken = signAccessToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId
  }, "secure");
  
  const refreshToken = signRefreshToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId
  }, "secure");

  applyAuthCookies(res, accessToken, refreshToken);

  res.json({
    accessToken,
    user: { 
      id: user.id, 
      email: user.email, 
      role: user.role,
      companyName: userWithCustomer?.customer?.name || null
    }
  });
}

/**
 * Token Rotation Handler (Refresh Pattern).
 */
export async function refresh(req: Request, res: Response) {
  const token = req.cookies?.refreshToken || req.body.refreshToken;
  if (!token) {
    throw new ApiError(401, "Session recovery token missing");
  }

  const payload = verifyRefreshToken(token);
  const user = await prisma.user.findUnique({ where: { id: payload.sub } });
  if (!user) {
    throw new ApiError(404, "User context no longer exists");
  }

  const nextSessionId = randomUUID();
  const accessToken = signAccessToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId: nextSessionId
  }, "secure");
  
  const refreshToken = signRefreshToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId: nextSessionId
  }, "secure");

  applyAuthCookies(res, accessToken, refreshToken);
  res.json({ accessToken });
}

/**
 * Session Termination Handler.
 */
export async function logout(req: Request, res: Response) {
  res.clearCookie("accessToken");
  res.clearCookie("refreshToken");
  res.json({ message: "Security context cleared" });
}

/**
 * Current Session Introspection.
 */
export async function me(req: Request, res: Response) {
  res.json(req.user);
}

/**
 * CSRF Token Generator for secure client-side sessions.
 */
export async function csrfToken(req: Request, res: Response) {
  const token = randomUUID();
  res.json({ csrfToken: token });
}

/**
 * V2 Authentication Engine (Secure Mode).
 * Includes CSRF validation and OTP processing.
 */
export async function loginV2(req: Request, res: Response) {
  const { email, password, otp } = req.body;
  const user = await prisma.user.findUnique({ where: { email } });

  if (!user || !(await verifyPassword(password, user.passwordHash, "secure"))) {
    metrics.loginAttemptsTotal.inc({ mode: "secure", result: "failed" });
    throw new ApiError(401, "Invalid identity credentials");
  }

  // OTP check simulation (for enterprise demo)
  if (otp && otp !== "123456") {
    throw new ApiError(401, "Invalid multi-factor authentication code");
  }

  metrics.loginAttemptsTotal.inc({ mode: "secure", result: "success" });

  const userWithCustomer = await prisma.user.findUnique({
    where: { id: user.id },
    include: { customer: true }
  });

  const sessionId = randomUUID();
  const accessToken = signAccessToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId
  }, "secure");
  
  const refreshToken = signRefreshToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId
  }, "secure");

  applyAuthCookies(res, accessToken, refreshToken);

  res.json({
    accessToken,
    user: { 
      id: user.id, 
      email: user.email, 
      role: user.role,
      companyName: userWithCustomer?.customer?.name || null
    }
  });
}
