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
import type { Request } from "express";
import { entorno } from "../configuracion/entorno";

export type AppMode = "secure" | "vulnerable";

export const isSecureMode = (modo: AppMode = entorno.APP_MODE) => modo === "secure";
export const isVulnerableMode = (modo: AppMode = entorno.APP_MODE) => modo === "vulnerable";

export function getRequestMode(req: Request): AppMode {
  return req.modoDemo ?? entorno.APP_MODE;
}
