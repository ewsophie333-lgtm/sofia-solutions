## 1. MENTALIDAD Y ROL
- **Identidad:** Arquitecto de Software Senior / Experto en Seguridad (ASIR).
- **Objetivo:** Preparación de defensa técnica, auditoría de vulnerabilidades y profesionalización de código.

## 2. LOG DE DECISIONES

### [2026-05-06] Auditoría y Profesionalización (ASIR Readiness)
- **Qué se hizo:**
  - **Refactorización Core**: Limpieza total de controladores (`auth`, `admin`, `tickets`, `alerts`, `services`, `payments`). Eliminación de lógica redundante y comentarios de IA.
  - **Documentación JSDoc**: Implementación de JSDoc técnico en todo el backend describiendo lógica de negocio e infraestructura.
  - **Optimización DB**: Configuración de Connection Pooling en Prisma y logs de rendimiento para consultas lentas.
  - **Auditoría de Seguridad**: Identificación de IDOR y XSS para demostración ante tribunal. Creación de manual de ataques manuales (`ATTACK-SCRIPTS.md`).
  - **Estrategia SSL**: Propuesta de Cloudflare Proxy para asegurar el dominio sin coste y mitigar ataques en el borde.
  - **Guion de Defensa**: Estructuración de exposición de 20 minutos con preguntas tipo examen resueltas.
- **Por qué:** Preparación para defensa de grado ASIR, transformando un prototipo en una plataforma SOC profesional y auditable.
### [2026-05-07] Docker & Codespaces Compatibility Fixes
- **Qué se hizo:**
  - **Estandarización de Line Endings**: Conversión de todos los archivos `.sh` a formato LF para evitar errores de ejecución en contenedores Linux desde Windows.
  - **Robustez del Túnel**: Actualización del servicio `tunnel` a una imagen especializada (`linuxserver/openssh-client`) para evitar fallos de instalación de dependencias en redes restringidas.
  - **Compatibilidad Multiplataforma**: Migración de scripts de `package.json` a `cross-env` para que funcionen tanto en Windows como en Linux (Docker/Codespaces).
  - **Mejora de Infraestructura**: Implementación de `healthchecks` en PostgreSQL y dependencias condicionales para asegurar un arranque ordenado del stack.
  - **Optimización de Codespaces**: Actualización de `.devcontainer/devcontainer.json` para usar una imagen base de Node.js robusta en lugar de intentar usar el contenedor de PHP como entorno de trabajo principal, evitando errores de comandos no encontrados (`npm`).
- **Por qué:** Resolver problemas de arranque en GitHub Codespaces donde el contenedor de desarrollo fallaba al no tener las herramientas necesarias para la inicialización.
- **Pendientes técnicos (Tech Debt):**
  - Considerar una alternativa a Serveo si los subdominios `sofia-solutions` están ocupados.
  - Implementar un modo "offline" real que desactive los servicios de túnel si no hay internet.

## 3. ESTADO DEL PROYECTO
- **Infraestructura:** Docker Compose (Vite + Node + Postgres + n8n) - **Optimizado para Codespaces**.
- **Backend:** Node/Express + Prisma ORM (Cross-platform ready).
- **Seguridad:** JWT Stateless + Roles (ADMIN/CLIENT) + SSL Ready (Cloudflare/Serveo).
- **Alertas:** Workflow n8n V16 (Master Identity) integrado con URLs de Serveo.
