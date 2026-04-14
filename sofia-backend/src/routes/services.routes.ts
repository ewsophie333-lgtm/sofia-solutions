import { Router } from "express";
import { z } from "zod";
import {
  createService,
  listServices,
  serviceCatalog,
  serviceDetail,
  serviceEffectiveness,
} from "../controllers/services.controller";
import { requireAuth, requireRole } from "../middleware/auth";
import { validate } from "../middleware/validate";
import { asyncHandler } from "../utils/http";

const router = Router();

router.get("/", asyncHandler(listServices));
router.get("/catalog", asyncHandler(serviceCatalog));
router.get("/effectiveness", asyncHandler(serviceEffectiveness));
router.get("/:id", asyncHandler(serviceDetail));
router.post(
  "/",
  requireAuth,
  requireRole("ADMIN"),
  validate(
    z.object({
      name: z.string().min(3),
      description: z.string().min(8),
      price: z.number().positive(),
      active: z.boolean().optional(),
      category: z.string().min(3).optional(),
      tier: z.string().min(3).optional(),
      slaHours: z.number().int().positive().max(72).optional(),
    }),
  ),
  asyncHandler(createService),
);

export default router;
