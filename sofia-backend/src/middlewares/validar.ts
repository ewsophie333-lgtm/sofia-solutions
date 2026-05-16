/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS
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
import type { ZodSchema } from "zod";
import { ApiError } from "../utilidades/errores";

/**
 * Middleware para validar los datos que entran en la petición usando un esquema de Zod.
 * Si los datos no cumplen con el formato, devuelvo un error 400.
 */
export const validar =
  <T>(schema: ZodSchema<T>) =>
  (req: Request, _res: Response, next: NextFunction): void => {
    // Analizo el cuerpo de la petición (body)
    const result = schema.safeParse(req.body);
    
    if (!result.success) {
      // Si la validación falla, mando el error al manejador global
      next(new ApiError(400, "Error en el formato de los datos enviados", result.error.flatten()));
      return;
    }

    // Si todo va bien, guardo los datos limpios y sigo adelante
    req.body = result.data;
    next();
  };
