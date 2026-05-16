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
import type { Request } from "express";
import { entorno } from "../configuracion/entorno";

export type AppMode = "secure" | "vulnerable";

export const isSecureMode = (modo: AppMode = entorno.APP_MODE) => modo === "secure";
export const isVulnerableMode = (modo: AppMode = entorno.APP_MODE) => modo === "vulnerable";

export function getRequestMode(req: Request): AppMode {
  return req.modoDemo ?? entorno.APP_MODE;
}
