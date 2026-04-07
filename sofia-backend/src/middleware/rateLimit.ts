import rateLimit from "express-rate-limit";
import { env } from "../config/env";
import { isVulnerableMode } from "../utils/mode";

export const authRateLimiter = isVulnerableMode()
  ? (_req: unknown, _res: unknown, next: () => void) => {
      // VULNERABLE: no se aplica rate limit al login.
      next();
    }
  : rateLimit({
      windowMs: env.RATE_LIMIT_WINDOW_MS,
      max: env.RATE_LIMIT_MAX,
      standardHeaders: true,
      legacyHeaders: false,
      message: { message: "Too many auth attempts, slow down." }
    });
