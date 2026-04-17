# Sofia Solutions

Proyecto final orientado a **ASIX**, centrado en:

- seguridad ofensiva y defensiva;
- contenedores y despliegue;
- servicios de red;
- base de datos;
- scripting de automatización;
- monitorización y visualización;
- comparación entre implementación vulnerable y segura.

La solución representa una plataforma corporativa de servicios IT y ciberseguridad con:

- frontend servido en web;
- backend API con Express y TypeScript;
- PostgreSQL con Prisma;
- Docker Compose para el entorno completo;
- visualización de métricas en **Grafana**;
- monitor SOC propio para la parte corporativa;
- scripts de ataque para validar diferencias entre entornos.

## Accesos

- Web: `http://localhost:8000`
- Login vulnerable: `http://localhost:8000/login`
- Login seguro: `http://localhost:8000/login-secure`
- Dashboard: `http://localhost:8000/dashboard`
- SOC Monitor: `http://localhost:8000/admin/security-monitor`
- API: `http://localhost:8001`
- Swagger: `http://localhost:8001/docs`
- Grafana: `http://localhost:3000`

Credenciales demo:

- usuario: `admin@sofia.local`
- contraseña: `SofiaAdmin2026!`

Acceso a paneles restringidos:

- `http://localhost:8000/dashboard` y `http://localhost:8000/admin/security-monitor` requieren una cuenta con rol `ADMIN`
- si no existe sesión válida, el frontend redirige a `http://localhost:8000/login`
- la cuenta administradora de demostración es `admin@sofia.local`

Credenciales Grafana:

- usuario: `admin`
- contraseña: `admin`

## Enfoque ASIX

El proyecto no debe entenderse como una simple web comercial. Su valor principal para ASIX está en:

- desplegar y administrar una arquitectura cliente-servidor completa;
- securizar autenticación, sesiones y endpoints;
- modelar una base de datos relacional coherente;
- levantar servicios con Docker Compose;
- automatizar tareas con scripts en Windows y Linux;
- simular ataques reales sobre una versión vulnerable;
- verificar el bloqueo de esos mismos ataques en la versión segura;
- monitorizar actividad y exponer métricas visuales en Grafana.

## Estructura

- `frontend-php/`: frontend servido por Apache/PHP para la capa visible
- `sofia-backend/`: API REST, lógica de seguridad, Prisma, ataques y documentación técnica
- `scripts/`: automatización multiplataforma para levantar stack y ejecutar ataques
- `docker-compose.yml`: orquestación del entorno

## Documentación disponible

- [README backend](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/README.md)
- [Guía de ataques](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/ATTACK-SCRIPTS.md)
- [Explicación del login seguro](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/SECURE-LOGIN-EXPLAINED.md)
- [Arquitectura funcional de servicios](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/SERVICE-ARCHITECTURE.md)
- [Arquitectura, defensa y operación](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/ARCHITECTURE-AND-DEFENSE.md)
- [Informe de seguridad](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/SECURITY-REPORT.md)
- [Documentación técnica académica](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/DOCUMENTACION-APA.md)
- [Guion de defensa](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/DEFENSA-PROYECTO.md)
- [Guía completa de estudio](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/GUIA-ESTUDIO-PROYECTO.md)

## Despliegue

### Opción rápida con Docker

Modo seguro:

```bash
docker compose up -d
```

Modo vulnerable:

```bash
set APP_MODE=vulnerable
docker compose up -d
```

### Scripts de automatización

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

## Ejecución de ataques

Los ataques automatizados están pensados para demostrar:

- inyección SQL;
- XSS;
- path traversal;
- manipulación de pagos;
- fuerza bruta;
- diferencia entre el flujo vulnerable y el seguro.

Windows:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/run-attacks.ps1 -Mode vulnerable
powershell -ExecutionPolicy Bypass -File scripts/run-attacks.ps1 -Mode secure
```

Linux:

```bash
sh ./scripts/run-attacks.sh vulnerable
sh ./scripts/run-attacks.sh secure
```

También puedes usar los scripts de `npm`:

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
- `npm run services:matrix:vuln`
- `npm run services:matrix:secure`

## Visualización

La visualización del proyecto se divide en dos capas:

### SOC corporativo

Ruta:

- `http://localhost:8000/admin/security-monitor`

Sirve para mostrar:

- estado operativo general;
- incidentes;
- servicios protegidos;
- exposición por cliente;
- telemetría agregada de seguridad.

### Grafana

Ruta:

- `http://localhost:3000`

Se utiliza como panel técnico para enseñar:

- volumen de peticiones;
- ataques bloqueados;
- intentos de login;
- sesiones activas;
- evolución temporal de la actividad.


## Decisiones de UX/UI

- El logo corporativo se fuerza con el mismo tratamiento visual en home, login, dashboard y SOC.
- `/login` y `/login-secure` usan la misma interfaz; la diferencia está en el backend.
- El dashboard y el monitor SOC solo deben visualizarse con una sesión administradora válida.

## Backend

Endpoints principales:

- `POST /api/v1/auth/login`
- `POST /api/v2/auth/login`
- `GET /api/v2/auth/csrf`
- `GET /api/admin/overview`
- `GET /api/admin/security-monitor`
- `GET /api/services`
- `GET /api/services/catalog`
- `GET /api/services/effectiveness`
- `GET /metrics`

## Objetivo docente

La finalidad del proyecto es demostrar, en un entorno controlado:

- cómo se despliega una plataforma full stack;
- cómo se modelan datos empresariales y operativos;
- cómo se levantan servicios con Docker;
- cómo se automatizan tareas de administración;
- cómo se monitoriza una aplicación;
- cómo se explotan vulnerabilidades intencionadas;
- y cómo esas vulnerabilidades quedan mitigadas en una implementación segura.
