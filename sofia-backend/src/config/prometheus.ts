import client from "prom-client";
import { env } from "./env";

const registry = new client.Registry();
client.collectDefaultMetrics({ register: registry, prefix: env.PROMETHEUS_PREFIX });

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
    labelNames: ["mode", "result"],
    registers: [registry]
  }),
  attacksBlockedTotal: new client.Counter({
    name: "attacks_blocked_total",
    help: "Total blocked attacks",
    labelNames: ["type", "mode"],
    registers: [registry]
  }),
  activeSessions: new client.Gauge({
    name: "active_sessions",
    help: "Active refresh sessions",
    labelNames: ["mode"],
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
metrics.activeSessions.set({ mode: "secure" }, 12);
metrics.activeSessions.set({ mode: "vulnerable" }, 0);
metrics.httpRequestsTotal.inc(150);
