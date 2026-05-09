import type { Request, Response, NextFunction } from "express";
import { env } from "../config/env";
import { metrics } from "../config/prometheus";
import { detectAttackPatterns } from "../utils/sanitize";
import { logSecurityEvent } from "../services/audit.service";
import { notifySoc } from "../services/soc.service";
import { getRequestMode } from "../utils/mode";
import { getThreatInfo } from "../config/threats";

export async function attackDetection(req: Request, res: Response, next: NextFunction) {
  const mode = getRequestMode(req);

  const payload = {
    path: req.path,
    query: req.query,
    body: req.body
  };

  const attack = detectAttackPatterns(payload);

  if (!attack) {
    next();
    return;
  }

  const threat = getThreatInfo(attack.type);
  console.log(`[ALERT] ${threat.type} detected on ${req.path}. Escalating to SOC...`);

  // Registro en el Audit Service (SIEM interno)
  await logSecurityEvent({
    type: attack.type,
    severity: threat.severity,
    sourceIp: req.ip ?? "unknown",
    endpoint: req.originalUrl,
    payload: { query: req.query, body: req.body },
    action: mode === "secure" ? "BLOCKED" : "ALLOWED",
    metadata: { mode, description: threat.description }
  });

  // Notificación al SOC (n8n -> Gmail) - SIEMPRE, incluso en modo vulnerable
  await notifySoc(`Ataque ${threat.type} detectado en ${req.originalUrl} (${mode.toUpperCase()})`, attack.type, threat.severity);

  if (mode === "vulnerable") {
    // VULNERABLE: solo registra el intento y deja continuar la petición.
    console.warn(`[VULNERABLE MODE] Attack detected: ${attack.type}. Escalated to SOC.`);
    next();
    return;
  }

  // BLOQUEO (Solo en modo seguro)
  metrics.attacksBlockedTotal.inc({ type: attack.type, mode });

  // Simulate Threat Location for Grafana World Map
  const countries = ["US", "CN", "RU", "BR", "FR", "DE", "UA", "KP"];
  const randomCountry = countries[Math.floor(Math.random() * countries.length)];
  metrics.threatLocationTotal.inc({ country_code: randomCountry, type: attack.type });

  res.status(403).json({ message: "Request blocked by Sofia WAF Shield." });
}
