import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { env } from "../config/env";
import { getSocNotifications } from "../services/soc.service";

export async function overview(_req: Request, res: Response) {
  const [payments, tickets, securityEvents, services, users] = await Promise.all([
    prisma.payment.findMany(),
    prisma.ticket.findMany({ orderBy: { createdAt: "desc" }, take: 5 }),
    prisma.securityEvent.findMany({ orderBy: { timestamp: "desc" }, take: 5 }),
    prisma.service.findMany({ orderBy: { id: "asc" }, take: 5 }),
    prisma.user.findMany()
  ]);

  res.json({
    year: 2026,
    revenue: payments.reduce((sum, item) => sum + item.amount, 0),
    secureLogins: users.length * 24,
    blockedAttacks: securityEvents.filter((item) => item.action === "BLOCKED").length,
    openTickets: tickets.filter((item) => item.status !== "CLOSED").length,
    appMode: env.APP_MODE,
    services,
    recentTickets: tickets,
    securityEvents,
    socNotifications: getSocNotifications()
  });
}

export async function securityEvents(_req: Request, res: Response) {
  const events = await prisma.securityEvent.findMany({ orderBy: { timestamp: "desc" } });
  res.json(events);
}
