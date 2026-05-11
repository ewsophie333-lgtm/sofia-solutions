@echo off
title SOFIA SOLUTIONS - ACTIVAR MODO VULNERABLE
echo [!] Cambiando postura de seguridad a: VULNERABLE...
set APP_MODE=vulnerable
docker compose up -d backend
echo [OK] El sistema ahora es VULNERABLE para la demostracion.
pause
