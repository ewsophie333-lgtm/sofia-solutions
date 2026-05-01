param(
  [ValidateSet("secure", "vulnerable")]
  [string]$Mode = "secure",
  [switch]$Rebuild
)

$ErrorActionPreference = "Stop"

# Define el modo de demostración del entorno completo.
# secure: controles activos
# vulnerable: fallos didácticos habilitados
Write-Host "== Sofia Solutions stack =="
Write-Host "Mode: $Mode"

$env:APP_MODE = $Mode

if ($Rebuild) {
  # Baja y reconstruye todos los servicios para asegurar consistencia.
  docker compose down
  docker compose up -d --build
} else {
  # Arranque rápido reutilizando imágenes previas.
  docker compose up -d
}

Write-Host ""
Write-Host "Accesos Locales:"
Write-Host "  Web:      http://localhost:8000"
Write-Host "  API:      http://localhost:8001"
Write-Host "  Grafana:  http://localhost:3000 (admin/admin)"
Write-Host "  Prisma:   http://localhost:5556"

Write-Host ""
Write-Host "Acceso Público (Cloudflare Tunnel):" -ForegroundColor Cyan
Write-Host "  El túnel se inicia automáticamente."
Write-Host "  Para ver tu URL pública, ejecuta este comando:" -ForegroundColor Yellow
Write-Host "  docker compose logs tunnel | Select-String 'trycloudflare.com'"
Write-Host ""
Write-Host "  Nota: Todo (Web, API y Grafana) funciona bajo esa única URL."


