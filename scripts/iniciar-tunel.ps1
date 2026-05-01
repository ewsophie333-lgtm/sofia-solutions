param(
    [int]$Port = 8000
)

Write-Host "== Sofia Solutions Tunnel Controller ==" -ForegroundColor Cyan
Write-Host "Iniciando túnel de acceso unificado (Cloudflare)..." -ForegroundColor Gray

# Comprobar si cloudflared está instalado
if (!(Get-Command cloudflared -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: No se encuentra 'cloudflared' instalado en el sistema." -ForegroundColor Red
    Write-Host "Recomendación: Usa el túnel integrado en Docker:" -ForegroundColor Green
    Write-Host "  docker compose logs tunnel | Select-String 'trycloudflare.com'"
    Write-Host ""
    Write-Host "Si prefieres ejecutarlo manualmente, descarga cloudflared de:"
    Write-Host "https://github.com/cloudflare/cloudflared/releases"
    exit
}

Write-Host "Generando URL pública..." -ForegroundColor Yellow
Write-Host "Nota: Esta URL servirá para Frontend, API y Grafana." -ForegroundColor Gray
cloudflared tunnel --url "http://localhost:$Port"
