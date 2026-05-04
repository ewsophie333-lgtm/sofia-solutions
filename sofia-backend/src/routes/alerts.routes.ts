import { Router } from "express";
import { 
  createUrgentAlert, 
  listAlerts, 
  getAlertById, 
  acknowledgeAlert 
} from "../controllers/alerts.controller";
import { requireAuth } from "../middleware/auth";

const router = Router();

// Crear alerta urgente
router.post("/urgent", requireAuth, createUrgentAlert);

// Listar alertas del usuario/admin
router.get("/", requireAuth, listAlerts);

// Obtener alerta por ID
router.get("/:id", requireAuth, getAlertById);

// Reconocer alerta
router.patch("/:id/acknowledge", requireAuth, acknowledgeAlert);

export default router;
