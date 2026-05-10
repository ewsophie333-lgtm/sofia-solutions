# Sofia Solutions — Plataforma de Ciberseguridad Gestionada

> **Proyecto Final ASIX 2026** — Seguridad Ofensiva y Defensiva  
> Autor: Sofia Solutions Security Group

---

## 📋 Requisitos Previos

| Requisito | Versión mínima |
|---|---|
| **Docker Desktop** | 4.x (con Docker Compose V2) |
| **Python 3** | 3.9+ (solo para el Audit Framework CLI) |
| **Navegador** | Chrome / Firefox (recomendado) |

> **⚠️ IMPORTANTE**: Asegúrate de que Docker Desktop está **ejecutándose** antes de continuar.

---

## 🚀 Instalación y Arranque (1 comando)

### Opción A: Script automático (Windows)
```powershell
.\scripts\SOFIA-INICIAR.ps1 -Mode vulnerable -Rebuild
```

### Opción B: Docker Compose directo
```bash
set APP_MODE=vulnerable
docker compose up -d --build
```

> El primer arranque tarda ~2-3 minutos (descarga imágenes Docker).

### Verificar que todo funciona
```bash
docker compose ps
```
Deberías ver 7 servicios activos: `frontend`, `backend`, `postgres`, `prometheus`, `grafana`, `n8n`, `cadvisor`.

---

## 🌐 Puntos de Acceso (Local)

