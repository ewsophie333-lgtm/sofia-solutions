/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS - MI SISTEMA DE CIBERSEGURIDAD
 * ============================================================================
 * 
 * Este código lo he desarrollado para mi proyecto final (TFG). Aquí trato de
 * aplicar todo lo que he aprendido sobre seguridad defensiva y monitorización.
 * 
 * Autor: Sofia Gomez
 * Año: 2026
 * ============================================================================
 */
import { Router } from "express";
import { z } from "zod";
import {
  createService,
  listServices,
  serviceCatalog,
  serviceDetail,
  serviceEffectiveness,
} from "../controladores/servicios.controlador";
import { requireAuth, requireRole } from "../middlewares/autenticacion";
import { validar } from "../middlewares/validar";
import { asyncHandler } from "../utilidades/http";

const router = Router();

router.get("/", asyncHandler(listServices));
router.get("/catalog", asyncHandler(serviceCatalog));
router.get("/effectiveness", asyncHandler(serviceEffectiveness));
router.get("/:id", asyncHandler(serviceDetail));
router.post(
  "/",
  requireAuth,
  requireRole("ADMIN"),
  validar(
    z.object({
      name: z.string().min(3),
      description: z.string().min(8),
      price: z.number().positive(),
      active: z.boolean().optional(),
      category: z.string().min(3).optional(),
      tier: z.string().min(3).optional(),
      slaHours: z.number().int().positive().max(72).optional(),
    }),
  ),
  asyncHandler(createService),
);

export default router;
