import { Router } from "express";
import { register, refresh, logout, loginV1 } from "../controllers/auth.controller";
import { validate } from "../middleware/validate";
import { z } from "zod";
import { asyncHandler } from "../utils/http";

const router = Router();

const registerSchema = z.object({
  email: z.string().email(),
  password: z.string().min(8),
  role: z.enum(["CLIENT", "ADMIN"]).optional(),
});

const loginSchema = z.object({
  email: z.string().min(1),
  password: z.string().min(1),
});

router.post("/register", validate(registerSchema), asyncHandler(register));
router.post("/login", validate(loginSchema), asyncHandler(loginV1));
router.post("/refresh", asyncHandler(refresh));
router.post("/logout", asyncHandler(logout));

export default router;
