param(
  [ValidateSet("secure", "vulnerable")]
  [string]$Mode = "secure",
  [switch]$Rebuild
)

$ErrorActionPreference = "Stop"

Write-Host "== Sofia Solutions stack =="
Write-Host "Mode: $Mode"

$env:APP_MODE = $Mode

if ($Rebuild) {
  docker compose down
  docker compose up -d --build
} else {
  docker compose up -d
}

Write-Host ""
Write-Host "Accesos:"
Write-Host "  Web:      http://localhost:8000"
Write-Host "  API:      http://localhost:8001"
Write-Host "  Swagger:  http://localhost:8001/docs"
Write-Host "  Grafana:  http://localhost:3000"
Write-Host ""
Write-Host "Credenciales Grafana:"
Write-Host "  usuario: admin"
Write-Host "  clave:   admin"

