import { logger } from "../config/logger";
import axios from "axios";

const N8N_WEBHOOK_URL = process.env.N8N_WEBHOOK_URL || "http://n8n:5678/webhook/alert";
const ALERT_EMAIL = "ewsophie333@gmail.com";

const socNotifications: string[] = [];

export async function notifySoc(message: string, type: string = "SECURITY_EVENT", severity: string = "HIGH") {
  socNotifications.unshift(message);
  if (socNotifications.length > 20) {
    socNotifications.pop();
  }

  logger.warn({ message: "soc_notification", detail: message });

  // Notificación externa a n8n -> Gmail
  try {
    console.log(`[SOC] Intentando enviar alerta a n8n: ${N8N_WEBHOOK_URL} | Sev: ${severity}`);
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
  } catch (error: any) {
    console.error(`[SOC ERROR] Fallo al enviar a n8n: ${error.message}`);
    if (error.response) {
      console.error(`[SOC ERROR DETAIL] Data: ${JSON.stringify(error.response.data)}`);
    }
  }
}

export function getSocNotifications() {
  return socNotifications;
}
