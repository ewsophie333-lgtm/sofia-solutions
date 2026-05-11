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
 * @author Sofia Solutions Architecture Team
 * @copyright 2026
 * ============================================================================
 */
import { Router } from "express";
import { 
  createUrgentAlert, 
  listAlerts, 
  getAlertById, 
  acknowledgeAlert 
} from "../controladores/alertas.controlador";
import { requireAuth } from "../middlewares/autenticacion";

const router = Router();

// Crear alerta urgente
router.post("/urgent", requireAuth, createUrgentAlert);

// Ruta de prueba para n8n/Gmail (sin auth para testing rápido)
router.post("/test", createUrgentAlert);

// Listar alertas del usuario/admin
router.get("/", requireAuth, listAlerts);

// Obtener alerta por ID
router.get("/:id", requireAuth, getAlertById);

// Reconocer alerta
router.patch("/:id/acknowledge", requireAuth, acknowledgeAlert);

export default router;
