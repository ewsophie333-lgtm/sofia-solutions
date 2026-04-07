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
  })
};
