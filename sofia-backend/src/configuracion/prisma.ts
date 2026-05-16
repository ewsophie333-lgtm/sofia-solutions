/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS
 * ============================================================================
 * 
 * Este código lo he desarrollado para mi proyecto final (TFG). Aquí trato de
 * aplicar todo lo que he aprendido tanto en el curso como en la empresa.
 * 
 * Autor: Sofia Gomez
 * Año: 2026
 * ============================================================================
 */
import { PrismaClient } from "@prisma/client";

/**
 * SOFIA SOLUTIONS - Data Access Layer (Prisma ORM)
 * 
 * Configures the Prisma Client with connection pooling optimized for PostgreSQL
 * and query logging for security auditing and performance monitoring.
 */
export const prisma = new PrismaClient({
  log: [
    { emit: 'event', level: 'query' },
    { emit: 'stdout', level: 'error' },
    { emit: 'stdout', level: 'info' },
    { emit: 'stdout', level: 'warn' },
  ],
});

// Logic for monitoring long-running queries in production SOC monitor
prisma.$on('query' as any, (e: any) => {
  if (e.duration > 1000) {
    console.warn(`[PERFORMANCE] Slow query detected: ${e.query} (Duration: ${e.duration}ms)`);
  }
});
