/**
 * SOFIA SOLUTIONS - Rutas de Soporte y Tickets
 * Gestiona la comunicación técnica entre los clientes y el equipo SOC.
 */

import { Router } from "express";
import { createMessage, createTicket, listMessages, listTickets } from "../controladores/tickets.controlador";
import { requireAuth } from "../middlewares/autenticacion";
import { validar } from "../middlewares/validar";
import { z } from "zod";
import { asyncHandler } from "../utilidades/http";

const router = Router();

/**
 * Todas las operaciones de tickets requieren autenticación previa.
 */
router.use(requireAuth);

/**
 * Recupera el historial de tickets del usuario autenticado.
 */
router.get("/", asyncHandler(listTickets));

/**
 * Registra una nueva solicitud de soporte.
 * Incluye validación de esquema para asegurar la calidad de la información.
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
 * Hilos de Mensajes: Recupera o envía mensajes dentro de un ticket específico.
 */
router.get("/:id/messages", asyncHandler(listMessages));
router.post(
  "/:id/messages",
  validar(z.object({ content: z.string().min(1, "El mensaje no puede estar vacío") })),
  asyncHandler(createMessage)
);

export default router;
