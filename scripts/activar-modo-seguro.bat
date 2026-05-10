@echo off
title SOFIA SOLUTIONS - ACTIVAR MODO SEGURO
echo [!] Cambiando postura de seguridad a: SEGURO...
echo.

:: Establecer la variable para docker compose
set APP_MODE=secure

:: Detener y reconstruir solo el backend con la nueva variable
docker compose down backend
docker compose up -d --build backend

echo.
echo [OK] El sistema ahora es SEGURO (IMPENETRABLE).
echo     - Contraseñas con bcrypt (12 rounds)
echo     - Rate Limiting activo (10 intentos / 15 min)
echo     - WAF Shield bloqueando SQLi, XSS, Path Traversal
echo.
pause
