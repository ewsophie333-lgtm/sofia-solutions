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
  const { title, description, severity = "CRÍTICA", sourceIp } = req.body;
  const clientIp = sourceIp || req.ip || req.socket.remoteAddress || "0.0.0.0";

  if (!title || !description) {
    throw new ApiError(400, "Incomplete payload: title and description are mandatory");
  }

  const alert = await prisma.alert.create({
    data: {
      userId: req.user?.id,
      title,
      description,
      severity: severity.toUpperCase(),
      status: "ACTIVE",
      notifiedAt: new Date(),
    },
  });

  // Notificación externa (SOS / n8n)
  const isUrgent = ["URGENTE", "CRÍTICA", "ALTA", "CRITICAL", "URGENT", "HIGH"].includes(alert.severity);
  
  if (isUrgent) {
    console.log(`[SOS] Iniciando envío de alerta crítica ${alert.id} a n8n...`);
    
    // Obtenemos datos del cliente para n8n
    const userWithCustomer = req.user?.id ? await prisma.user.findUnique({
      where: { id: req.user.id },
      include: { customer: true }
    }) : null;

    axios.post(N8N_WEBHOOK_URL, {
      alertId: alert.id,
      title: alert.title,
      description: alert.description,
      severity: alert.severity,
      sourceIp: clientIp, // Campo solicitado para n8n
      timestamp: alert.notifiedAt,
      recipientEmail: ALERT_EMAIL,
      userName: req.user?.email || "User-Demo",
      clientName: userWithCustomer?.customer?.name || "Cliente Sofia Solutions"
    }).then(() => {
      console.log(`[SOS SUCCESS] Alerta ${alert.id} entregada correctamente a n8n.`);
    }).catch((err) => {
      console.error(`[SOS FAILURE] Alerta ${alert.id} no pudo entregarse a n8n: ${err.message}`);
    });
  }

  res.status(201).json({
    ...alert,
    webhookSent: isUrgent,
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
