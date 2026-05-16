/**
 * SOFIA SOLUTIONS - Mi Controlador para el Centro de Ayuda y Tickets
 * 
 * Aquí es donde gestiono los mensajes de soporte e informes que nos mandan.
 * He programado una lógica para que cada uno solo vea lo suyo y todo vaya en orden.
 */

import type { Request, Response } from "express";
import { prisma } from "../configuracion/prisma";
import { ApiError } from "../utilidades/errores";
import { getRequestMode } from "../utilidades/modo";

/**
 * Saco la lista de tickets que tocan. Si eres el jefe (ADMIN) los ves todos,
 * pero si eres un cliente normal, solo te enseño los tuyos.
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
 * Aquí es donde se guardan las nuevas peticiones de ayuda.
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
 * Con esto recupero todos los mensajes de una conversación específica.
 */
export async function listMessages(req: Request, res: Response) {
  const ticketId = Number(req.params.id);
  const modo = getRequestMode(req);

  // PROTECCIÓN CONTRA IDOR: He puesto esto para que nadie cotillee tickets de otros.
  // Solo lo dejo pasar si el modo seguro está apagado o si eres el ADMIN.
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
 * Esta parte sirve para enviar mensajes nuevos a un ticket.
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
