/**
 * SOFIA SOLUTIONS - Controlador de Helpdesk & Tickets
 * 
 * Gestiona el ciclo de vida de las solicitudes de soporte e informes de incidentes.
 * Implementa lógica de aislamiento de datos basada en roles y mensajería en tiempo real.
 */

import type { Request, Response } from "express";
import { prisma } from "../configuracion/prisma";
import { ApiError } from "../utilidades/errores";
import { getRequestMode } from "../utilidades/modo";

/**
 * Lista todos los tickets asociados al contexto de la sesión actual.
 * Los administradores tienen acceso global; los clientes están restringidos a su propio userId.
 */
export async function listTickets(req: Request, res: Response) {
  const where = req.user?.role === "ADMIN" ? {} : { userId: req.user?.id };
  const tickets = await prisma.ticket.findMany({
    where,
    include: {
      messages: true,
      user: {
        include: {
          customer: true
        }
      }
    },
    orderBy: { createdAt: "desc" }
  });

  res.json(tickets);
}

/**
 * Capa de persistencia para nuevas peticiones de soporte.
 */
export async function createTicket(req: Request, res: Response) {
  const { subject, status, priority } = req.body;

  const ticket = await prisma.ticket.create({
    data: {
      userId: req.user!.id,
      subject,
      status: status ?? "OPEN",
      priority: priority ?? "MEDIUM"
    }
  });

  res.status(201).json(ticket);
}

/**
 * Lógica de recuperación de hilos.
 * Obtiene mensajes para un contenedor de incidente específico.
 */
export async function listMessages(req: Request, res: Response) {
  const ticketId = Number(req.params.id);
  const modo = getRequestMode(req);

  // PROTECCIÓN IDOR: En modo seguro, validamos que el ticket pertenezca al usuario (o sea ADMIN)
  if (modo === "secure" && req.user?.role !== "ADMIN") {
    const ticket = await prisma.ticket.findUnique({
      where: { id: ticketId },
      select: { userId: true }
    });

    if (!ticket || ticket.userId !== req.user?.id) {
      throw new ApiError(403, "Acceso denegado: No tienes permisos para ver los mensajes de este ticket (IDOR Blocked)");
    }
  }

  const messages = await prisma.ticketMessage.findMany({
    where: { ticketId },
    orderBy: { createdAt: "asc" }
  });

  res.json(messages);
}

/**
 * Manejador de comunicación multi-participante.
 */
export async function createMessage(req: Request, res: Response) {
  const ticketId = Number(req.params.id);
  const ticket = await prisma.ticket.findUnique({ where: { id: ticketId } });

  if (!ticket) {
    throw new ApiError(404, "Ticket de destino no encontrado en el registro persistente");
  }

  const message = await prisma.ticketMessage.create({
    data: {
      ticketId,
      senderId: req.user!.id,
      content: req.body.content
    }
  });

  res.status(201).json(message);
}
