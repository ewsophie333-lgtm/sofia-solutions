import { Router } from "express";
import { createService, listServices } from "../controllers/services.controller";
import { requireAuth, requireRole } from "../middleware/auth";
import { validate } from "../middleware/validate";
import { z } from "zod";
import { asyncHandler } from "../utils/http";

const router = Router();

router.get("/", asyncHandler(listServices));
router.post(
  "/",
  requireAuth,
  requireRole("ADMIN"),
  validate(
    z.object({
      name: z.string().min(3),
      description: z.string().min(10),
      price: z.number().positive(),
      active: z.boolean().default(true)
    })
  ),
  asyncHandler(createService)
);

export default router;
