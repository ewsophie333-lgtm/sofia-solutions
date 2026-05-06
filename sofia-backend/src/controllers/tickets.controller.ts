/**
 * SOFIA SOLUTIONS - Helpdesk & Ticketing Controller
 * 
 * Manages the lifecycle of support requests and incident reports.
 * Implements logic for role-based data isolation and real-time messaging.
 */

import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { ApiError } from "../utils/errors";

/**
 * Lists all tickets associated with the current session context.
 * Admins receive global access; Clients are restricted to their own userId.
 */
export async function listTickets(req: Request, res: Response) {
  const where = req.user?.role === "ADMIN" ? {} : { userId: req.user?.id };
  const tickets = await prisma.ticket.findMany({
    where,
    include: { messages: true },
    orderBy: { createdAt: "desc" }
  });

  res.json(tickets);
}

/**
 * Persistence layer for new support requests.
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
 * Thread Retrieval Logic.
 * Fetch messages for a specific incident container.
 */
export async function listMessages(req: Request, res: Response) {
  const ticketId = Number(req.params.id);
  const messages = await prisma.ticketMessage.findMany({
    where: { ticketId },
    orderBy: { createdAt: "asc" }
  });

  res.json(messages);
}

/**
 * Multi-party Communication Handler.
 */
export async function createMessage(req: Request, res: Response) {
  const ticketId = Number(req.params.id);
  const ticket = await prisma.ticket.findUnique({ where: { id: ticketId } });
  
  if (!ticket) {
    throw new ApiError(404, "Target ticket not found in the persistent registry");
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
