/**
 * ============================================================================
 * SOFIA SOLUTIONS - MI SISTEMA DE DETECCIÓN DE ATAQUES (WAF)
 * ============================================================================
 * 
 * Este middleware es el corazón de mi sistema de defensa. Analiza cada petición
 * que llega buscando patrones raros y decide si dejarla pasar o bloquearla 
 * dependiendo de si tengo activado el "Escudo de Sofia".
 * 
 * Sofia Gomez
 * @copyright 2026
 * ============================================================================
 */
import type { Request, Response, NextFunction } from "express";
import { metrics } from "../configuracion/prometheus";
import { detectAttackPatterns } from "../utilidades/sanitizar";
import { logSecurityEvent } from "../servicios/auditoria.servicio";
import { notifySoc } from "../servicios/soc.servicio";
import { getRequestMode } from "../utilidades/modo";
import { getThreatInfo } from "../configuracion/amenazas";

/**
 * Función que intercepta los ataques.
 * Miro en el body, en la query y en la ruta buscando inyecciones o scripts.
 */
export async function deteccionAtaques(req: Request, res: Response, next: NextFunction) {
  const modo = getRequestMode(req);

  const payload = {
    path: req.path,
    query: req.query,
    body: req.body
  };

  const attack = detectAttackPatterns(payload);

  if (!attack) {
    // Si no hay ataque, todo en orden, seguimos.
    next();
    return;
  }

  const threat = getThreatInfo(attack.type);
  console.log(`[ALERTA] He detectado un ataque de tipo ${threat.type} en ${req.path}. Avisando al SOC...`);

  // Registro el evento en mi servicio de auditoría (es como un SIEM interno)
  const sourceIp = (req.headers["x-forwarded-for"] as string) || req.ip || "127.0.0.1";

  await logSecurityEvent({
    type: attack.type,
    severity: threat.severity,
    sourceIp,
    endpoint: req.originalUrl,
    payload: { query: req.query, body: req.body },
    action: modo === "secure" ? "BLOQUEADO" : "PERMITIDO",
    metadata: { modo, description: threat.description }
  });

  // Aviso al SOC siempre, para que salga en el dashboard y mande el email a n8n
  await notifySoc(`Ataque ${threat.type} detectado en ${req.originalUrl} (Modo: ${modo.toUpperCase()})`, attack.type, threat.severity, sourceIp);

  if (modo === "vulnerable") {
    // Si estoy en modo vulnerable, dejo que el ataque siga para poder enseñarlo en la demo
    console.warn(`[MODO VULNERABLE] Ataque detectado pero permitido: ${attack.type}. Registrado en el SOC.`);
    next();
    return;
  }

  // BLOQUEO (Solo ocurre si el modo seguro está activado)
  metrics.attacksBlockedTotal.inc({ type: attack.type, modo });

  // Simulo una ubicación para que el mapa de Grafana quede bien con puntos de todo el mundo
  const countries = ["US", "CN", "RU", "BR", "FR", "DE", "UA", "KP"];
  const randomCountry = countries[Math.floor(Math.random() * countries.length)];
  metrics.threatLocationTotal.inc({ country_code: randomCountry, type: attack.type });

  res.status(403).json({ message: "Petición bloqueada por el Escudo WAF de Sofia." });
}
