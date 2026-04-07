import { Router } from "express";
import { overview, securityEvents } from "../controllers/admin.controller";
import { requireAuth, requireRole } from "../middleware/auth";
import { asyncHandler } from "../utils/http";

const router = Router();

router.use(requireAuth, requireRole("ADMIN"));
router.get("/overview", asyncHandler(overview));
router.get("/security-events", asyncHandler(securityEvents));

export default router;
