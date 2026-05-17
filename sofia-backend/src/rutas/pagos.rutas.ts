/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS
 * ============================================================================
 * 
 * Este código lo he desarrollado para mi proyecto final de ASIR. Aquí trato de
 * aplicar todo lo que he aprendido tanto en el curso como en la empresa.
 * 
 * Autor: Sofia Gomez
 * Año: 2026
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
