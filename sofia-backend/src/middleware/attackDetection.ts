import type { Request, Response, NextFunction } from "express";
import { env } from "../config/env";
import { metrics } from "../config/prometheus";
import { detectAttackPatterns } from "../utils/sanitize";
import { logSecurityEvent } from "../services/audit.service";
import { notifySoc } from "../services/soc.service";
import { getRequestMode } from "../utils/mode";

export async function attackDetection(req: Request, res: Response, next: NextFunction) {
  const mode = getRequestMode(req);
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
    action: mode === "secure" ? "BLOCKED" : "ALLOWED",
    metadata: { mode }
  });

  if (mode === "vulnerable") {
    // VULNERABLE: solo registra el intento y deja continuar la petición.
    console.warn(`[VULNERABLE MODE] Attack detected but allowed: ${attack.type}`);
    next();
    return;
  }

  metrics.attacksBlockedTotal.inc({ type: attack.type, mode });
  notifySoc(`Ataque ${attack.type} bloqueado en ${req.originalUrl}`);
  res.status(403).json({ message: "Request blocked by attack detection middleware." });
}
