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
import { prisma } from "../configuracion/prisma";
import { registro } from "../configuracion/registro";

type SecurityEventInput = {
  type: string;
  severity: string;
  sourceIp: string;
  endpoint: string;
  payload: unknown;
  action: string;
  metadata?: Record<string, unknown>;
};

export async function logSecurityEvent(input: SecurityEventInput) {
  registro.warn({ message: "security_event", ...input });

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
