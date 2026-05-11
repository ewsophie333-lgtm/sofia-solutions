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
import limitePeticiones from "express-rate-limit";
import type { Request, Response, NextFunction } from "express";
import { entorno } from "../configuracion/entorno";
import { getRequestMode, isVulnerableMode } from "../utilidades/modo";

const secureAuthRateLimiter = limitePeticiones({
  windowMs: entorno.RATE_LIMIT_WINDOW_MS,
  max: entorno.RATE_LIMIT_MAX,
  standardHeaders: true,
  legacyHeaders: false,
  message: { message: "Too many auth attempts, slow down." }
});

export function authRateLimiter(req: Request, res: Response, next: NextFunction) {
  // DESACTIVADO PARA LA DEMO: Permitir intentos ilimitados
  return next();
}
