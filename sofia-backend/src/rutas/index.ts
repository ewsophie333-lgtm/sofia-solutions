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
import authRoutes from "./autenticacion.rutas";
import servicesRoutes from "./servicios.rutas";
import paymentsRoutes from "./pagos.rutas";
import ticketsRoutes from "./tickets.rutas";
import adminRoutes from "./admin.rutas";
import alertsRoutes from "./alertas.rutas";

const router = Router();

router.use("/autenticacion", authRoutes);
router.use("/services", servicesRoutes);
router.use("/payments", paymentsRoutes);
router.use("/tickets", ticketsRoutes);
router.use("/admin", adminRoutes);
router.use("/alerts", alertsRoutes);

router.get("/health", (req, res) => {
    res.json({ status: "ok", mode: "PROXIED" });
});

export default router;
