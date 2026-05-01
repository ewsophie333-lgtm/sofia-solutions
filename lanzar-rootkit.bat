@echo off
title Sofia Audit Kit - Proyecto Final ASIR 2026
color 0a

echo ========================================================
echo   SOFIA AUDIT KIT - PROYECTO FINAL ASIR 2026
echo ========================================================
echo.

echo Comprobando dependencias...
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [!] ERROR: Python no esta instalado o no esta en el PATH.
    echo Por favor instala Python 3 desde https://www.python.org/downloads/
    pause
    exit /b
)

echo Instalando libreria 'requests' (si no esta instalada)...
python -m pip install requests >nul 2>&1

echo.
echo Iniciando framework...
echo.

python sofia-audit-framework.py -t http://localhost:8000

echo.
pause
