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
