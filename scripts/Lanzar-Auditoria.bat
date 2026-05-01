@echo off
title Lanzador de Sofia Exploit Kit
echo [!] Configurando entorno de auditoria...
powershell -ExecutionPolicy Bypass -File "%~dp0scripts\SOFIA-TOOL.ps1"
pause
