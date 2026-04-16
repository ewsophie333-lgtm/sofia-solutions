# Sofia Backend

Backend académico de `Sofia Solutions`, orientado a un proyecto de ASIX centrado en:

- seguridad ofensiva y defensiva;
- despliegue de servicios;
- scripting y automatización;
- contenedorización con Docker;
- bases de datos relacionales;
- visualización de eventos e incidentes.

El backend da servicio al frontend corporativo publicado en `http://localhost:8000` y expone una API REST en `http://localhost:8001`.

## Rol dentro del proyecto

Este backend no se limita a autenticar usuarios o devolver datos de catálogo. Su función es sostener una plataforma completa con:

- login vulnerable y login seguro;
- catálogo de servicios con lógica operativa;
- datos de clientes, activos e incidentes;
- tickets y pagos simulados;
- eventos de seguridad;
- métricas internas para Grafana;
- panel SOC con datos coherentes para la defensa del proyecto.

## Enfoque ASIX

El valor del backend para ASIX está en que permite demostrar competencias de:

- administración de servicios en red;
- exposición y securización de APIs;
- diseño y explotación de bases de datos;
- despliegue y orquestación con Docker Compose;
- automatización en Windows y Linux;
- monitorización y observabilidad;
- análisis comparativo entre configuraciones inseguras y endurecidas.

## Stack técnico

- Node.js 20+
- Express + TypeScript
- Prisma ORM
- PostgreSQL 15
- JWT + bcryptjs / MD5 académico
- Zod
- Winston
- Swagger/OpenAPI
- Prometheus como fuente interna de métricas
- Grafana como capa visible de visualización técnica

## Servicios y puertos

- frontend corporativo: `http://localhost:8000`
- backend API: `http://localhost:8001`
- Swagger: `http://localhost:8001/docs`
- healthcheck: `http://localhost:8001/health`
- métricas internas: `http://localhost:8001/metrics`
- PostgreSQL: `5432`
- Grafana: `http://localhost:3000`

Prometheus sigue existiendo como recolector interno para Grafana, pero ya no forma parte de la capa visible principal del proyecto.

## Endpoints principales

Autenticación vulnerable:

- `POST /api/v1/auth/login`
- `POST /api/v1/auth/register`
- `POST /api/v1/auth/refresh`
- `POST /api/v1/auth/logout`

Autenticación segura:

- `GET /api/v2/auth/csrf`
- `POST /api/v2/auth/login`
- `POST /api/v2/auth/register`
- `POST /api/v2/auth/refresh`
- `POST /api/v2/auth/logout`

Servicios y negocio:

- `GET /api/services`
- `GET /api/services/catalog`
- `GET /api/services/effectiveness`
- `GET /api/services/:id`
- `POST /api/services`

Monitorización y administración:

- `GET /api/admin/overview`
- `GET /api/admin/security-monitor`
- `GET /api/admin/security-events`

Otros módulos:

- `POST /api/payments/checkout`
- `GET /api/payments/history`
- `GET /api/tickets`
- `POST /api/tickets`
- `GET /api/tickets/:id/messages`
- `POST /api/tickets/:id/messages`
- `GET /metrics`

## Modos de funcionamiento

El proyecto puede trabajar en dos modos:

- `APP_MODE=vulnerable`
- `APP_MODE=secure`

La comparación entre ambos no se basa en dos aplicaciones distintas, sino en dos comportamientos diferenciados sobre el mismo dominio funcional. Eso permite defender mejor el proyecto: la lógica de negocio es la misma, pero cambia la forma de protegerla.

### Modo vulnerable

Se usa con finalidad didáctica para mostrar fallos como:

- ausencia de rate limiting efectivo;
- hashing débil o comportamiento de autenticación laxo;
- validaciones insuficientes;
- detección pasiva de ataques;
- posibilidad de abuso por SQLi, XSS o fuerza bruta en escenarios de demostración.

### Modo seguro

Aplica controles más realistas:

- rate limit;
- validación estricta;
- bloqueo de payloads maliciosos;
- bcrypt;
- cookies más seguras;
- eventos de seguridad persistidos;
- métricas y trazabilidad.

## Arquitectura funcional

El backend se organiza para reflejar una arquitectura profesional y mantenible:

- `config/`: variables de entorno, base de datos, Prometheus
- `controllers/`: lógica de negocio
- `routes/`: exposición de endpoints
- `middleware/`: autenticación, detección de ataques, rate limiting, logging
- `services/`: lógica de apoyo y agregación
- `utils/`: helpers y utilidades
- `prisma/`: modelo relacional, seeds y migraciones
- `tests/`: scripts de ataque y validación

## Base de datos y dominio

Las entidades principales del modelo son:

- `User`
- `Service`
- `Customer`
- `Asset`
- `Incident`
- `Ticket`
- `TicketMessage`
- `Payment`
- `SecurityEvent`

Esta estructura permite que el catálogo de servicios tenga sentido real dentro del proyecto:

- un cliente contrata servicios;
- esos servicios protegen activos;
- los activos generan incidentes y eventos;
- los incidentes alimentan el SOC;
- los scripts de ataque permiten comprobar qué controles resisten o fallan.

## Puesta en marcha

### Con Docker Compose

Desde la raíz del monorepo:

```bash
docker compose up -d
```

Modo vulnerable:

```bash
set APP_MODE=vulnerable
docker compose up -d
```

### Con scripts de automatización

Windows:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/start-stack.ps1
powershell -ExecutionPolicy Bypass -File scripts/start-stack.ps1 -Mode vulnerable -Rebuild
```

Linux:

```bash
sh ./scripts/start-stack.sh
sh ./scripts/start-stack.sh vulnerable --build
```

## Scripts de ataque y validación

Los scripts sirven para demostrar que:

- el modo vulnerable permite determinados abusos;
- el modo seguro los bloquea o reduce;
- la lógica de servicios y el SOC responden a esos vectores.

Comandos útiles:

- `npm run attack:sqli:vuln`
- `npm run attack:sqli:secure`
- `npm run attack:xss:vuln`
- `npm run attack:xss:secure`
- `npm run attack:traversal:vuln`
- `npm run attack:traversal:secure`
- `npm run attack:payment:vuln`
- `npm run attack:payment:secure`
- `npm run attack:bruteforce:vuln`
- `npm run attack:bruteforce:secure`
- `npm run services:validate`
- `npm run services:matrix:vuln`
- `npm run services:matrix:secure`

## Qué documentación consultar

- [ATTACK-SCRIPTS.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/ATTACK-SCRIPTS.md)
- [SECURE-LOGIN-EXPLAINED.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/SECURE-LOGIN-EXPLAINED.md)
- [SERVICE-ARCHITECTURE.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/SERVICE-ARCHITECTURE.md)
- [SECURITY-REPORT.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/SECURITY-REPORT.md)
- [DOCUMENTACION-APA.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/DOCUMENTACION-APA.md)

## Credenciales de demostración

- usuario: `admin@sofia.local`
- contraseña: `SofiaAdmin2026!`
