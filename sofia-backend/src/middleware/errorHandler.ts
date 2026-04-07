import type { Request, Response, NextFunction } from "express";
import { ZodError } from "zod";
import { logger } from "../config/logger";
import { ApiError } from "../utils/errors";

export function errorHandler(
  error: unknown,
  req: Request,
  res: Response,
  _next: NextFunction
) {
  if (error instanceof ApiError) {
    res.status(error.statusCode).json({ message: error.message, details: error.details });
    return;
  }

  if (error instanceof ZodError) {
    res.status(400).json({ message: "Validation error", details: error.flatten() });
    return;
  }

  logger.error({
    message: "unhandled_error",
    requestId: req.requestId,
    error
  });

  res.status(500).json({ message: "Internal server error" });
}
