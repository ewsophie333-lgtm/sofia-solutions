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
import { ZodError } from "zod";
import { registro } from "../configuracion/registro";
import { ApiError } from "../utilidades/errores";

export function manejadorErrores(
  error: unknown,
  req: Request,
  res: Response,
  _next: NextFunction
) {
  if (error instanceof ApiError) {
    res.status(error.statusCode).json({ message: error.message, details: error.details });
    return;
  }

  if (error instanceof ZodError) {
    res.status(400).json({ message: "Validation error", details: error.flatten() });
    return;
  }

  registro.error({
    message: "unhandled_error",
    requestId: req.requestId,
    error
  });

  res.status(500).json({ message: "Internal server error" });
}
