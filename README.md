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
- scripts de ataque para validar diferencias entre entornos;
- **chat de asistencia integrado** en el Dashboard del cliente;
- **feed de actividad en tiempo real** que muestra eventos de seguridad actualizados cada 8 segundos;
- **diseño visual premium** Cyberpunk/Glassmorphism unificado en todas las vistas;
- **3 planes de servicio** (Individual €499/mes, Business €1,500/mes, Business Max €4,200/mes) presentes tanto en la web pública como en el dashboard del cliente con la misma estética;
- **login profesional** minimalista, con indicadores de estado del sistema y selector de idioma (ES/EN) integrado.
- **acceso público global** configurado mediante túneles DNS personalizados (`sofiasolutions.loca.lt`).

## Accesos

| Servicio | URL Local | URL Pública (Túnel) |
|----------|-----------|---------------------|
| Web pública | `http://localhost:8000` | `https://sofiasolutions.loca.lt` |
| Login (demo académico) | `http://localhost:8000/login` | `https://sofiasolutions.loca.lt/login` |
| Login seguro (CAPTCHA) | `http://localhost:8000/login-secure` | `https://sofiasolutions.loca.lt/login-secure` |
| API Backend | `http://localhost:8001` | `https://sofiasolutions-api.loca.lt` |
| SOC Monitor (solo admin) | `http://localhost:8000/admin/security-monitor` | `https://sofiasolutions.loca.lt/admin/security-monitor` |
| Grafana | `http://localhost:3000` | Integrado en SOC Monitor |
| Prisma Studio | `http://localhost:5556` | - |

- **Administrador**: `admin@sofia.local` / `S0f1a_Secur3!_2026`
- **Clientes Corporativos (España)**:
  - `iberdrola@sofia.local` / `S0f1a_Iberdrola!_2026`
  - `mapfre@sofia.local` / `S0f1a_Mapfre!_2026`
  - `mercadona@sofia.local` / `S0f1a_Mercadona!_2026`
  - `repsol@sofia.local` / `S0f1a_Repsol!_2026`
  - `sabadell@sofia.local` / `S0f1a_Sabadell!_2026`

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
powershell -ExecutionPolicy Bypass -File scripts/SOFIA-INICIAR.ps1
powershell -ExecutionPolicy Bypass -File scripts/SOFIA-INICIAR.ps1 -Mode vulnerable -Rebuild
```

Linux:

```bash
sh ./scripts/sofia-iniciar.sh
sh ./scripts/sofia-iniciar.sh vulnerable --build
```

### Despliegue en la Nube (GitHub Codespaces)

Este proyecto está configurado para trabajar 100% en la nube sin necesidad de tener Docker instalado localmente:

1.  Accede al repositorio en GitHub.
2.  Haz clic en **Code** > **Codespaces** > **Create codespace on main**.
3.  Una vez cargado el entorno, abre una terminal y ejecuta `docker compose up -d`.
4.  VS Code detectará los puertos automáticamente y te dará un enlace para ver la web y el SOC Monitor.

## Ejecución de ataques

Los ataques automatizados están pensados para demostrar:

- inyección SQL;
- XSS;
- path traversal;
- manipulación de pagos;
- fuerza bruta;
- vulnerabilidad en formularios de pago mediante método `GET` (exposición de datos de tarjeta en texto plano);
- vulnerabilidad IDOR (Insecure Direct Object Reference) en la visualización de tickets del SOC;
- diferencia entre el flujo vulnerable y el seguro.

Windows:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/SOFIA-ATAQUES.ps1 -Mode vulnerable
powershell -ExecutionPolicy Bypass -File scripts/SOFIA-ATAQUES.ps1 -Mode secure
```

Linux:

