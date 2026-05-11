/**
 * ============================================================================
 * SOFIA SOLUTIONS - SECURITY & MONITORING PLATFORM
 * ============================================================================
 * 
 * Este archivo forma parte de la arquitectura base del backend de Sofia Solutions.
 * Ha sido disenado siguiendo principios de codigo limpio, seguridad por diseno,
 * y alta escalabilidad para entornos criticos e industriales.
 * 
 * @module SofiaSolutions
 * @author Sofia Solutions Architecture Team
 * @copyright 2026
 * ============================================================================
 */
import type { Request, Response, NextFunction } from "express";
import { verifyAccessToken } from "../utilidades/jwt";
import { ApiError } from "../utilidades/errores";

export function requireAuth(req: Request, _res: Response, next: NextFunction) {
  try {
    const header = req.headers.authorization;
    let token = header?.startsWith("Bearer ") ? header.slice(7) : null;

    if (!token) {
      // Prioritize tokens based on the requested resource
      if (req.path.includes("/admin") || req.path.includes("/soc") || req.path.includes("/alerts")) {
        token = req.cookies?.accessToken_ADMIN || req.cookies?.accessToken;
      } else {
        token = req.cookies?.accessToken_CLIENT || req.cookies?.accessToken;
      }
    }
    if (!token || token === "null" || token === "undefined") {
      throw new ApiError(401, "Missing or invalid access token");
    }

    try {
      const payload = verifyAccessToken(token);
      req.user = {
        id: payload.sub,
        email: payload.email,
        role: payload.role as "ADMIN" | "CLIENT"
      };
      next();
    } catch (error) {
      throw new ApiError(401, "Invalid or expired session");
    }
  } catch (error) {
    next(error);
  }
}

export function requireRole(role: "ADMIN" | "CLIENT") {
  return (req: Request, _res: Response, next: NextFunction) => {
    if (!req.user || req.user.role !== role) {
      next(new ApiError(403, "Insufficient permissions"));
      return;
    }

    next();
  };
}
