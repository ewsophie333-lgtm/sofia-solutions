import type { Request, Response, NextFunction } from "express";
import { verifyAccessToken } from "../utils/jwt";
import { ApiError } from "../utils/errors";

export function requireAuth(req: Request, _res: Response, next: NextFunction) {
  try {
    const header = req.headers.authorization;
    const token = header?.startsWith("Bearer ") ? header.slice(7) : req.cookies?.accessToken;
    if (!token) {
      throw new ApiError(401, "Missing access token");
    }

    const payload = verifyAccessToken(token);
    req.user = {
      id: payload.sub,
      email: payload.email,
      role: payload.role as "ADMIN" | "CLIENT"
    };
    next();
  } catch (error) {
    next(error);
  }
}

export function requireRole(role: "ADMIN" | "CLIENT") {
  return (req: Request, _res: Response, next: NextFunction) => {
    if (!req.user || req.user.role !== role) {
      next(new ApiError(403, "Insufficient permissions"));
      return;
    }

    next();
  };
}
