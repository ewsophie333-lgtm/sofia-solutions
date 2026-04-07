import type { Request, Response, NextFunction } from "express";
import { env } from "../config/env";
import { metrics } from "../config/prometheus";
import { detectAttackPatterns } from "../utils/sanitize";
import { logSecurityEvent } from "../services/audit.service";
import { notifySoc } from "../services/soc.service";

export async function attackDetection(req: Request, res: Response, next: NextFunction) {
  const attack = detectAttackPatterns({
    path: req.path,
    query: req.query,
    body: req.body
  });

  if (!attack) {
    next();
    return;
  }

  await logSecurityEvent({
    type: attack.type,
    severity: "HIGH",
    sourceIp: req.ip ?? "unknown",
    endpoint: req.originalUrl,
    payload: { query: req.query, body: req.body },
    action: env.APP_MODE === "secure" ? "BLOCKED" : "ALLOWED",
    metadata: { mode: env.APP_MODE }
  });

  if (env.APP_MODE === "vulnerable") {
    // VULNERABLE: solo registra el intento y deja continuar la petición.
    console.warn(`[VULNERABLE MODE] Attack detected but allowed: ${attack.type}`);
    next();
    return;
  }

  metrics.attacksBlockedTotal.inc({ type: attack.type, mode: env.APP_MODE });
  notifySoc(`Ataque ${attack.type} bloqueado en ${req.originalUrl}`);
  res.status(403).json({ message: "Request blocked by attack detection middleware." });
}
