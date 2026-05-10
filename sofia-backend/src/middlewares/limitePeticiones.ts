import limitePeticiones from "express-rate-limit";
import type { Request, Response, NextFunction } from "express";
import { entorno } from "../configuracion/entorno";
import { getRequestMode, isVulnerableMode } from "../utilidades/modo";

const secureAuthRateLimiter = limitePeticiones({
  windowMs: entorno.RATE_LIMIT_WINDOW_MS,
  max: entorno.RATE_LIMIT_MAX,
  standardHeaders: true,
  legacyHeaders: false,
  message: { message: "Too many auth attempts, slow down." }
});

export function authRateLimiter(req: Request, res: Response, next: NextFunction) {
  if (isVulnerableMode(getRequestMode(req))) {
    // VULNERABLE: no se aplica rate limit al login.
    next();
    return;
  }

  secureAuthRateLimiter(req, res, next);
}
