/**
 * SOFIA SOLUTIONS - Mis Rutas de Soporte
 * Aquí defino cómo se llega a los tickets y mensajes desde el frontend.
 */

import { Router } from "express";
import { createMessage, createTicket, listMessages, listTickets } from "../controladores/tickets.controlador";
import { requireAuth } from "../middlewares/autenticacion";
import { validar } from "../middlewares/validar";
import { z } from "zod";
import { asyncHandler } from "../utilidades/http";

const router = Router();

/**
 * Para tocar cualquier cosa de tickets tienes que estar logueado sí o sí.
 */
router.use(requireAuth);

/**
 * Obtener todos los tickets.
 */
router.get("/", asyncHandler(listTickets));

/**
 * Crear un ticket nuevo. Valido los datos para que no me metan basura.
 */
router.post(
  "/",
  validar(
    z.object({
      subject: z.string().min(5, "El asunto debe tener al menos 5 caracteres"),
      status: z.string().optional(),
      priority: z.string().optional()
    })
  ),
  asyncHandler(createTicket)
);

/**
 * Aquí gestiono los mensajes individuales de cada ticket.
 */
router.get("/:id/messages", asyncHandler(listMessages));
router.post(
  "/:id/messages",
  validar(z.object({ content: z.string().min(1, "El mensaje no puede estar vacío") })),
  asyncHandler(createMessage)
);

export default router;
