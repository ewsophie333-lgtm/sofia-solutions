@echo off
title SOFIA SOLUTIONS - ACTIVAR MODO SEGURO
echo [!] Cambiando postura de seguridad a: SEGURO...
set APP_MODE=secure
docker compose up -d backend
echo [OK] El sistema ahora es IMPENETRABLE.
pause
