import { Router } from "express";
import authRoutes from "./autenticacion.rutas";
import servicesRoutes from "./servicios.rutas";
import paymentsRoutes from "./pagos.rutas";
import ticketsRoutes from "./tickets.rutas";
import adminRoutes from "./admin.rutas";
import alertsRoutes from "./alertas.rutas";

const router = Router();

router.use("/auth", authRoutes);
router.use("/services", servicesRoutes);
router.use("/payments", paymentsRoutes);
router.use("/tickets", ticketsRoutes);
router.use("/admin", adminRoutes);
router.use("/alerts", alertsRoutes);

export default router;
