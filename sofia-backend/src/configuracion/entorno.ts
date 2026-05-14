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
import dotenv from "dotenv";
import path from "path";
import { z } from "zod";

const entornoPath = path.resolve(__dirname, "../../.entorno");
const entornoExamplePath = path.resolve(__dirname, "../../.entorno.example");

dotenv.config({ path: entornoExamplePath });
dotenv.config({ path: entornoPath });

const entornoSchema = z.object({
  NODE_ENV: z.enum(["development", "test", "production"]).default("development"),
  PORT: z.coerce.number().default(8001),
  DATABASE_URL: z.string().min(1).default("postgresql://postgres:postgres@localhost:5432/sofia_solutions"),
  APP_MODE: z.enum(["vulnerable", "secure"]).default("secure"),
  CORS_ORIGIN: z.string().default("http://localhost:8000"),
  JWT_ACCESS_SECRET: z.string().min(8).default("change_me_access"),
  JWT_REFRESH_SECRET: z.string().min(8).default("change_me_refresh"),
  JWT_ACCESS_EXPIRES_IN: z.string().default("15m"),
  JWT_REFRESH_EXPIRES_IN: z.string().default("7d"),
  COOKIE_SECURE: z.coerce.boolean().default(false),
  PROMETHEUS_PREFIX: z.string().default("sofia_"),
  RATE_LIMIT_WINDOW_MS: z.coerce.number().default(900000),
  RATE_LIMIT_MAX: z.coerce.number().default(3),
  ADMIN_EMAIL: z.string().email().default("admin@sofia.local"),
  ADMIN_PASSWORD: z.string().min(8).default("SofiaAdmin2026!")
});

export const entorno = entornoSchema.parse(process.env);
