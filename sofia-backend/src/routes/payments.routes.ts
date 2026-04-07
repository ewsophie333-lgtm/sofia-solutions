import { Router } from "express";
import { checkout, history } from "../controllers/payments.controller";
import { requireAuth } from "../middleware/auth";
import { validate } from "../middleware/validate";
import { z } from "zod";
import { asyncHandler } from "../utils/http";

const router = Router();

router.use(requireAuth);

router.post(
  "/checkout",
  validate(
    z.object({
      serviceId: z.number().int().positive(),
      amount: z.number().positive().optional(),
      currency: z.string().default("EUR"),
      last4: z.string().length(4).default("4242"),
      brand: z.string().default("visa")
    })
  ),
  asyncHandler(checkout)
);

router.get("/history", asyncHandler(history));

export default router;
