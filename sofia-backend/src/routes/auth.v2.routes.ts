import { Router } from "express";
import rateLimit from "express-rate-limit";
import { register, refresh, logout, loginV2, csrfToken } from "../controllers/auth.controller";
import { validate } from "../middleware/validate";
import { z } from "zod";
import { asyncHandler } from "../utils/http";

const router = Router();

const secureLoginLimiter = rateLimit({
  windowMs: 15 * 60 * 1000,
  max: 5,
  standardHeaders: true,
  legacyHeaders: false,
  message: { message: "Too many auth attempts, slow down." },
});

const registerSchema = z.object({
  email: z.string().email(),
  password: z
    .string()
    .min(12)
    .regex(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/, "Password too weak"),
  role: z.enum(["CLIENT", "ADMIN"]).optional(),
});

const loginSchema = z.object({
  email: z.string().email(),
  password: z
    .string()
    .min(12)
    .regex(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/, "Password too weak"),
  otp: z.string().length(6).optional(),
});

router.get("/csrf", asyncHandler(csrfToken));
router.post("/register", validate(registerSchema), asyncHandler(register));
router.post("/login", secureLoginLimiter, validate(loginSchema), asyncHandler(loginV2));
router.post("/refresh", asyncHandler(refresh));
router.post("/logout", asyncHandler(logout));

export default router;
