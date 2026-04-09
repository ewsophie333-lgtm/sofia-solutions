import type { Request, Response, NextFunction } from "express";
import type { AppMode } from "../utils/mode";
import { env } from "../config/env";

function normalizeMode(value: unknown): AppMode | null {
  if (value === "secure" || value === "vulnerable") {
    return value;
  }

  return null;
}

export function demoModeResolver(req: Request, _res: Response, next: NextFunction) {
  const fromHeader = normalizeMode(req.header("x-demo-mode"));
  const fromQuery = normalizeMode(req.query.demoMode);
  const fromPath = req.path.includes("/api/v1")
    ? "vulnerable"
    : req.path.includes("/api/v2")
      ? "secure"
      : req.path.includes("/secure")
        ? "secure"
        : req.path.includes("/vulnerable")
          ? "vulnerable"
          : null;

  req.demoMode = fromHeader ?? fromQuery ?? fromPath ?? env.APP_MODE;
  next();
}
