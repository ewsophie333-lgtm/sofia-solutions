const suspiciousPatterns = [
  { type: "SQLI_ATTEMPT", regex: /('|--|;|\bunion\b|\bselect\b|\bdrop\b|\bor\s+1=1)/i },
  { type: "XSS_ATTEMPT", regex: /(<script|javascript:|onerror=|onload=|<img)/i },
  { type: "PATH_TRAVERSAL", regex: /(\.\.\/|%2e%2e%2f|%2fetc%2fpasswd)/i }
];

export function detectAttackPatterns(payload: unknown) {
  const serialized = JSON.stringify(payload ?? {});
  return suspiciousPatterns.find((pattern) => pattern.regex.test(serialized));
}
