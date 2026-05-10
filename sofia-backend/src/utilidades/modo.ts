import type { Request } from "express";
import { entorno } from "../configuracion/entorno";

export type AppMode = "secure" | "vulnerable";

export const isSecureMode = (modo: AppMode = entorno.APP_MODE) => modo === "secure";
export const isVulnerableMode = (modo: AppMode = entorno.APP_MODE) => modo === "vulnerable";

export function getRequestMode(req: Request): AppMode {
  return req.modoDemo ?? entorno.APP_MODE;
}
