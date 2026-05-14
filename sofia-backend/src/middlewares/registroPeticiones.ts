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
import type { Request, Response, NextFunction } from "express";
import { randomUUID } from "crypto";
import { registro } from "../configuracion/registro";
import { metrics } from "../configuracion/prometheus";

export function registroPeticiones(req: Request, res: Response, next: NextFunction): void {
  req.requestId = randomUUID();
  const start = Date.now();

  res.on("finish", () => {
    metrics.httpRequestsTotal.inc({
      method: req.method,
      route: req.route?.path ?? req.path,
      statusCode: String(res.statusCode)
    });

    registro.info({
      message: "request_completed",
      requestId: req.requestId,
      method: req.method,
      path: req.path,
      statusCode: res.statusCode,
      durationMs: Date.now() - start
    });
  });

  next();
}
