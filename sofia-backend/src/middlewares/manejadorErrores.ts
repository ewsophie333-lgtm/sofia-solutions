/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS - MI SISTEMA DE CIBERSEGURIDAD
 * ============================================================================
 * 
 * Este código lo he desarrollado para mi proyecto final (TFG). Aquí trato de
 * aplicar todo lo que he aprendido tanto en el curso como en la empresa.
 * 
 * Autor: Sofia Gomez
 * Año: 2026
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
