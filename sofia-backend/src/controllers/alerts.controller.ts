/**
 * SOFIA SOLUTIONS - Security Alerting & Notification Controller
 * 
 * Manages the generation, lifecycle, and external routing of security alerts.
 * Interfaces with n8n workflow engine for real-time critical notifications.
 */

import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { ApiError } from "../utils/errors";
import axios from "axios";

const N8N_WEBHOOK_URL = process.env.N8N_WEBHOOK_URL || "http://n8n:5678/webhook/alert";
const ALERT_EMAIL = "ewsophie333@gmail.com";

/**
 * High-Priority Alert Creation Engine.
 * Triggers external notification workflows for URGENT/CRITICAL severities.
 */
export async function createUrgentAlert(req: Request, res: Response) {
  const { title, description, severity = "CRITICAL" } = req.body;

  if (!title || !description) {
    throw new ApiError(400, "Incomplete payload: title and description are mandatory");
  }

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

  // Critical Notification Routing (External Workflow Integration)
  if (["URGENT", "CRITICAL"].includes(severity.toUpperCase())) {
    try {
      // Fetch user's customer information to include in the alert
      const userWithCustomer = req.user?.id ? await prisma.user.findUnique({
        where: { id: req.user.id },
        include: { customer: true }
      }) : null;

      await axios.post(N8N_WEBHOOK_URL, {
        alertId: alert.id,
        title: alert.title,
        description: alert.description,
        severity: alert.severity,
        timestamp: alert.notifiedAt,
        recipientEmail: ALERT_EMAIL,
        userName: req.user?.email || "System-Monitor",
        clientName: userWithCustomer?.customer?.name || "Global / Internal"
      });
    } catch (error) {
      console.error("[INTEGRATION] Failed to route alert to n8n workflow engine:", error);
    }
  }

  res.status(201).json({
    ...alert,
    webhookSent: ["URGENT", "CRITICAL"].includes(severity.toUpperCase()),
  });
}

/**
 * Retrieves the security alert backlog.
 */
export async function listAlerts(req: Request, res: Response) {
  const alerts = await prisma.alert.findMany({
    where: req.user?.role === "ADMIN" ? {} : { userId: req.user?.id },
    orderBy: { notifiedAt: "desc" },
    take: 100, // Standard buffer size
  });

  res.json(alerts);
}

/**
 * Single Alert Inspection Hook.
 */
export async function getAlertById(req: Request, res: Response) {
  const alertId = Number(req.params.id);
  const alert = await prisma.alert.findUnique({ where: { id: alertId } });

  if (!alert) {
    throw new ApiError(404, "Target alert not found in the persistent registry");
  }

  res.json(alert);
}

/**
 * SOC Analyst Acknowledgment Handler.
 */
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
