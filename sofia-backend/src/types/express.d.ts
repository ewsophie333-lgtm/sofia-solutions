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
 * Sofia Gomez
 * @copyright 2026
 * ============================================================================
 */
declare global {
  namespace Express {
    interface Request {
      modoDemo?: "secure" | "vulnerable";
      user?: {
        id: number;
        email: string;
        role: "ADMIN" | "CLIENT";
      };
      requestId?: string;
    }
  }
}

export {};
