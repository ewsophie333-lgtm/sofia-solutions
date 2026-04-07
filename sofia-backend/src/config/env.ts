import dotenv from "dotenv";
import path from "path";
import { z } from "zod";

const envPath = path.resolve(__dirname, "../../.env");
const envExamplePath = path.resolve(__dirname, "../../.env.example");

dotenv.config({ path: envExamplePath });
dotenv.config({ path: envPath });

const envSchema = z.object({
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
  RATE_LIMIT_MAX: z.coerce.number().default(10),
  ADMIN_EMAIL: z.string().email().default("admin@sofia.local"),
  ADMIN_PASSWORD: z.string().min(8).default("Admin123!")
});

export const env = envSchema.parse(process.env);
