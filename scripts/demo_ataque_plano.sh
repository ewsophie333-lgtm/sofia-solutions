#!/bin/bash

# Este script demuestra el acceso exitoso pasando la contraseña en texto plano al backend V1.
# El API espera un JSON y devuelve un token JWT real gracias a la debilidad agregada en la BD.

URL="http://localhost:8001/api/v1/auth/login"
EMAIL="admin@sofia.local"
PASSWORD="S0f1a_Secur3!_2026"

echo "[*] Intentando login en entorno VULNERABLE"
echo "[*] Email: $EMAIL"
echo "[*] Password (Plano): $PASSWORD"
echo ""

RESPONSE=$(curl -s -w "\nHTTP_STATUS:%{http_code}" -X POST "$URL" \
    -H "Content-Type: application/json" \
    -d "{\"email\": \"$EMAIL\", \"password\": \"$PASSWORD\"}")

# Extraer cuerpo y status de la variable RESPONSE
HTTP_STATUS=$(echo "$RESPONSE" | tr -d '\n' | sed -e 's/.*HTTP_STATUS://')
BODY=$(echo "$RESPONSE" | sed -e 's/HTTP_STATUS\:.*//g')

if [ "$HTTP_STATUS" -eq 200 ]; then
    echo "[+] ¡LOGIN EXITOSO!"
    
    # Intento básico de mostrar el Access token usando grep si no tenemos jq instalado.
    TOKEN=$(echo "$BODY" | grep -oP '"accessToken":"\K[^"]+')
    ROLE=$(echo "$BODY" | grep -oP '"role":"\K[^"]+')
    
    # Formateo si se extrajo correctamente
    if [ ! -z "$TOKEN" ]; then
        echo "[+] Token: ${TOKEN:0:30}..."
    else
        echo "[+] Token incluido en respuesta."
    fi
    
    echo "[+] Rol: $ROLE"
    echo -e "\nPayload del Server:"
    echo "$BODY" | grep -oP '"mode":"\K[^"]+' | awk '{print "[+] Modo Ejecución: " $0}'
else
    echo "[-] Fallo en el login. Código HTTP: $HTTP_STATUS"
    echo "[-] Respuesta de la BD: $BODY"
fi
