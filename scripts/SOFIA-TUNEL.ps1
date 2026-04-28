param(
    [int]$Port = 8000
)

Write-Host "== Sofia Solutions Tunnel Controller ==" -ForegroundColor Cyan
Write-Host "Iniciando túnel de acceso seguro (Cloudflare)..."

# Comprobar si cloudflared está instalado
if (!(Get-Command cloudflared -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: No se encuentra 'cloudflared' instalado." -ForegroundColor Red
    Write-Host "Para instalarlo:" -ForegroundColor Yellow
    Write-Host "  1. Descarga el binario de https://github.com/cloudflare/cloudflared/releases"
    Write-Host "  2. Añádelo al PATH de Windows."
    Write-Host ""
    Write-Host "Alternativa con Docker (Recomendado):" -ForegroundColor Green
    Write-Host "  El túnel ya se inicia solo al hacer 'docker compose up -d'"
    exit
}

Write-Host "Conectando con Cloudflare..." -ForegroundColor Gray
cloudflared tunnel --url "http://localhost:$Port"
