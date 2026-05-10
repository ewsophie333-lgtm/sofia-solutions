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
