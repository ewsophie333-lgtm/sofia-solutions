#!/bin/bash
# ============================================================
#  SOFIA SOLUTIONS — Ataque DoS / Saturación (Académico ASIR)
#  Uso: bash sofia-dos.sh [N_peticiones] [vulnerable|secure]
#  Endpoints:
#    Vulnerable → POST /api/v1/auth/login  (sin rate-limit)
#    Seguro     → POST /api/v2/auth/login  (rate-limit 10/15min)
# ============================================================

API_BASE="${API_BASE:-http://localhost:8001}"
URL_VULN="$API_BASE/api/v1/auth/login"
URL_SAFE="$API_BASE/api/v2/auth/login"
PETICIONES="${1:-20}"
MODE="${2:-vulnerable}"

# ---- Verificar disponibilidad ----
echo "============================================="
echo "  SOFIA SOLUTIONS — Simulación DoS (ASIR)"
echo "============================================="
echo "[*] Verificando conectividad con $API_BASE..."
HTTP_CHECK=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 "$API_BASE/health" 2>/dev/null)
if [ "$HTTP_CHECK" != "200" ]; then
    echo "[✗] No se puede conectar con la API en $API_BASE"
    echo "    Asegúrate de que el backend está corriendo: docker ps"
    exit 1
fi
echo "[✓] API disponible — HTTP $HTTP_CHECK"
echo ""

if [ "$MODE" = "secure" ]; then
    URL="$URL_SAFE"
    echo "  Modo → SEGURO (Rate Limit activo)"
    echo "  Límite: 10 intentos / 15 minutos por IP"
else
    URL="$URL_VULN"
    echo "  Modo → VULNERABLE (sin límite de peticiones)"
fi

echo "[*] Enviando $PETICIONES peticiones a $URL"
echo ""

BLOQUEADAS=0
EXITOSAS=0

for i in $(seq 1 "$PETICIONES"); do
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 -X POST "$URL" \
        -H "Content-Type: application/json" \
        -H "Origin: http://localhost:8000" \
        -d "{\"email\":\"atacante@evil.com\",\"password\":\"intento$i\"}")

    if [ "$HTTP_CODE" = "429" ]; then
        BLOQUEADAS=$((BLOQUEADAS+1))
        echo "[#$i] ⛔ BLOQUEADO — HTTP 429 Rate Limit activo"
    elif [ "$HTTP_CODE" = "401" ] || [ "$HTTP_CODE" = "400" ]; then
        EXITOSAS=$((EXITOSAS+1))
        echo "[#$i] ✓ Petición admitida (credenciales incorrectas) — HTTP $HTTP_CODE"
    elif [ "$HTTP_CODE" = "403" ]; then
        BLOQUEADAS=$((BLOQUEADAS+1))
        echo "[#$i] ⛔ BLOQUEADO — HTTP 403 Forbidden (CSRF / Captcha)"
    else
        echo "[#$i] HTTP $HTTP_CODE"
    fi
done

echo ""
echo "============================================="
echo "  RESUMEN"
echo "  Admitidas:           $EXITOSAS / $PETICIONES"
echo "  Bloqueadas (límite): $BLOQUEADAS / $PETICIONES"
echo "============================================="
