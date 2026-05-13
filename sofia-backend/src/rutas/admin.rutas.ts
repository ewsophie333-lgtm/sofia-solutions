/**
 * SOFIA SOLUTIONS - Rutas de API Administrativa
 * Define endpoints protegidos para monitorización a nivel de sistema y gestión SOC.
 */

import { Router } from "express";
import { overview, securityEvents, securityMonitor, getGeoTelemetry, getGeomapData, getGeomapDataAdvanced } from "../controladores/admin.controlador";
import { requireAuth, requireRole } from "../middlewares/autenticacion";
import { asyncHandler } from "../utilidades/http";

const router = Router();

/**
 * Telemetría para Mapa de Grafana: Mockup dinámico.
 * Nota: Endpoints públicos para facilitar la integración con Grafana Provisioning.
 */
router.get("/geo-telemetry", asyncHandler(getGeoTelemetry));
router.get("/geomap-data", asyncHandler(getGeomapData));
router.get("/geomap-data-advanced", asyncHandler(getGeomapDataAdvanced));

/**
 * Protección global: El resto de rutas administrativas requieren una sesión JWT válida.
 */
router.use(requireAuth);

/**
 * Resumen general: Accesible por todos los usuarios autenticados (Cliente/Admin).
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
