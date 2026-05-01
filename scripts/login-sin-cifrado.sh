#!/bin/bash
# ============================================================
#  SOFIA SOLUTIONS — Demostración Login en Texto Plano (ASIR)
#  Muestra cómo el endpoint V1 (vulnerable) acepta contraseñas
#  almacenadas en texto plano, sin hash bcrypt.
#  Endpoint: POST /api/v1/auth/login
# ============================================================

API_BASE="${API_BASE:-http://localhost:8001}"
URL_VULN="$API_BASE/api/v1/auth/login"
URL_SAFE="$API_BASE/api/v2/auth/login"
EMAIL="aquila@sofia.local"
PASSWORD="S0f1a_Secur3!_2026"

echo "============================================="
echo "  SOFIA SOLUTIONS — Contraseña en Texto Plano"
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
echo "[*] Email:    $EMAIL"
echo "[*] Password: $PASSWORD (enviado en texto plano al backend)"
echo ""

# ---- FASE 1: Endpoint vulnerable (V1) ----
echo "--- FASE 1: Endpoint VULNERABLE (/api/v1/auth/login) ---"
RESPONSE=$(curl -s -w "\nHTTP_STATUS:%{http_code}" --max-time 5 -X POST "$URL_VULN" \
    -H "Content-Type: application/json" \
    -H "Origin: http://localhost:8000" \
    -d "{\"email\": \"$EMAIL\", \"password\": \"$PASSWORD\"}")

HTTP_STATUS=$(echo "$RESPONSE" | grep -o 'HTTP_STATUS:[0-9]*' | cut -d: -f2)
BODY=$(echo "$RESPONSE" | sed 's/HTTP_STATUS:[0-9]*//')

if [ "$HTTP_STATUS" = "200" ]; then
    TOKEN=$(echo "$BODY" | grep -oP '"accessToken":"\K[^"]+' 2>/dev/null || echo "")
    ROLE=$(echo "$BODY" | grep -oP '"role":"\K[^"]+' 2>/dev/null || echo "")
    MODE_VAL=$(echo "$BODY" | grep -oP '"mode":"\K[^"]+' 2>/dev/null || echo "")
    echo "[+] ¡LOGIN EXITOSO! — Contraseña en texto plano aceptada"
    [ -n "$TOKEN" ] && echo "[+] Token JWT: ${TOKEN:0:50}..."
    [ -n "$ROLE"  ] && echo "[+] Rol:       $ROLE"
    [ -n "$MODE_VAL" ] && echo "[+] Modo BD:   $MODE_VAL (vulnerable = sin hash)"
else
    echo "[-] Fallo — HTTP $HTTP_STATUS"
    echo "    Respuesta: $BODY"
fi

echo ""

# ---- FASE 2: Endpoint seguro (V2) para comparación ----
echo "--- FASE 2: Endpoint SEGURO (/api/v2/auth/login) ---"
echo "[*] El mismo email/password contra V2 (bcrypt + CSRF)..."
HTTP_SAFE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 -X POST "$URL_SAFE" \
    -H "Content-Type: application/json" \
    -H "Origin: http://localhost:8000" \
    -d "{\"email\": \"$EMAIL\", \"password\": \"$PASSWORD\"}")
echo "[✓] Resultado V2: HTTP $HTTP_SAFE (requiere token CSRF para completarse)"

echo ""
echo "============================================="
echo "  CONCLUSIÓN: V1 almacena contraseñas sin hash."
echo "  V2 usa bcrypt (12 rounds) + CSRF token."
echo "============================================="
