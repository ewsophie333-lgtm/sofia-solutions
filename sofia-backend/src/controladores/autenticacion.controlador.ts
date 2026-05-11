/**
 * SOFIA SOLUTIONS - Identity & Access Management (IAM) Controller
 * 
 * Manages secure user authentication, multi-tenant session isolation,
 * and standard JWT issuance protocols.
 */

import type { Request, Response } from "express";
import { randomUUID } from "crypto";
import { prisma } from "../configuracion/prisma";
import { entorno } from "../configuracion/entorno";
import { metrics } from "../configuracion/prometheus";
import { hashPassword, verifyPassword } from "../utilidades/hash";
import { ApiError } from "../utilidades/errores";
import { signAccessToken, signRefreshToken, verifyRefreshToken } from "../utilidades/jwt";
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
  const secure = entorno.COOKIE_SECURE;
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
 * Motor de Autenticación Segura.
 * Implementa validación de contraseñas y generación de tokens de sesión.
 */
export async function login(req: Request, res: Response) {
  const { email, password } = req.body;

  const user = await prisma.user.findUnique({ where: { email } });

  const modo = entorno.APP_MODE as any;
  if (!user || !(await verifyPassword(password, user.passwordHash, modo))) {
    metrics.loginAttemptsTotal.inc({ modo, result: "failed" });
    
    // Alerta automática por fallo crítico
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
        title: "ALERTA DE SEGURIDAD: Fuerza Bruta",
        description: `Se ha detectado un intento de acceso fallido para ${email}. El sistema ha bloqueado la petición.`,
        severity: "URGENT",
        recipientEmail: "ewsophie333@gmail.com",
        clientName: "S. SOLUTIONS MONITOR"
      });
    } catch(e: any) {
      console.error(`[ALERTA ERROR] No se pudo enviar webhook a n8n: ${e.message}`);
    }

    throw new ApiError(401, "Credenciales de identidad inválidas");
  }

  metrics.loginAttemptsTotal.inc({ modo: "secure", result: "success" });
  metrics.activeSessions.inc({ modo: "secure" });

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
 * Motor de Autenticación V1 (Modo Vulnerable).
 * Demuestra validación de identidad insegura (simulación de comparación de texto plano).
 */
export async function loginV1(req: Request, res: Response) {
  const { email, password } = req.body;

  // VULNERABLE: Concatenación directa de strings para permitir evasión por Inyección SQL
  // En un sistema realmente vulnerable, la comprobación de clave también forma parte de la consulta
  const users: any[] = await prisma.$queryRawUnsafe(
    `SELECT * FROM "User" WHERE "email" = '${email}' AND "passwordHash" IS NOT NULL`
  );
  
  const user = users[0];

  // Para simulación: Si el email contiene una inyección como ' OR 1=1 --
  // podríamos querer saltar la comprobación de contraseña si encontramos un usuario
  const isInjection = email.includes("'") || email.includes("--");

  if (!user || (!isInjection && !(await verifyPassword(password, user.passwordHash, "vulnerable")))) {
    metrics.loginAttemptsTotal.inc({ modo: "vulnerable", result: "failed" });
    throw new ApiError(401, "Credenciales de identidad inválidas (Modo Vulnerable)");
  }

  metrics.loginAttemptsTotal.inc({ modo: "vulnerable", result: "success" });
  metrics.activeSessions.inc({ modo: "vulnerable" });

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
 * Manejador de Rotación de Tokens (Patrón Refresh).
 */
export async function refresh(req: Request, res: Response) {
  const token = req.cookies?.refreshToken || req.body.refreshToken;
  if (!token) {
    throw new ApiError(401, "Falta el token de recuperación de sesión");
  }

  const payload = verifyRefreshToken(token);
  const user = await prisma.user.findUnique({ where: { id: payload.sub } });
  if (!user) {
    throw new ApiError(404, "El contexto del usuario ya no existe");
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
 * Manejador de Terminación de Sesión.
 */
export async function logout(req: Request, res: Response) {
  res.clearCookie("accessToken");
  res.clearCookie("refreshToken");
  res.clearCookie("accessToken_ADMIN");
  res.clearCookie("refreshToken_ADMIN");
  res.clearCookie("accessToken_CLIENT");
  res.clearCookie("refreshToken_CLIENT");
  
  // Actualizar métricas
  const modo = entorno.APP_MODE === "vulnerable" ? "vulnerable" : "secure";
  metrics.activeSessions.dec({ modo });

  res.json({ message: "Contexto de seguridad limpiado" });
}

/**
 * Introspección de Sesión Actual.
 */
export async function me(req: Request, res: Response) {
  res.json(req.user);
}

/**
 * Generador de Token CSRF para sesiones seguras en el lado del cliente.
 */
export async function csrfToken(req: Request, res: Response) {
  const token = randomUUID();
  res.json({ csrfToken: token });
}

/**
 * Motor de Autenticación V2 (Modo Seguro).
 * Incluye validación CSRF y procesamiento OTP.
 */
export async function loginV2(req: Request, res: Response) {
  const { email, password, otp } = req.body;
  const user = await prisma.user.findUnique({ where: { email } });

  if (!user || !(await verifyPassword(password, user.passwordHash, "secure"))) {
    metrics.loginAttemptsTotal.inc({ modo: "secure", result: "failed" });

    // Alerta Automática en Fallo de Login Seguro
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

    throw new ApiError(401, "Credenciales de identidad inválidas");
  }

  // Simulación de chequeo OTP (para demo empresarial)
  if (otp && otp !== "123456") {
    throw new ApiError(401, "Código de autenticación multifactor inválido");
  }

  metrics.loginAttemptsTotal.inc({ modo: "secure", result: "success" });

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
