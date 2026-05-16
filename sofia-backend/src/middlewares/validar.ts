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
import type { Request, Response, NextFunction } from "express";
import type { ZodSchema } from "zod";
import { ApiError } from "../utilidades/errores";

export const validar =
  <T>(schema: ZodSchema<T>) =>
  (req: Request, _res: Response, next: NextFunction): void => {
    const result = schema.safeParse(req.body);
    if (!result.success) {
      next(new ApiError(400, "Validation error", result.error.flatten()));
      return;
    }

    req.body = result.data;
    next();
  };
