/**
 * SOFIA SOLUTIONS - Controlador de Alertas de Seguridad y Notificaciones
 * 
 * Gestiona la generación, ciclo de vida y enrutamiento externo de las alertas de seguridad.
 * Se integra con el motor de flujos de trabajo n8n para notificaciones críticas en tiempo real.
 */

import type { Request, Response } from "express";
import { prisma } from "../configuracion/prisma";
import { ApiError } from "../utilidades/errores";
import { metrics } from "../configuracion/prometheus";
import axios from "axios";

const N8N_WEBHOOK_URL = process.env.N8N_WEBHOOK_URL || "http://n8n:5678/webhook/alert";
const ALERT_EMAIL = "ewsophie333@gmail.com";

/**
 * Motor de Creación de Alertas de Alta Prioridad.
 * Desencadena flujos de trabajo de notificación externa para severidades URGENT/CRITICAL.
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

  // Enrutamiento de Notificaciones Críticas (Integración con Flujo de Trabajo Externo)
  if (["URGENT", "CRITICAL"].includes(severity.toUpperCase())) {
    try {
      // Obtener información del cliente del usuario para incluirla en la alerta
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

      // Registrar métrica para el dashboard de Grafana
      metrics.alertsSentTotal.inc({ 
        destination: "GMAIL/N8N", 
        type: alert.title.includes("SQL") ? "SQL_INJECTION" : "GENERAL_ALERT",
        severity: alert.severity 
      });
    } catch (error: any) {
      console.error(`[ALERTA ERROR] Error al entornoiar alerta ${alert.id} a n8n (${N8N_WEBHOOK_URL}): ${error.message}`);
      if (error.response) {
        console.error(`[ALERTA ERROR] Respuesta de n8n: ${error.response.status} - ${JSON.stringify(error.response.data)}`);
      }
    }
  }

  res.status(201).json({
    ...alert,
    webhookSent: ["URGENT", "CRITICAL"].includes(severity.toUpperCase()),
  });
}

/**
 * Recupera el registro histórico de alertas de seguridad.
 */
export async function listAlerts(req: Request, res: Response) {
  const alerts = await prisma.alert.findMany({
    where: req.user?.role === "ADMIN" ? {} : { userId: req.user?.id },
    orderBy: { notifiedAt: "desc" },
    take: 100, // Tamaño de buffer estándar
  });

  res.json(alerts);
}

/**
 * Hook para la inspección de una alerta individual.
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
