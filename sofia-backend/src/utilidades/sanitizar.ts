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
const suspiciousPatterns = [
  { type: "SQLI_ATTEMPT", regex: /('|--|;|\bunion\b|\bselect\b|\bdrop\b|\bor\s+1=1)/i },
  { type: "XSS_ATTEMPT", regex: /(<script|javascript:|onerror=|onload=|<img)/i },
  { type: "PATH_TRAVERSAL", regex: /(\.\.\/|%2e%2e%2f|%2fetc%2fpasswd)/i }
];

export function detectAttackPatterns(payload: unknown) {
  const serialized = JSON.stringify(payload ?? {});
  console.log(`[DEBUG] Analyzing payload: ${serialized}`);
  return suspiciousPatterns.find((pattern) => pattern.regex.test(serialized));
}
