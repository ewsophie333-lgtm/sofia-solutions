/**
 * ============================================================================
 * SOFIA SOLUTIONS - MI MIDDLEWARE DE AUTENTICACIÓN
 * ============================================================================
 * 
 * Este es el guardián de mi aplicación. Aquí compruebo que el que intenta entrar
 * tenga un token válido. Si no lo tiene, lo echo fuera antes de que toque nada.
 * 
 * Sofia Gomez
 * @copyright 2026
 * ============================================================================
 */
import type { Request, Response, NextFunction } from "express";
import { verifyAccessToken } from "../utilidades/jwt";
import { ApiError } from "../utilidades/errores";

/**
 * Esta función la uso para obligar a que el usuario esté autenticado.
 * Primero miro si el token viene en el header "Authorization", y si no, 
 * lo busco en las cookies (que es lo que uso en el dashboard).
 */
export function requireAuth(req: Request, _res: Response, next: NextFunction) {
  try {
    const header = req.headers.authorization;
    let token = header?.startsWith("Bearer ") ? header.slice(7) : null;

    if (!token) {
      // Si no hay header, lo busco en las cookies dependiendo de qué parte de la web estemos
      if (req.path.includes("/admin") || req.path.includes("/soc") || req.path.includes("/alerts")) {
        token = req.cookies?.accessToken_ADMIN || req.cookies?.accessToken;
      } else {
        token = req.cookies?.accessToken_CLIENT || req.cookies?.accessToken;
      }
    }
    
    // Si llegamos aquí y no hay token, fuera.
    if (!token || token === "null" || token === "undefined") {
      throw new ApiError(401, "No se ha encontrado el token de acceso o es inválido");
    }

    try {
      // Valido el token usando mi utilidad de JWT
      const payload = verifyAccessToken(token);
      req.user = {
        id: payload.sub,
        email: payload.email,
        role: payload.role as "ADMIN" | "CLIENT"
      };
      next();
    } catch (error) {
      throw new ApiError(401, "Tu sesión ha caducado o el token no es válido");
    }
  } catch (error) {
    next(error);
  }
}

/**
 * Con esta función puedo restringir rutas a roles específicos (ADMIN o CLIENT).
 */
export function requireRole(role: "ADMIN" | "CLIENT") {
  return (req: Request, _res: Response, next: NextFunction) => {
    if (!req.user || req.user.role !== role) {
      // Si no tienes el rol que pido, te bloqueo el paso.
      next(new ApiError(403, "No tienes permisos suficientes para entrar aquí"));
      return;
    }

    next();
  };
}
