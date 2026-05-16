/**
 * ============================================================================
 * SOFIA SOLUTIONS - MI SISTEMA DE DETECCIÓN DE PATRONES MALICIOSOS
 * ============================================================================
 * 
 * He creado este pequeño motor de búsqueda de patrones sospechosos para pillar
 * los ataques más comunes (SQLi, XSS, etc.) antes de que toquen mi base de datos.
 * 
 * Sofia Gomez
 * @copyright 2026
 * ============================================================================
 */

// Aquí defino las "firmas" de los ataques que quiero detectar.
const suspiciousPatterns = [
  // Intento de Inyección SQL: Busco comillas, guiones de comentario o palabras clave de SQL.
  { type: "SQLI_ATTEMPT", regex: /('|--|;|\bunion\b|\bselect\b|\bdrop\b|\bor\s+1=1)/i },
  // Intento de XSS: Busco etiquetas script o manejadores de eventos de JS.
  { type: "XSS_ATTEMPT", regex: /(<script|javascript:|onerror=|onload=|<img)/i },
  // Path Traversal: Busco saltos de directorio para que no me lean archivos del sistema.
  { type: "PATH_TRAVERSAL", regex: /(\.\.\/|%2e%2e%2f|%2fetc%2fpasswd)/i }
];

/**
 * Esta función analiza cualquier cosa que le mandemos (body, query, etc.)
 * y nos dice si ha encontrado algo raro basándose en mis patrones de arriba.
 */
export function detectAttackPatterns(payload: unknown) {
  const serialized = JSON.stringify(payload ?? {});
  // Lo imprimo por consola para poder debugear si algo falla durante la presentación
  console.log(`[DEBUG] Analizando datos entrantes: ${serialized}`);
  return suspiciousPatterns.find((pattern) => pattern.regex.test(serialized));
}