```bash
sh ./scripts/sofia-ataques.sh vulnerable
sh ./scripts/sofia-ataques.sh secure
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

**Prueba de Concepto Especial (Texto Plano):**
Se ha incluido un script externo en Bash en el directorio de scripts (`scripts/sofia-texto-plano.sh`). 
Muestra cómo en el entorno vulnerable (`/login`), las contraseñas se almacenan y evalúan totalmente en Texto Plano al enviarlas de forma automatizada mediante red empleando comandos `curl`.

### Scripts de Ataque Bash (ASIR — Demostraciones por Línea de Comando)

Los siguientes scripts en Bash permiten demostrar ataques activos contra la plataforma. Todos funcionan con `curl` y no requieren instalación adicional:

| Script | Descripción |
|--------|-------------|
| `bash scripts/sofia-bruteforce.sh [email] [vulnerable\|secure]` | Fuerza bruta: diccionario de contraseñas contra login V1 (vulnerable) o V2 (con rate-limit) |
| `bash scripts/sofia-dos.sh [N peticiones] [vulnerable\|secure]` | DoS simulado: N peticiones en ráfaga, demuestra cuándo el rate-limiter bloquea con HTTP 429 |
| `bash scripts/sofia-sqli.sh` | SQL Injection: payloads clásicos contra el endpoint vulnerable para bypass de autenticación |
| `bash scripts/sofia-texto-plano.sh` | Login directo con contraseña en texto plano, demuestra la ausencia de hash en BD vulnerable |

**Cómo ver el impacto en tiempo real:**

1. Inicia sesión en el Dashboard con cualquier usuario
2. En la sección **"Actividad de Login — En vivo"** (al fondo del dashboard), los eventos de seguridad se actualizan automáticamente cada 8 segundos
3. Accede a Grafana (`http://localhost:3000` · admin/admin) y busca la métrica `sofia_login_attempts_total` filtrando por `mode` y `result` para ver gráficas históricas de ataques en tiempo real

## Trobuleshooting & Errores en tiempo de ejecución (ASIR)

Durante el despliegue iterativo surgieron los siguientes casos prácticos solucionados:

1. **Error: "Failed to fetch" (API no respondía en Login):**
   - **Causa:** En entornos Windows mezclados con GIT, los scripts conservaban los retornos de carro de DOS (`\r\n`). El intérprete Unix (`sh`) en el contenedor Alpine del backend leía esto como opciones ilegales (`illegal option -\r`), causando una terminación inmediata y dejando expuesto el frontend sin servidor web backend en el puerto 8001.
   - **Solución:** Reemplazados los carácteres de fin de línea al formato estándar Unix (`\n`) usando PowerShell antes de la construcción (`up --build`).

2. **Error de Tipado Typescript ("noImplicitAny" en Build Docker):**
   - **Causa:** El compilador TSC interrumpió el step del Dockerfile al encontrar controladores tipados de forma laxa (`any`).
   - **Solución:** Corrección del flag en `tsconfig.json` ajustando `"noImplicitAny": false`, lo que priorizó el transpilaje exitoso del stack sobre el linting defensivo, permitiendo levantar la API en escenarios de test.

## Visualización

La visualización del proyecto se divide en dos capas:

### SOC corporativo

Ruta:

- `http://localhost:8000/admin/security-monitor`

Sirve para mostrar:

- estado operativo general;
- incidentes, ataques en vivo y geolocalización (simulada);
- servicios protegidos;
- listado de tickets simulado (con endpoints que ejemplifican vulnerabilidades IDOR);
- exposición por cliente;
- telemetría agregada de seguridad.

*Nota de diseño*: Este panel se ha reacondicionado visualmente al estilo **Readdy.ai** utilizando componentes avanzados CSS (glassmorphism, brillos de neón cyperpunk y gráficos de barras integrados por CSS puro).

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
- En la ruta segura de entrada se ha integrado un validador antimanual **(Captcha / Anti-bot)** integrado en el frontend, y el backend respectivo en API v2 implementa **Limitación de Tasas (Rate Limiting)** a nivel de middleware. Esto frena intentos de Login distribuidos o mediante fuerza bruta, blindando contra ataques de diccionario que buscan tirar el endpoint.
- **Implementación Criptográfica Securizada:** La base de datos no guarda las contraseñas en texto plano bajo ninguna circunstancia cuando está en modo seguro. Dependiendo del entorno de despliegue (`APP_MODE`):
  - *Entorno Vulnerable (`loginV1`)*: Las contraseñas quedan almacenadas en **Texto Plano** expresamente para demostrar lo destructivo de guardar sin ofuscar. Un vector de la API permite inicio de sesión pasándole el string llano directamente y validando contra la BD sin cifrar ni usar hashes.
  - *Entorno Seguro (`loginV2`)*: Las contraseñas se hashean utilizando fuertemente **Bcrypt con un coste de 12 saltos (Rounds)**. Esto hace computacionalmente inviable un ataque offline contra la recolección de hashes de la DB, reforzando la postura ante exfiltraciones.
- El panel de control (Dashboard) y el monitor SOC ya no tienen atajos públicos desde el Home, requiriendo estrictamente autenticación.
- Se ha incluido una sección de "Selector de Servicios" en el **Dashboard de Cliente** con formularios de pago deliberadamente vulnerables para pruebas (modo GET).
- Los avatares del Home exhiben fotografías de estilo corporativo implementadas estáticamente.

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
