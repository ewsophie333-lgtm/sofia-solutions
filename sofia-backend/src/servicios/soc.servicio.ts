/**
 * ============================================================================
 * SOFIA SOLUTIONS - SECURITY & MONITORING PLATFORM
 * ============================================================================
 * 
 * Este archivo forma parte de la arquitectura base del backend de Sofia Solutions.
 * Ha sido disenado siguiendo principios de codigo limpio, seguridad por diseno,
 * y alta escalabilidad para entornos criticos e industriales.
 * 
 * @module SofiaSolutions
 * @author Sofia Solutions Architecture Team
 * @copyright 2026
 * ============================================================================
 */
import { registro } from "../configuracion/registro";
import axios from "axios";
import { metrics } from "../configuracion/prometheus";

const N8N_WEBHOOK_URL = process.env.N8N_WEBHOOK_URL || "http://n8n:5678/webhook/alert";
const ALERT_EMAIL = "ewsophie333@gmail.com";

// Severidades que disparan notificación externa por email (n8n → Gmail)
const EMAIL_SEVERITIES = ["CRITICAL", "HIGH", "URGENT"];

const socNotifications: string[] = [];

export async function notifySoc(message: string, type: string = "SECURITY_EVENT", severity: string = "HIGH") {
  socNotifications.unshift(message);
  if (socNotifications.length > 20) {
    socNotifications.pop();
  }

  registro.warn({ message: "soc_notification", detail: message, type, severity });

  // Solo entornoiar email para severidades de impacto alto
  if (!EMAIL_SEVERITIES.includes(severity.toUpperCase())) {
    console.log(`[SOC] Alerta ${type} (${severity}) registrada localmente. No se entornoía email.`);
    return;
  }

  // Notificación externa a n8n -> Gmail
  try {
    console.log(`[SOC] Enviando alerta ${severity} a n8n: ${N8N_WEBHOOK_URL}`);
    const response = await axios.post(N8N_WEBHOOK_URL, {
      title: `ALERTA SOC [${severity}]: ${type}`,
      description: message,
      severity,
      timestamp: new Date(),
      recipientEmail: ALERT_EMAIL,
      userName: "Sofia-WAF-Shield",
      clientName: "Internal Infrastructure"
    });
    console.log(`[SOC SUCCESS] n8n respondió: ${response.status} ${response.statusText}`);
    metrics.alertsSentTotal.inc({ destination: "GMAIL/N8N", type, severity });
  } catch (error: any) {
    console.error(`[SOC ERROR] Fallo al entornoiar a n8n: ${error.message}`);
    if (error.response) {
      console.error(`[SOC ERROR DETAIL] Data: ${JSON.stringify(error.response.data)}`);
    }
  }
}

export function getSocNotifications() {
  return socNotifications;
}
