/**
 * ============================================================================
 * SOFIA SOLUTIONS - MI SISTEMA DE NOTIFICACIONES SOC
 * ============================================================================
 * 
 * Este servicio lo uso para avisarme cada vez que ocurre algo importante en el 
 * sistema. Si detecto un ataque crítico, mando un email a través de n8n para
 * tenerlo controlado al momento.
 * 
 * Sofia Gomez
 * @copyright 2026
 * ============================================================================
 */
import { registro } from "../configuracion/registro";
import axios from "axios";
import { metrics } from "../configuracion/prometheus";

const N8N_WEBHOOK_URL = process.env.N8N_WEBHOOK_URL || "http://n8n:5678/webhook/alert";
const ALERT_EMAIL = "ewsophie333@gmail.com";

// Solo mando email para estas severidades, si no me colapsarían el correo en las pruebas
const EMAIL_SEVERITIES = ["CRÍTICA", "ALTA", "URGENTE"];

const socNotifications: string[] = [];

/**
 * Función principal para notificar al SOC.
 * Guardo la notificación en una lista local y, si es grave, la mando fuera.
 */
export async function notifySoc(message: string, type: string = "SECURITY_EVENT", severity: string = "ALTA", sourceIp: string = "unknown") {
  socNotifications.unshift(message);
  if (socNotifications.length > 20) {
    socNotifications.pop();
  }

  registro.warn({ message: "soc_notification", detail: message, type, severity });

  // Aquí decido si mando email o si me vale con el log local
  if (!EMAIL_SEVERITIES.includes(severity.toUpperCase())) {
    console.log(`[SOC] Alerta ${type} (${severity}) registrada localmente. No hace falta mandar email.`);
    return;
  }

  // Notificación externa: mando el webhook a mi n8n para que me llegue al Gmail
  try {
    console.log(`[SOC] Mandando alerta ${severity} a n8n: ${N8N_WEBHOOK_URL} (IP: ${sourceIp})`);
    const response = await axios.post(N8N_WEBHOOK_URL, {
      title: `ALERTA SOC [${severity}]: ${type}`,
      description: message,
      severity: severity.toLowerCase(),
      timestamp: new Date().toLocaleString("es-ES"),
      recipientEmail: ALERT_EMAIL,
      userName: "Escudo-WAF-Sofia",
      clientName: "Infraestructura Interna",
      sourceIp: sourceIp 
    });
    console.log(`[SOC ÉXITO] n8n ha respondido bien: ${response.status}`);
    metrics.alertsSentTotal.inc({ destination: "GMAIL/N8N", type, severity });
  } catch (error: any) {
    console.error(`[SOC ERROR] Algo ha fallado al avisar a n8n: ${error.message}`);
  }
}

/**
 * Función para sacar las últimas notificaciones y pintarlas en mi panel.
 */
export function getSocNotifications() {
  return socNotifications;
}
