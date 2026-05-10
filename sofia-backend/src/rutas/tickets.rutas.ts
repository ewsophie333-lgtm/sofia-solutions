/**
 * SOFIA SOLUTIONS - Helpdesk & Ticketing Routes
 * Manages communication between clients and the SOC team.
 */

import { Router } from "express";
import { createMessage, createTicket, listMessages, listTickets } from "../controladores/tickets.controlador";
import { requireAuth } from "../middlewares/autenticacion";
import { validar } from "../middlewares/validar";
import { z } from "zod";
import { asyncHandler } from "../utilidades/http";

const router = Router();

/**
 * All ticketing operations require authentication.
 */
router.use(requireAuth);

/**
 * Retrieves the ticket backlog for the current user.
 */
router.get("/", asyncHandler(listTickets));

/**
 * Submits a new support request.
 * Includes schema validation for subject length.
 */
router.post(
  "/",
  validar(
    z.object({
      subject: z.string().min(5),
      status: z.string().optional(),
      priority: z.string().optional()
    })
  ),
  asyncHandler(createTicket)
);

/**
 * Message Threads: Fetches/Posts messages within a specific ticket context.
 */
router.get("/:id/messages", asyncHandler(listMessages));
router.post(
  "/:id/messages",
  validar(z.object({ content: z.string().min(1) })),
  asyncHandler(createMessage)
);

export default router;
