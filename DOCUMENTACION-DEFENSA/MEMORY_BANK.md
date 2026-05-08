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
### [2026-05-08] Grafana & Prometheus Infrastructure Fix
- **Qué se hizo:**
  - **Corrección de Rutas**: Se actualizó `docker-compose.yml` para apuntar a `./sofia-backend/docker/prometheus.yml`, ya que el archivo no existía en la raíz.
  - **Configuración de Proxy**: Se habilitó `GF_SERVER_SERVE_FROM_SUB_PATH` y `GF_SERVER_ROOT_URL` en Grafana para que funcione correctamente detrás del proxy Apache (`/grafana`).
  - **Provisionamiento Automático**: Se mapearon los volúmenes de `provisioning` y `dashboards` para que Grafana cargue los paneles SOC de forma nativa sin configuración manual.
  - **Fix de Telemetría**: Se añadió la clave `alertDistribution` en `admin.controller.ts` que faltaba para alimentar el gráfico de dona en el panel administrativo.
  - **Restauración de Vulnerabilidad**: Se corrigió el archivo `auth.routes.ts` donde la ruta `/login/vulnerable` estaba erróneamente apuntando al controlador seguro, impidiendo la demostración de SQL Injection.
  - **Unificación de Telemetría**: Se actualizó `admin.controller.ts` para sumar tanto `incidents` como `alerts` en el gráfico de distribución, asegurando que ataques automáticos (Fuerza Bruta) aparezcan en el dashboard.
  - **Arreglo General de Auditoría**: Se sincronizaron los endpoints en `audit.php` para apuntar específicamente a `/login/vulnerable` y `/login/v2`. Esto corrige el fallo donde los ataques v1 eran bloqueados por las rutas seguras por defecto.
- **Por qué:** Garantizar que el 100% de las demostraciones de vulnerabilidades (SQLi, Brute Force, Bypass) funcionen para la presentación.
- **Pendientes técnicos (Tech Debt):**
  - Considerar una alternativa a Serveo si los subdominios `sofia-solutions` están ocupados.
  - Implementar un modo "offline" real que desactive los servicios de túnel si no hay internet.
  - Verificar la persistencia de datos de Grafana (añadir volumen para `/var/lib/grafana` si es necesario).

## 3. ESTADO DEL PROYECTO
- **Infraestructura:** Docker Compose (Vite + Node + Postgres + n8n) - **Optimizado para Codespaces**.
- **Backend:** Node/Express + Prisma ORM (Cross-platform ready).
- **Seguridad:** JWT Stateless + Roles (ADMIN/CLIENT) + SSL Ready (Cloudflare/Serveo).
- **Alertas:** Workflow n8n V16 (Master Identity) integrado con URLs de Serveo.
