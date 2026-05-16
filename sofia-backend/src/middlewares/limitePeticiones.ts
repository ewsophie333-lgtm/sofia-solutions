/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS - MI SISTEMA DE CIBERSEGURIDAD
 * ============================================================================
 * 
 * Este código lo he desarrollado para mi proyecto final (TFG). Aquí trato de
 * aplicar todo lo que he aprendido sobre seguridad defensiva y monitorización.
 * 
 * Autor: Sofia Gomez
 * Año: 2026
 * ============================================================================
 */
import limitePeticiones from "express-rate-limit";
import type { Request, Response, NextFunction } from "express";
import { entorno } from "../configuracion/entorno";
import { getRequestMode, isVulnerableMode } from "../utilidades/modo";

// Configuro el limitador para el modo seguro: 5 peticiones por ventana de tiempo
const secureAuthRateLimiter = limitePeticiones({
  windowMs: entorno.RATE_LIMIT_WINDOW_MS,
  max: entorno.RATE_LIMIT_MAX,
  standardHeaders: true,
  legacyHeaders: false,
  message: { message: "Demasiados intentos. Por favor, espera un momento antes de volver a probar." }
});

export function authRateLimiter(req: Request, res: Response, next: NextFunction) {
  const modo = getRequestMode(req);
  
  if (modo === "vulnerable") {
    // DESACTIVADO PARA LA DEMO: Permitir intentos ilimitados en modo vulnerable
    return next();
  }

  // Activo en modo seguro
  return secureAuthRateLimiter(req, res, next);
}
