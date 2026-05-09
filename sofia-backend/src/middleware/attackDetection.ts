import type { Request, Response, NextFunction } from "express";
import { env } from "../config/env";
import { metrics } from "../config/prometheus";
import { detectAttackPatterns } from "../utils/sanitize";
import { logSecurityEvent } from "../services/audit.service";
import { notifySoc } from "../services/soc.service";
import { getRequestMode } from "../utils/mode";

export async function attackDetection(req: Request, res: Response, next: NextFunction) {
  const mode = getRequestMode(req);

  // Skip detection for audit console demos — the console simulates attacks intentionally
  const referer = req.headers.referer || req.headers.origin || '';
  const isAuditConsole = referer.includes('/consola') || referer.includes('/admin/audit');
  if (isAuditConsole) {
    next();
    return;
  }

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

  // Simulate Threat Location for Grafana World Map
  const countries = ["US", "CN", "RU", "BR", "FR", "DE", "UA", "KP"];
  const randomCountry = countries[Math.floor(Math.random() * countries.length)];
  metrics.threatLocationTotal.inc({ country_code: randomCountry, type: attack.type });

  notifySoc(`Ataque ${attack.type} bloqueado en ${req.originalUrl}`);
  res.status(403).json({ message: "Request blocked by attack detection middleware." });
}
