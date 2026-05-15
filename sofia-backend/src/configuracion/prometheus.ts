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
import client from "prom-client";
import { entorno } from "./entorno";

const registry = new client.Registry();
client.collectDefaultMetrics({ register: registry, prefix: entorno.PROMETHEUS_PREFIX });

export const metrics = {
  registry,
  httpRequestsTotal: new client.Counter({
    name: "http_requests_total",
    help: "Total HTTP requests",
    labelNames: ["method", "route", "statusCode"],
    registers: [registry]
  }),
  loginAttemptsTotal: new client.Counter({
    name: "login_attempts_total",
    help: "Total login attempts",
    labelNames: ["modo", "result"],
    registers: [registry]
  }),
  attacksBlockedTotal: new client.Counter({
    name: "attacks_blocked_total",
    help: "Total blocked attacks",
    labelNames: ["type", "modo"],
    registers: [registry]
  }),
  activeSessions: new client.Gauge({
    name: "active_sessions",
    help: "Active refresh sessions",
    labelNames: ["modo"],
    registers: [registry]
  }),
  threatLocationTotal: new client.Counter({
    name: "threat_location_total",
    help: "Attacks by simulated country location",
    labelNames: ["country_code", "type"],
    registers: [registry]
  }),
  alertsSentTotal: new client.Counter({
    name: "alerts_sent_total",
    help: "Total security alerts sent to external channels",
    labelNames: ["destination", "type", "severity"],
    registers: [registry]
  })
};

// Initialize metrics with demo values to avoid "No data" in Grafana
metrics.activeSessions.set({ modo: "secure" }, 12);
metrics.activeSessions.set({ modo: "vulnerable" }, 0);
metrics.httpRequestsTotal.inc(150);

// Pre-populate threat location data for Grafana geomap
// Cada país necesita lat/lon para que Grafana pueda pintar el mapa
const demoCountries = [
  { code: "RU", lat: 55.75, lon: 37.62, count: 14 },
  { code: "CN", lat: 39.90, lon: 116.40, count: 11 },
  { code: "US", lat: 38.90, lon: -77.04, count: 8 },
  { code: "BR", lat: -15.79, lon: -47.88, count: 6 },
  { code: "DE", lat: 52.52, lon: 13.40, count: 4 },
  { code: "UA", lat: 50.45, lon: 30.52, count: 3 },
  { code: "KP", lat: 39.03, lon: 125.75, count: 2 },
  { code: "FR", lat: 48.86, lon: 2.35, count: 1 }
];
for (const c of demoCountries) {
  metrics.threatLocationTotal.inc({ country_code: c.code, type: "Inyección SQL" }, c.count);
}

// Gauge con coordenadas para el Geomap de Grafana
const geoGauge = new client.Gauge({
  name: "threat_geo_attacks",
  help: "Attack count with geographic coordinates for Grafana geomap",
  labelNames: ["country_code", "latitude", "longitude"],
  registers: [registry]
});
for (const c of demoCountries) {
  geoGauge.set({ country_code: c.code, latitude: String(c.lat), longitude: String(c.lon) }, c.count);
}
export { geoGauge };

// Pre-populate attack blocked data
metrics.attacksBlockedTotal.inc({ type: "SQLI_ATTEMPT", modo: "secure" }, 7);
metrics.attacksBlockedTotal.inc({ type: "XSS_ATTEMPT", modo: "secure" }, 4);
metrics.attacksBlockedTotal.inc({ type: "PATH_TRAVERSAL", modo: "secure" }, 3);

// Pre-populate alert sent data
metrics.alertsSentTotal.inc({ destination: "GMAIL/N8N", type: "Inyección SQL", severity: "CRITICAL" }, 3);
metrics.alertsSentTotal.inc({ destination: "GMAIL/N8N", type: "Fuerza Bruta", severity: "HIGH" }, 2);

