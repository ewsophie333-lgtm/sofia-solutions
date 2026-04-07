import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { ApiError } from "../utils/errors";

export async function listTickets(req: Request, res: Response) {
  const where = req.user?.role === "ADMIN" ? {} : { userId: req.user?.id };
  const tickets = await prisma.ticket.findMany({
    where,
    include: { messages: true },
    orderBy: { createdAt: "desc" }
  });

  res.json(tickets);
}

export async function createTicket(req: Request, res: Response) {
  const { subject, status, priority } = req.body as { subject: string; status?: string; priority?: string };

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

export async function listMessages(req: Request, res: Response) {
  const ticketId = Number(req.params.id);
  const messages = await prisma.ticketMessage.findMany({
    where: { ticketId },
    orderBy: { createdAt: "asc" }
  });

  res.json(messages);
}

export async function createMessage(req: Request, res: Response) {
  const ticketId = Number(req.params.id);
  const ticket = await prisma.ticket.findUnique({ where: { id: ticketId } });
  if (!ticket) {
    throw new ApiError(404, "Ticket not found");
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
