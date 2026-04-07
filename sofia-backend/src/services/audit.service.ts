import { prisma } from "../config/prisma";
import { logger } from "../config/logger";

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
  logger.warn({ message: "security_event", ...input });

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
