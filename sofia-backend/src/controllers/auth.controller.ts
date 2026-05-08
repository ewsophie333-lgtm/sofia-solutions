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
import axios from "axios";

const N8N_WEBHOOK_URL = process.env.N8N_WEBHOOK_URL || "http://n8n:5678/webhook/alert";

/**
 * Persists secure HTTP-only cookies for session management.
 * @internal
 */
function applyAuthCookies(
  res: Response,
  accessToken: string,
  refreshToken: string,
  role: string = "CLIENT"
) {
  const secure = env.COOKIE_SECURE;
  const suffix = role === "ADMIN" ? "_ADMIN" : "_CLIENT";

  res.cookie(`accessToken${suffix}`, accessToken, {
    httpOnly: true,
    secure,
    sameSite: "strict",
    maxAge: 15 * 60 * 1000
  });

  res.cookie(`refreshToken${suffix}`, refreshToken, {
    httpOnly: true,
    secure,
    sameSite: "strict",
    maxAge: 7 * 24 * 60 * 60 * 1000
  });

  // Keep legacy cookies for backward compatibility if needed, but the role-specific ones take precedence
  res.cookie("accessToken", accessToken, { httpOnly: true, secure, sameSite: "strict", maxAge: 15 * 60 * 1000 });
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
    
    // Auto-Alert on Critical Failure
    await prisma.alert.create({
      data: {
        title: "Intento de Acceso No Autorizado",
        description: `Fallo de autenticación para el usuario: ${email}. Posible ataque de Fuerza Bruta detectado.`,
        severity: "URGENT",
        status: "ACTIVE"
      }
    });

    try {
      await axios.post(N8N_WEBHOOK_URL, {
        title: "ALERTA DE SEGURIDAD: Brute Force",
        description: `Se ha detectado un intento de acceso fallido para ${email}. El sistema ha bloqueado la peticion.`,
        severity: "URGENT",
        recipientEmail: "ewsophie333@gmail.com",
        clientName: "S. SOLUTIONS MONITOR"
      });
    } catch(e: any) {
      console.error(`[ALERTA ERROR] No se pudo enviar webhook a n8n: ${e.message}`);
    }

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

  applyAuthCookies(res, accessToken, refreshToken, user.role);

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
 * V1 Authentication Engine (Vulnerable Mode).
 * Demonstrates insecure identity validation (plaintext comparison simulation).
 */
export async function loginV1(req: Request, res: Response) {
  const { email, password } = req.body;

  // VULNERABLE: Direct string concatenation to allow SQL Injection bypass
  // In a truly vulnerable system, the password check is also part of the query
  const users: any[] = await prisma.$queryRawUnsafe(
    `SELECT * FROM "User" WHERE "email" = '${email}' AND "passwordHash" IS NOT NULL`
  );
  
  const user = users[0];

  // For simulation: If email contains an injection like ' OR 1=1 --
  // we might want to skip the password check if we found a user
  const isInjection = email.includes("'") || email.includes("--");

  if (!user || (!isInjection && !(await verifyPassword(password, user.passwordHash, "vulnerable")))) {
    metrics.loginAttemptsTotal.inc({ mode: "vulnerable", result: "failed" });
    throw new ApiError(401, "Invalid identity credentials (Vulnerable Mode)");
  }

  metrics.loginAttemptsTotal.inc({ mode: "vulnerable", result: "success" });

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
  }, "vulnerable");

  const refreshToken = signRefreshToken({
    sub: user.id,
    email: user.email,
    role: user.role,
    sessionId
  }, "vulnerable");

  applyAuthCookies(res, accessToken, refreshToken, user.role);

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

  applyAuthCookies(res, accessToken, refreshToken, user.role);
  res.json({ accessToken });
}

/**
 * Session Termination Handler.
 */
export async function logout(req: Request, res: Response) {
  res.clearCookie("accessToken");
  res.clearCookie("refreshToken");
  res.clearCookie("accessToken_ADMIN");
  res.clearCookie("refreshToken_ADMIN");
  res.clearCookie("accessToken_CLIENT");
  res.clearCookie("refreshToken_CLIENT");
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

    // Auto-Alert on Secure Login Failure
    await prisma.alert.create({
      data: {
        title: "Alerta de Seguridad V2",
        description: `Fallo de login securizado para ${email}. El sistema de Rate Limiting ha sido activado.`,
        severity: "CRITICAL",
        status: "ACTIVE"
      }
    });

    try {
      await axios.post(N8N_WEBHOOK_URL, {
        title: "CRITICAL: Fallo en Login Seguro",
        description: `Intento de compromiso detectado contra el endpoint v2 para ${email}.`,
        severity: "CRITICAL",
        recipientEmail: "ewsophie333@gmail.com",
        clientName: "S. SOLUTIONS SECURITY"
      });
    } catch(e: any) {
      console.error(`[ALERTA V2 ERROR] No se pudo enviar webhook a n8n: ${e.message}`);
    }

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

  applyAuthCookies(res, accessToken, refreshToken, user.role);

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
