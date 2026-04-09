import type { Request } from "express";
import { env } from "../config/env";

export type AppMode = "secure" | "vulnerable";

export const isSecureMode = (mode: AppMode = env.APP_MODE) => mode === "secure";
export const isVulnerableMode = (mode: AppMode = env.APP_MODE) => mode === "vulnerable";

export function getRequestMode(req: Request): AppMode {
  return req.demoMode ?? env.APP_MODE;
}
