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
import type { RequestHandler } from "express";

export const asyncHandler = (handler: RequestHandler): RequestHandler => {
  return (req, res, next) => {
    Promise.resolve(handler(req, res, next)).catch(next);
  };
};
