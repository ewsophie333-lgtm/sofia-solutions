/**
 * SOFIA SOLUTIONS - Administrative API Routes
 * Defines protected endpoints for system-wide monitoring and SOC management.
 */

import { Router } from "express";
import { overview, securityEvents, securityMonitor } from "../controllers/admin.controller";
import { requireAuth, requireRole } from "../middleware/auth";
import { asyncHandler } from "../utils/http";

const router = Router();

/**
 * Global protection: All admin routes require a valid JWT session.
 */
router.use(requireAuth);

/**
 * General summary: Accessible by all authenticated users (Client/Admin).
 * Note: The controller internally filters data based on the user's role.
 */
router.get("/overview", asyncHandler(overview));

/**
 * SOC Dashboard Feed: Strictly reserved for users with ADMIN privileges.
 */
router.get("/security-monitor", requireRole("ADMIN"), asyncHandler(securityMonitor));

/**
 * Raw Event Log: Strictly reserved for users with ADMIN privileges.
 */
router.get("/security-events", requireRole("ADMIN"), asyncHandler(securityEvents));

export default router;
