param(
  [ValidateSet("vulnerable", "secure")]
  [string]$Mode = "vulnerable"
)

$ErrorActionPreference = "Stop"

# Lanza la batería de ataques académicos contra el modo indicado.
# Se usa en defensa para comparar qué payloads pasan en vulnerable
# y cuáles quedan bloqueados en secure.
Write-Host "== Ejecutando ataques en modo $Mode =="

if ($Mode -eq "vulnerable") {
  # SQLi de demostración sobre el login v1.
  npm run attack:sqli:vuln
  # XSS de demostración sobre el login v1.
  npm run attack:xss:vuln
  # Path traversal contra rutas tolerantes en modo vulnerable.
  npm run attack:traversal:vuln
  # Manipulación del checkout y del amount enviado por cliente.
  npm run attack:payment:vuln
  # Fuerza bruta sin limitación efectiva.
  npm run attack:bruteforce:vuln
  # Matriz final para correlacionar ataques con servicios.
  npm run services:matrix:vuln
} else {
  # Repite la misma batería contra el modo seguro para verificar bloqueo.
  npm run attack:sqli:secure
  npm run attack:xss:secure
  npm run attack:traversal:secure
  npm run attack:payment:secure
  npm run attack:bruteforce:secure
  npm run services:matrix:secure
}
