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
