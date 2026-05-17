/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS
 * ============================================================================
 * 
 * Este código lo he desarrollado para mi proyecto final de ASIR. Aquí trato de
 * aplicar todo lo que he aprendido tanto en el curso como en la empresa.
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

/**
 * Middleware para controlar el número de peticiones (Rate Limiting).
 * Lo uso principalmente para evitar ataques de fuerza bruta en el login.
 */
export function authRateLimiter(req: Request, res: Response, next: NextFunction) {
  // Primero miro en qué modo está funcionando la app (vulnerable o seguro)
  const modo = getRequestMode(req);
  
  if (modo === "vulnerable") {
    // Si estamos en modo vulnerable, desactivo el límite.
    // Esto es útil para que el tribunal pueda probar ataques de diccionario sin bloqueos.
    return next();
  }

  // Si estamos en modo seguro, aplico el limitador estricto que he configurado arriba.
  return secureAuthRateLimiter(req, res, next);
}
