import { Router } from "express";
import authRoutes from "./auth.routes";
import servicesRoutes from "./services.routes";
import paymentsRoutes from "./payments.routes";
import ticketsRoutes from "./tickets.routes";
import adminRoutes from "./admin.routes";

const router = Router();

router.use("/auth", authRoutes);
router.use("/services", servicesRoutes);
router.use("/payments", paymentsRoutes);
router.use("/tickets", ticketsRoutes);
router.use("/admin", adminRoutes);

export default router;
