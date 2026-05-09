@echo off
echo.
echo [SOFIA SOLUTIONS] Iniciando Visualizador de Base de Datos (Prisma Studio)...
echo.
docker exec -it sofia-solutions-backend-1 npx prisma studio --port 5555 --browser none
echo.
echo [OK] El visualizador esta activo en: http://localhost:5555
echo Mantén esta ventana abierta durante la demostración.
pause
