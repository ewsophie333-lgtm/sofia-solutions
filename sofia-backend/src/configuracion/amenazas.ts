/**
 * SOFIA SOLUTIONS - Threat Intelligence Matrix
 * 
 * Centralized catalog of security amenazas, their severity levels,
 * and impact descriptions for the SOC and Audit Framework.
 */

export type ThreatSeverity = "BAJA" | "MEDIA" | "ALTA" | "CRÍTICA" | "URGENTE";

export interface ThreatDefinition {
  type: string;
  severity: ThreatSeverity;
  description: string;
  impact: string;
}

export const THREAT_MATRIX: Record<string, ThreatDefinition> = {
  SQLI_ATTEMPT: {
    type: "Inyección SQL",
    severity: "CRÍTICA",
    description: "Intento de manipulación de consultas a la base de datos.",
    impact: "Acceso no autorizado, exfiltración de credenciales y compromiso de integridad de datos."
  },
  PATH_TRAVERSAL: {
    type: "LFI / Salto de Directorio",
    severity: "CRÍTICA",
    description: "Intento de lectura de archivos sensibles del sistema operativo.",
    impact: "Exposición de configuraciones del servidor y llaves de cifrado."
  },
  BRUTE_FORCE: {
    type: "Fuerza Bruta",
    severity: "ALTA",
    description: "Múltiples fallos de autenticación detectados en un corto periodo.",
    impact: "Compromiso de cuentas de usuario y acceso administrativo no autorizado."
  },
  XSS_ATTEMPT: {
    type: "Scripting Entre Sitios (XSS)",
    severity: "ALTA",
    description: "Inyección de scripts maliciosos en parámetros de la aplicación.",
    impact: "Robo de cookies de sesión (Hijacking) y suplantación de identidad del cliente."
  },
  IDOR_ATTEMPT: {
    type: "Referencia Directa Insegura (IDOR)",
    severity: "ALTA",
    description: "Acceso a recursos o registros que no pertenecen al usuario autenticado.",
    impact: "Fuga de información confidencial de otros clientes y brecha de privacidad (BOLA)."
  },
  DOS_ATTEMPT: {
    type: "Denegación de Servicio (DoS)",
    severity: "MEDIA",
    description: "Volumen anómalo de peticiones desde una única fuente.",
    impact: "Agotamiento de recursos y degradación del servicio para usuarios legítimos."
  },
  SOS_ALERT: {
    type: "Alerta SOS Manual",
    severity: "CRÍTICA",
    description: "Señal de emergencia activada manualmente por el cliente.",
    impact: "Incidente físico o brecha de seguridad confirmada en las instalaciones del cliente."
  }
};

export function getThreatInfo(type: string): ThreatDefinition {
  return THREAT_MATRIX[type] || {
    type: "Amenaza No Catalogada",
    severity: "MEDIA",
    description: "Evento de seguridad no catalogado.",
    impact: "Impacto desconocido, requiere investigación manual."
  };
}
