/**
 * ============================================================================
 * SOFIA SOLUTIONS - MI SERVICIO DE AUDITORÍA Y LOGS
 * ============================================================================
 * 
 * Este archivo es fundamental porque aquí es donde guardo absolutamente todo
 * lo que pasa en el sistema a nivel de seguridad. Estos datos son los que
 * luego consulto desde Grafana para ver los ataques en el mapa.
 * 
 * Sofia Gomez
 * @copyright 2026
 * ============================================================================
 */
import { prisma } from "../configuracion/prisma";
import { registro } from "../configuracion/registro";

// Definición de qué datos guardo para cada evento de seguridad
type SecurityEventInput = {
  type: string;
  severity: string;
  sourceIp: string;
  endpoint: string;
  payload: unknown;
  action: string;
  metadata?: Record<string, unknown>;
};

/**
 * Esta función guarda el evento tanto en los logs del servidor (consola/archivo)
 * como en la base de datos de PostgreSQL usando Prisma.
 */
export async function logSecurityEvent(input: SecurityEventInput) {
  // Primero mando un aviso al registro del sistema
  registro.warn({ message: "security_event", ...input });

  // Y luego lo guardo en la base de datos para que la telemetría sea persistente
  return prisma.securityEvent.create({
    data: {
      type: input.type,
      severity: input.severity,
      sourceIp: input.sourceIp,
      endpoint: input.endpoint,
      payload: JSON.stringify(input.payload),
      action: input.action,
      metadata: JSON.stringify(input.metadata ?? {})
    }
  });
}
