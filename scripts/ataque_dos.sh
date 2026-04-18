#!/bin/bash
# ============================================================
#  SOFIA SOLUTIONS — Ataque de Peticiones (DoS Demo, ASIR)
#  Objetivo: saturar el endpoint y ver rate limit activarse
# ============================================================

URL_VULN="http://localhost:8001/api/v1/auth/login"
URL_SAFE="http://localhost:8001/api/v2/auth/login"
PETICIONES="${1:-20}"
MODE="${2:-vulnerable}"

if [ "$MODE" = "secure" ]; then
    URL="$URL_SAFE"
    echo "=========================================="
    echo "  Ataque DoS → MODO SEGURO (Rate Limit)"
    echo "=========================================="
    echo "[!] El límite es 5 intentos/15min por IP."
else
    URL="$URL_VULN"
    echo "=========================================="
    echo "  Ataque DoS → MODO VULNERABLE (Sin límite)"
    echo "=========================================="
    echo "[!] La API no bloqueará — todas pasan."
fi

echo "[*] Enviando $PETICIONES peticiones a $URL"
echo ""

BLOQUEADAS=0
EXITOSAS=0

for i in $(seq 1 "$PETICIONES"); do
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" -X POST "$URL" \
        -H "Content-Type: application/json" \
        -d '{"email":"hacker@evil.com","password":"intento'$i'"}')
    
    if [ "$HTTP_CODE" = "429" ]; then
        BLOQUEADAS=$((BLOQUEADAS+1))
        echo "[#$i] ⛔ BLOQUEADO — HTTP 429 Rate Limit activo"
    elif [ "$HTTP_CODE" = "401" ] || [ "$HTTP_CODE" = "404" ]; then
        EXITOSAS=$((EXITOSAS+1))
        echo "[#$i] ✓ Petición admitida (credenciales incorrectas) — HTTP $HTTP_CODE"
    else
        echo "[#$i] HTTP $HTTP_CODE"
    fi
done

echo ""
echo "=========================================="
echo "  RESUMEN"
echo "  Admitidas: $EXITOSAS / $PETICIONES"
echo "  Bloqueadas por rate-limit: $BLOQUEADAS / $PETICIONES"
echo "=========================================="
