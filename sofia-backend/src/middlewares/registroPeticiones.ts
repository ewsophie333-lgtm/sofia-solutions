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
import { randomUUID } from "crypto";
import { registro } from "../configuracion/registro";
import { metrics } from "../configuracion/prometheus";

/**
 * Este middleware registra cada petición que llega a la API.
 * Asigno un ID único a cada una para poder rastrearlas si hay problemas.
 */
export function registroPeticiones(req: Request, res: Response, next: NextFunction): void {
  // Genero un ID único para esta petición
  req.requestId = randomUUID();
  const start = Date.now();

  // Cuando la petición termina (se envía la respuesta), calculo cuánto ha tardado
  res.on("finish", () => {
    // Actualizo las métricas de Prometheus para Grafana
    metrics.httpRequestsTotal.inc({
      method: req.method,
      route: req.route?.path ?? req.path,
      statusCode: String(res.statusCode)
    });

    // Guardo un log con los detalles de la petición
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
