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
 * Sofia Gomez
 * @copyright 2026
 * ============================================================================
 */
import { createLogger, format, transports } from "winston";
import { entorno } from "./entorno";

export const registro = createLogger({
  level: entorno.NODE_ENV === "development" ? "debug" : "info",
  format: format.combine(format.timestamp(), format.errors({ stack: true }), format.json()),
  defaultMeta: { service: "sofia-backend", modo: entorno.APP_MODE },
  transports: [new transports.Console()]
});
