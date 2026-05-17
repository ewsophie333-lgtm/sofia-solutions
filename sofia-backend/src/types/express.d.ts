/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS
 * ============================================================================
 * 
 * Este código lo he desarrollado para mi proyecto final de ASIR. Aquí trato de
 * aplicar todo lo que he aprendido tanto en el curso como en la empresa.
 * 
 * Autor: Sofia Gomez
 * Año: 2026
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
