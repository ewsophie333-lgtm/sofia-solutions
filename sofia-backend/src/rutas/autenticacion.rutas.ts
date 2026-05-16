/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS
 * ============================================================================
 * 
 * Este código lo he desarrollado para mi proyecto final (TFG). Aquí trato de
 * aplicar todo lo que he aprendido tanto en el curso como en la empresa.
 * 
 * Autor: Sofia Gomez
 * Año: 2026
 * ============================================================================
 */
import { Router } from "express";
import { login, loginV1, loginV2, logout, refresh, register } from "../controladores/autenticacion.controlador";
import { validar } from "../middlewares/validar";
import { authRateLimiter } from "../middlewares/limitePeticiones";
import { z } from "zod";
import { asyncHandler } from "../utilidades/http";

const router = Router();

const registerSchema = z.object({
  email: z.string().email(),
  password: z.string().min(8),
  role: z.enum(["CLIENT", "ADMIN"]).optional()
});

const secureLoginSchema = z.object({
  email: z.string().email(),
  password: z.string().min(1)
});

const loginV2Schema = z.object({
  email: z.string().email(),
  password: z.string().min(1),
  otp: z.string().optional()
});

const vulnerableLoginSchema = z.object({
  email: z.string().min(1),
  password: z.string().min(1)
});

router.post("/register", validar(registerSchema), asyncHandler(register));
router.post("/login", validar(secureLoginSchema), asyncHandler(login));
router.post("/login/secure", validar(secureLoginSchema), asyncHandler(login));
router.post("/login/v2", validar(loginV2Schema), asyncHandler(loginV2));
router.post("/login/vulnerable", asyncHandler(loginV1));
router.post("/refresh", asyncHandler(refresh));
router.post("/logout", asyncHandler(logout));

export default router;
