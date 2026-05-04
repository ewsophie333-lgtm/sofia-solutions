import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { ApiError } from "../utils/errors";
import axios from "axios";

const N8N_WEBHOOK_URL = process.env.N8N_WEBHOOK_URL || "http://n8n:5678/webhook/alert";
const ALERT_EMAIL = "ewsophie333@gmail.com";

export async function createUrgentAlert(req: Request, res: Response) {
  const { title, description, severity = "CRITICAL" } = req.body as {
    title: string;
    description: string;
    severity?: string;
  };

  if (!title || !description) {
    throw new ApiError(400, "title and description are required");
  }

  // Guardar alerta en BD
  const alert = await prisma.alert.create({
    data: {
      userId: req.user?.id,
      title,
      description,
      severity,
      status: "ACTIVE",
      notifiedAt: new Date(),
    },
  });

  // Enviar webhook a n8n para alertas urgentes/críticas
  if (["URGENT", "CRITICAL"].includes(severity)) {
    try {
      await axios.post(N8N_WEBHOOK_URL, {
        alertId: alert.id,
        title: alert.title,
        description: alert.description,
        severity: alert.severity,
        timestamp: alert.notifiedAt,
        recipientEmail: ALERT_EMAIL,
        userName: req.user?.email || "System",
      });
    } catch (error) {
      console.error("Error sending webhook to n8n:", error);
      // No fallar la creación de la alerta si n8n no responde
    }
  }

  res.status(201).json({
    ...alert,
    webhookSent: ["URGENT", "CRITICAL"].includes(severity),
  });
}

export async function listAlerts(req: Request, res: Response) {
  const alerts = await prisma.alert.findMany({
    where: req.user?.role === "ADMIN" ? {} : { userId: req.user?.id },
    orderBy: { notifiedAt: "desc" },
    take: 50,
  });

  res.json(alerts);
}

export async function getAlertById(req: Request, res: Response) {
  const alertId = Number(req.params.id);
  const alert = await prisma.alert.findUnique({
    where: { id: alertId },
  });

  if (!alert) {
    throw new ApiError(404, "Alert not found");
  }

  res.json(alert);
}

export async function acknowledgeAlert(req: Request, res: Response) {
  const alertId = Number(req.params.id);
  
  const alert = await prisma.alert.update({
    where: { id: alertId },
    data: {
      status: "ACKNOWLEDGED",
      acknowledgedAt: new Date(),
      acknowledgedBy: req.user?.id,
    },
  });

  res.json(alert);
}
