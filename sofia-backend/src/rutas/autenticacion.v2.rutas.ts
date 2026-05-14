/**
 * ============================================================================
 * SOFIA SOLUTIONS - SECURITY & MONITORING PLATFORM
 * ============================================================================
 * 
 * Este archivo forma parte de la arquitectura base del backend de Sofia Solutions.
 * Ha sido disenado siguiendo principios de codigo limpio, seguridad por diseno,
 * y alta escalabilidad para entornos criticos e industriales.
 * 
 * @module SofiaSolutions
 * Sofia Gomez
 * @copyright 2026
 * ============================================================================
 */
import { Router } from "express";
import limitePeticiones from "express-rate-limit";
import { register, refresh, logout, loginV2, csrfToken } from "../controladores/autenticacion.controlador";
import { validar } from "../middlewares/validar";
import { z } from "zod";
import { asyncHandler } from "../utilidades/http";

const router = Router();

const secureLoginLimiter = limitePeticiones({
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
router.post("/register", validar(registerSchema), asyncHandler(register));
router.post("/login", secureLoginLimiter, validar(loginSchema), asyncHandler(loginV2));
router.post("/refresh", asyncHandler(refresh));
router.post("/logout", asyncHandler(logout));

export default router;
