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
import { checkout, history } from "../controladores/pagos.controlador";
import { requireAuth } from "../middlewares/autenticacion";
import { validar } from "../middlewares/validar";
import { z } from "zod";
import { asyncHandler } from "../utilidades/http";

const router = Router();

router.use(requireAuth);

router.post(
  "/checkout",
  validar(
    z.object({
      serviceId: z.number().int().positive(),
      amount: z.number().positive().optional(),
      currency: z.string().default("EUR"),
      last4: z.string().length(4).default("4242"),
      brand: z.string().default("visa")
    })
  ),
  asyncHandler(checkout)
);

router.get("/history", asyncHandler(history));

export default router;
