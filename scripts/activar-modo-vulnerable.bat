@echo off
title SOFIA SOLUTIONS - ACTIVAR MODO VULNERABLE
echo [!] Cambiando postura de seguridad a: VULNERABLE...
echo.

:: Establecer la variable para docker compose
set APP_MODE=vulnerable

:: Detener y reconstruir solo el backend con la nueva variable
docker compose down backend
docker compose up -d --build backend

echo.
echo [OK] El sistema ahora es VULNERABLE para la demostracion.
echo     - Contraseñas en texto plano (admin123, mapfre123, etc.)
echo     - Sin Rate Limiting
echo     - SQL Injection habilitado en /api/v1/auth/login
echo.
pause