| Servicio | URL | Descripción |
|---|---|---|
| **Web Principal** | [http://localhost:8000](http://localhost:8000) | Landing page corporativa |
| **Login Vulnerable** | [http://localhost:8000/login](http://localhost:8000/login) | Login V1 (sin protecciones) |
| **Login Seguro** | [http://localhost:8000/login-seguro](http://localhost:8000/login-seguro) | Login V2 (bcrypt + captcha) |
| **Dashboard Cliente** | [http://localhost:8000/dashboard](http://localhost:8000/dashboard) | Panel ejecutivo del cliente |
| **Panel SOC Admin** | [http://localhost:8000/admin](http://localhost:8000/admin) | Centro de operaciones de seguridad |
| **Consola de Auditoría** | [http://localhost:8000/consola](http://localhost:8000/consola) | Framework de ataques visual |
| **Grafana Metrics** | [http://localhost:3000](http://localhost:3000) | Métricas en tiempo real (admin/admin) |
| **n8n Workflows** | [http://localhost:5678](http://localhost:5678) | Automatización de alertas |
| **API Docs** | [http://localhost:8001/docs](http://localhost:8001/docs) | Swagger / OpenAPI |
| **Database GUI** | [http://localhost:5555](http://localhost:5555) | Prisma Studio (ejecutar script) |

---

## 🔐 Credenciales de Demostración

### Modo Vulnerable (V1) — Contraseñas en texto plano
| Rol | Usuario | Contraseña |
|---|---|---|
| **Administrador SOC** | `admin@sofia.local` | `admin123` |
| **Cliente MAPFRE** | `mapfre@sofia.local` | `mapfre123` |
| **Cliente IBERDROLA** | `iberdrola@sofia.local` | `iberdrola123` |
| **Cliente MERCADONA** | `mercadona@sofia.local` | `mercadona123` |

### Modo Seguro (V2) — Contraseñas con bcrypt (12 rounds)
| Rol | Usuario | Contraseña |
|---|---|---|
| **Administrador SOC** | `admin@sofia.local` | `S0f1a_Secur3!_2026` |
| **Cliente MAPFRE** | `mapfre@sofia.local` | `S0f1a_Mapfre!_2026` |

> En modo seguro, las contraseñas simples como `admin123` **no funcionan**.

---

## 🎯 Guión de Demostración para el Jurado

### FASE 1: Demostración Ofensiva (Modo Vulnerable)

1. **Verificar modo vulnerable**:
   ```
   scripts\activar-modo-vulnerable.bat
   ```

2. **Acceder a la Consola de Auditoría**: [http://localhost:8000/consola](http://localhost:8000/consola)

3. **Ejecutar ataques** (en este orden):

   | # | Ataque | Qué demostrar |
   |---|---|---|
   | 1 | **SQL Injection** | Bypass de login: el payload `' OR '1'='1'--` entra sin contraseña |
   | 2 | **Fuerza Bruta** | Diccionario encuentra `admin123` sin bloqueo |
   | 3 | **XSS Reflejado** | Cookie theft: roba cookies/tokens del navegador |
   | 4 | **Path Traversal** | Lee `/etc/passwd` del servidor a través de `download.php` |
   | 5 | **IDOR** | Accede a datos de otros clientes (MAPFRE, Iberdrola) |
   | 6 | **Cookie Hijack** | Burp Suite: intercepta JWT y escala privilegios CLIENT→ADMIN |

4. **Verificar alertas SOC**: Ir a [http://localhost:8000/admin](http://localhost:8000/admin) → Feed de alertas en tiempo real

5. **Verificar email**: Los ataques CRITICAL/HIGH generan emails automáticos vía n8n → Gmail

### FASE 2: Monitorización y SOC

1. Abrir **Grafana**: [http://localhost:3000](http://localhost:3000) (admin/admin)
2. Ir al dashboard **"Sofia Security Overview"**
3. Mostrar: HTTP Requests, Blocked Attacks, Login Attempts, Active Sessions
4. Mostrar: Attack Origins by Country, Alerts Sent to Gmail

### FASE 3: Respuesta ante Incidentes (SOS)

1. Login como cliente (ej: `mapfre@sofia.local` / `mapfre123`)
2. Pulsar botón **SOS** en el dashboard
3. Cambiar a vista Admin → ver notificación de emergencia
4. Pulsar **"Tomar control del caso"**

### FASE 4: Demostración Defensiva (Modo Seguro)

1. **Cambiar a modo seguro**:
   ```
   scripts\activar-modo-seguro.bat
   ```

2. Repetir los mismos ataques desde la consola:
   - **SQLi** → Bloqueado por WAF (HTTP 403)
   - **Fuerza Bruta** → Bloqueado por Rate Limiter (HTTP 429 tras 10 intentos)
   - **XSS** → Payload sanitizado, no se ejecuta
   - **Path Traversal** → Whitelist de archivos, acceso denegado

### Herramienta CLI (Alternativa a la Consola Web)
```bash
python scripts/sofia-audit-framework.py
```
Framework interactivo por terminal con los mismos ataques.

---

## 🏗️ Arquitectura del Sistema

```
┌─────────────┐    ┌──────────────┐    ┌───────────┐
│  Frontend   │───▶│   Backend    │───▶│ PostgreSQL│
│  PHP/Apache │    │ Node+Express │    │   15-alp  │
│  :8000      │    │ :8001        │    │   :5432   │
└─────────────┘    └──────┬───────┘    └───────────┘
                          │
              ┌───────────┼───────────┐
              ▼           ▼           ▼
        ┌──────────┐ ┌────────┐ ┌─────────┐
        │Prometheus│ │  n8n   │ │ Grafana │
        │  :9090   │ │ :5678  │ │  :3000  │
        └──────────┘ └───┬────┘ └─────────┘
                         │
                         ▼
                    ┌──────────┐
                    │  Gmail   │
                    │(alertas) │
                    └──────────┘
```

### Stack Tecnológico

| Componente | Tecnología |
|---|---|
| Frontend | PHP 8.2 + Apache (interfaz visual) |
| Backend API | Node.js + Express 5 + TypeScript |
| ORM | Prisma (PostgreSQL) |
| Auth | JWT (HS256) + bcrypt + cookies HttpOnly |
| WAF | Middleware custom (detección de patrones) |
| Métricas | Prometheus + prom-client |
| Dashboards | Grafana (provisionado automático) |
| Alertas | n8n (webhook → SMTP → Gmail) |
| Containers | Docker Compose (7 servicios) |

---

## 📊 Base de Datos (Inspección)

Para mostrar al jurado los datos internos:
```
scripts\ABRIR-DATABASE-GUI.bat
```
Accede a [http://localhost:5555](http://localhost:5555) → Tablas:
- `User` → Usuarios con contraseñas (plaintext vs bcrypt)
- `SecurityEvent` → Log de todos los ataques detectados
- `Alert` → Alertas del SOC con severidad y estado
- `Incident` → Incidentes por cliente con geolocalización
- `Customer` → Clientes corporativos (Iberdrola, MAPFRE, etc.)

---

## 📁 Estructura del Proyecto

```
sofia-solutions/
├── frontend-php/          # Interfaz visual (Apache + PHP 8.2)
│   └── public/
│       ├── views/         # Páginas: login, dashboard, audit, admin, soc
│       ├── assets/        # CSS, JS, logos
│       └── includes/      # Helpers y AI assistant
├── sofia-backend/         # API Core (Node.js + Express + Prisma)
│   ├── src/
│   │   ├── controllers/   # Auth, Admin, Tickets, Alerts, Services
│   │   ├── middleware/     # WAF, Rate Limit, Attack Detection
│   │   ├── services/      # SOC notifications, Audit logging
│   │   └── config/        # Prometheus, Threats, Environment
│   ├── prisma/            # Schema + Seed data
│   └── docker/            # Dockerfile + Grafana dashboards
├── scripts/               # Automatización para la defensa
├── docker-compose.yml     # Orquestación completa (7 servicios)
└── n8n_workflow_sofia.json # Workflow de alertas importable
```

---

## ⚙️ Scripts Disponibles

| Script | Función |
|---|---|
| `SOFIA-INICIAR.ps1` | Levanta todo el stack Docker |
| `activar-modo-vulnerable.bat` | Cambia a modo V1 (sin protecciones) |
| `activar-modo-seguro.bat` | Cambia a modo V2 (WAF + bcrypt + rate limit) |
| `lanzar-rootkit.bat` | Lanza el framework de ataques CLI (Python) |
| `ABRIR-DATABASE-GUI.bat` | Abre Prisma Studio (GUI de base de datos) |

---

## 🔔 Sistema de Alertas (n8n)

El flujo de alertas funciona así:
1. Se detecta un ataque (middleware `attackDetection.ts`)
2. Se registra en `SecurityEvent` (base de datos)
3. Si la severidad es **CRITICAL** o **HIGH**, se envía webhook a n8n
4. n8n procesa y envía email a `ewsophie333@gmail.com`

| Tipo de ataque | Severidad | ¿Envía email? |
|---|---|---|
| SQL Injection | CRITICAL | ✅ Sí |
| XSS | HIGH | ✅ Sí |
| Path Traversal | CRITICAL | ✅ Sí |
| IDOR/BOLA | HIGH | ✅ Sí |
| SOS (Cliente) | CRITICAL | ✅ Sí |
| Brute Force (pocos intentos) | MEDIUM | ❌ Solo log |
| DoS / Rate Limit | MEDIUM | ❌ Solo log |
