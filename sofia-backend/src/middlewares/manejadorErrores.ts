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
import type { Request, Response, NextFunction } from "express";
import { ZodError } from "zod";
import { registro } from "../configuracion/registro";
import { ApiError } from "../utilidades/errores";

/**
 * Este es mi manejador global de errores. Captura cualquier fallo en la API
 * y devuelve una respuesta limpia al cliente.
 */
export function manejadorErrores(
  error: unknown,
  req: Request,
  res: Response,
  _next: NextFunction
) {
  // Si es un error controlado por mi clase ApiError
  if (error instanceof ApiError) {
    res.status(error.statusCode).json({ message: error.message, details: error.details });
    return;
  }

  // Si es un error de validación de Zod (datos mal formados)
  if (error instanceof ZodError) {
    res.status(400).json({ message: "Error de validación en los datos", details: error.flatten() });
    return;
  }

  // Para cualquier otro error inesperado, lo registro para revisarlo luego
  registro.error({
    message: "unhandled_error",
    requestId: req.requestId,
    error
  });

  // Y devuelvo un error 500 genérico
  res.status(500).json({ message: "Ha ocurrido un error interno en el servidor" });
}
