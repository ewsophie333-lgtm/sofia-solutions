import type { Request, Response, NextFunction } from "express";
import type { AppMode } from "../utilidades/modo";
import { entorno } from "../configuracion/entorno";

function normalizeMode(value: unknown): AppMode | null {
  if (value === "secure" || value === "vulnerable") {
    return value;
  }

  return null;
}

export function modoDemoResolver(req: Request, _res: Response, next: NextFunction) {
  const fromHeader = normalizeMode(req.header("x-demo-modo"));
  const fromQuery = normalizeMode(req.query.modoDemo);
  const fromPath = req.path.includes("/api/v1")
    ? "vulnerable"
    : req.path.includes("/api/v2")
      ? "secure"
      : req.path.includes("/secure")
        ? "secure"
        : req.path.includes("/vulnerable")
          ? "vulnerable"
          : null;

  req.modoDemo = fromHeader ?? fromQuery ?? fromPath ?? entorno.APP_MODE;
  next();
}
