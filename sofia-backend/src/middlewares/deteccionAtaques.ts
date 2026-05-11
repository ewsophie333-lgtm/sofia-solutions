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
import type { Request, Response, NextFunction } from "express";
import { entorno } from "../configuracion/entorno";
import { metrics } from "../configuracion/prometheus";
import { detectAttackPatterns } from "../utilidades/sanitizar";
import { logSecurityEvent } from "../servicios/auditoria.servicio";
import { notifySoc } from "../servicios/soc.servicio";
import { getRequestMode } from "../utilidades/modo";
import { getThreatInfo } from "../configuracion/amenazas";

export async function deteccionAtaques(req: Request, res: Response, next: NextFunction) {
  const modo = getRequestMode(req);

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
    action: modo === "secure" ? "BLOCKED" : "ALLOWED",
    metadata: { modo, description: threat.description }
  });

  // Notificación al SOC (n8n -> Gmail) - SIEMPRE, incluso en modo vulnerable
  await notifySoc(`Ataque ${threat.type} detectado en ${req.originalUrl} (${modo.toUpperCase()})`, attack.type, threat.severity);

  if (modo === "vulnerable") {
    // VULNERABLE: solo registra el intento y deja continuar la petición.
    console.warn(`[VULNERABLE MODE] Attack detected: ${attack.type}. Escalated to SOC.`);
    next();
    return;
  }

  // BLOQUEO (Solo en modo seguro)
  metrics.attacksBlockedTotal.inc({ type: attack.type, modo });

  // Simulate Threat Location for Grafana World Map
  const countries = ["US", "CN", "RU", "BR", "FR", "DE", "UA", "KP"];
  const randomCountry = countries[Math.floor(Math.random() * countries.length)];
  metrics.threatLocationTotal.inc({ country_code: randomCountry, type: attack.type });

  res.status(403).json({ message: "Request blocked by Sofia WAF Shield." });
}
