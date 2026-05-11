/**
 * SOFIA SOLUTIONS - Rutas de API Administrativa
 * Define endpoints protegidos para monitorización a nivel de sistema y gestión SOC.
 */

import { Router } from "express";
import { overview, securityEvents, securityMonitor } from "../controladores/admin.controlador";
import { requireAuth, requireRole } from "../middlewares/autenticacion";
import { asyncHandler } from "../utilidades/http";

const router = Router();

/**
 * Protección global: Todas las rutas de administración requieren una sesión JWT válida.
 */
router.use(requireAuth);

/**
 * Resumen general: Accesible por todos los usuarios autenticados (Cliente/Admin).
 * Nota: El controlador filtra internamente los datos según el rol del usuario.
 */
router.get("/overview", asyncHandler(overview));

/**
 * Feed del Dashboard SOC: Estrictamente reservado para usuarios con privilegios ADMIN.
 */
router.get("/security-monitor", requireRole("ADMIN"), asyncHandler(securityMonitor));

/**
 * Registro de Eventos en Crudo: Estrictamente reservado para usuarios con privilegios ADMIN.
 */
router.get("/security-events", requireRole("ADMIN"), asyncHandler(securityEvents));

export default router;
