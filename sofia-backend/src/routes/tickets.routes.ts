import { Router } from "express";
import { createMessage, createTicket, listMessages, listTickets } from "../controllers/tickets.controller";
import { requireAuth } from "../middleware/auth";
import { validate } from "../middleware/validate";
import { z } from "zod";
import { asyncHandler } from "../utils/http";

const router = Router();

router.use(requireAuth);

router.get("/", asyncHandler(listTickets));
router.post(
  "/",
  validate(
    z.object({
      subject: z.string().min(5),
      status: z.string().optional(),
      priority: z.string().optional()
    })
  ),
  asyncHandler(createTicket)
);
router.get("/:id/messages", asyncHandler(listMessages));
router.post(
  "/:id/messages",
  validate(z.object({ content: z.string().min(1) })),
  asyncHandler(createMessage)
);

export default router;
