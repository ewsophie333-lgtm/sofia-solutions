import { Router } from "express";
import { login, logout, refresh, register } from "../controllers/auth.controller";
import { validate } from "../middleware/validate";
import { authRateLimiter } from "../middleware/rateLimit";
import { z } from "zod";
import { asyncHandler } from "../utils/http";

const router = Router();

const registerSchema = z.object({
  email: z.string().email(),
  password: z.string().min(8),
  role: z.enum(["CLIENT", "ADMIN"]).optional()
});

const loginSchema = z.object({
  email: z.string().email(),
  password: z.string().min(1)
});

router.post("/register", validate(registerSchema), asyncHandler(register));
router.post("/login", authRateLimiter as never, validate(loginSchema), asyncHandler(login));
router.post("/refresh", asyncHandler(refresh));
router.post("/logout", asyncHandler(logout));

export default router;
