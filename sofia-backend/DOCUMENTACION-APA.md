# Documentación técnica académica

## 1. Introducción

Sofia Solutions es un proyecto académico orientado a ASIX que simula una empresa de servicios IT y ciberseguridad. La solución combina:

- un frontend corporativo servido en web;
- un backend API con lógica de negocio y seguridad;
- una base de datos relacional;
- un monitor SOC corporativo;
- una capa técnica de visualización con Grafana;
- una batería de scripts para demostrar ataques y mitigaciones.

El objetivo no es solo construir una web funcional. El objetivo es demostrar cómo se despliega, securiza, monitoriza y documenta una plataforma completa.

## 2. Justificación para ASIX

Este proyecto encaja con un enfoque ASIX porque obliga a trabajar sobre:

- servicios de red;
- despliegue de aplicaciones;
- administración de bases de datos;
- seguridad y alta disponibilidad;
- scripting y automatización;
- monitorización;
- documentación técnica.

Por tanto, el valor principal no está en la maquetación aislada de páginas, sino en la integración entre sistemas, seguridad y operación.

## 3. Arquitectura general

La arquitectura visible del proyecto queda organizada así:

- `frontend-php/`: capa web corporativa servida por Apache/PHP;
- `sofia-backend/`: API REST construida con Express y TypeScript;
- `postgres`: persistencia relacional;
- `grafana`: visualización técnica;
- `docker-compose.yml`: orquestación completa del entorno.

![Arquitectura general de Sofia Solutions](../memoria/diagramas/arquitectura-general.svg)

### Diagrama general de arquitectura

La capa técnica visible se apoya en el frontend corporativo, la API backend, PostgreSQL y Grafana como panel técnico de apoyo.

## 4. Arquitectura de red y servicios

### Servicios expuestos

- `http://localhost:8000` → frontend corporativo
- `http://localhost:8001` → backend API
- `http://localhost:8001/docs` → Swagger
- `http://localhost:8001/health` → healthcheck
- `http://localhost:3000` → Grafana

### Servicios internos

- `postgres:5432`

La defensa del proyecto se centra en el SOC corporativo y en Grafana como panel técnico visible.

## 5. Frontend corporativo

El frontend visible se ha planteado con una estética empresarial limpia y profesional. Las rutas principales son:

- `/`
- `/login`
- `/login-secure`
- `/dashboard`
- `/admin/security-monitor`

### Objetivo visual

La interfaz debe transmitir:

- marca corporativa;
- profesionalidad;
- claridad;
- confianza;
- coherencia con una empresa de servicios de seguridad.

### Objetivo funcional

El frontend no se limita a presentar formularios:

- muestra la propuesta de valor de la empresa;
- permite autenticación en modo vulnerable y seguro;
- consume el dashboard de negocio;
- muestra un monitor SOC entendible;
- da contexto a los servicios ofrecidos.

### Control de acceso visible

Aunque la home es pública, los paneles operativos no deben quedar expuestos a cualquier visitante. Por ello:

- `/dashboard` y `/admin/security-monitor` quedan reservados a cuentas con rol `ADMIN`;
- el backend protege esos endpoints con autenticación y rol;
- el frontend redirige a `/login` cuando no existe una sesión válida o cuando la cuenta no tiene privilegios suficientes.

## 6. Backend y organización del código

El backend se distribuye por capas para mantener claridad y mantenibilidad:

- `config/`
- `controllers/`
- `routes/`
- `middleware/`
- `services/`
- `utils/`
- `prisma/`
- `tests/`

Esta estructura permite separar:

- entrada HTTP;
- validación;
- seguridad;
- lógica de negocio;
- acceso a datos;
- automatización de pruebas.

## 7. Base de datos y modelo relacional

El modelo se ha diseñado para que el proyecto tenga coherencia operativa y no solo comercial.

Entidades principales:

- `User`
- `Service`
- `Customer`
- `Asset`
- `Incident`
- `Ticket`
- `TicketMessage`
- `Payment`
- `SecurityEvent`

### Lógica del modelo

- un usuario opera sobre la plataforma;
- un cliente contrata servicios;
- un servicio protege activos;
- un activo puede generar incidentes;
- un incidente y un evento de seguridad alimentan el monitor SOC;
- los tickets representan soporte y continuidad operativa;
- los pagos simulan contratación y trazabilidad.

### Diagrama entidad-relación

![Diagrama ER](../memoria/diagramas/base-datos-er.svg)

## 8. Login dual y seguridad comparativa

Una de las piezas centrales del proyecto es la existencia de dos flujos de autenticación visualmente equivalentes, pero distintos internamente:

- `/login`
- `/login-secure`

Endpoints:

- `POST /api/v1/auth/login`
- `GET /api/v2/auth/csrf`
- `POST /api/v2/auth/login`

Las dos rutas visibles (`/login` y `/login-secure`) mantienen la misma interfaz. La diferencia se da en:

- el flujo de autenticación contra la API;
- el tratamiento de tokens y cookies;
- la protección del backend;
- el modo en que se permite o bloquea la carga posterior del panel.

### Objetivo pedagógico

La idea es demostrar que dos interfaces casi idénticas pueden implicar niveles de riesgo muy diferentes. Esto permite explicar con claridad:

- por qué la seguridad no es una cuestión estética;
- por qué el backend y la política de sesión importan más que la pantalla;
- cómo se detectan y bloquean ataques reales.

### Fragmento del login seguro

![Captura del flujo de login seguro](../memoria/diagramas/codigo-login-seguro.svg)

```js
const csrfResponse = await fetch('/api/v2/auth/csrf', {
  credentials: 'include'
});

const { csrfToken } = await csrfResponse.json();

await fetch('/api/v2/auth/login', {
  method: 'POST',
  credentials: 'include',
  headers: {
    'Content-Type': 'application/json',
    'x-csrf-token': csrfToken
  },
  body: JSON.stringify({ email, password })
});
```

### Fragmento del script de arranque en PowerShell

![Captura del script de arranque en PowerShell](../memoria/diagramas/codigo-start-stack.svg)

```powershell
param(
  [ValidateSet("secure", "vulnerable")]
  [string]$Mode = "secure",
  [switch]$Rebuild
)

$env:APP_MODE = $Mode

if ($Rebuild) {
  docker compose down
  docker compose up -d --build
} else {
  docker compose up -d
}
```

### Fragmento del script de arranque en Linux

![Captura del script de arranque en Linux](../memoria/diagramas/codigo-start-stack.svg)

```sh
#!/usr/bin/env sh
set -eu

MODE="${1:-secure}"
REBUILD="${2:-}"

export APP_MODE="$MODE"

if [ "$REBUILD" = "--build" ]; then
  docker compose down
  docker compose up -d --build
else
  docker compose up -d
fi
```

## 9. Ataques demostrados

El proyecto incorpora scripts para demostrar ataques controlados:

- SQL Injection
- XSS
- path traversal
- fuerza bruta
- manipulación de pagos

Estos ataques se ejecutan desde:

- scripts de PowerShell;
- scripts de shell;
- scripts TypeScript en `sofia-backend/tests/`.

### Objetivo de los ataques

No se usan para “romper” el proyecto, sino para enseñar:

- el comportamiento vulnerable;
- la mejora al endurecer controles;
- la utilidad real del catálogo de servicios;
- la importancia de la observabilidad.

### Fragmento del ataque al checkout

![Captura de la validación del checkout seguro](../memoria/diagramas/codigo-checkout-seguro.svg)

```ts
const response = await fetch(`${baseUrl}/api/payments/checkout`, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    Authorization: `Bearer ${accessToken}`,
    'x-demo-mode': mode
  },
  body: JSON.stringify({
    serviceId: 1,
    amount: 1,
    currency: 'EUR',
    last4: '4242',
    brand: 'visa'
  })
});
```

### Fragmento del script de ataques en PowerShell

![Captura del script de ataques en PowerShell](../memoria/diagramas/codigo-run-attacks-ps1.svg)

```powershell
if ($Mode -eq "vulnerable") {
  npm run attack:sqli:vuln
  npm run attack:xss:vuln
  npm run attack:traversal:vuln
  npm run attack:payment:vuln
  npm run attack:bruteforce:vuln
  npm run services:matrix:vuln
} else {
  npm run attack:sqli:secure
  npm run attack:xss:secure
  npm run attack:traversal:secure
  npm run attack:payment:secure
  npm run attack:bruteforce:secure
  npm run services:matrix:secure
}
```

### Fragmento del script de ataques en Linux

![Captura del script de ataques en Linux](../memoria/diagramas/codigo-run-attacks-sh.svg)

```sh
case "$MODE" in
  vulnerable)
    npm run attack:sqli:vuln
    npm run attack:xss:vuln
    npm run attack:traversal:vuln
    npm run attack:payment:vuln
    npm run attack:bruteforce:vuln
    npm run services:matrix:vuln
    ;;
  secure)
    npm run attack:sqli:secure
    npm run attack:xss:secure
    npm run attack:traversal:secure
    npm run attack:payment:secure
    npm run attack:bruteforce:secure
    npm run services:matrix:secure
    ;;
esac
```

### Fragmento de validación del checkout seguro

```ts
const service = await prisma.service.findUnique({
  where: { id: payload.serviceId }
});

if (!service) {
  throw new NotFoundError('Servicio no encontrado');
}

const safeAmount = service.price;
const manipulated = Number(payload.amount) !== Number(service.price);
```

### Fragmento de docker-compose.yml

![Captura del archivo docker-compose.yml](../memoria/diagramas/codigo-docker-compose.svg)

```yaml
services:
  frontend:
    build:
      context: .
      dockerfile: frontend-php/Dockerfile
    ports:
      - "8000:80"

  backend:
    build:
      context: ./sofia-backend
      dockerfile: docker/Dockerfile
    environment:
      PORT: 8001
      DATABASE_URL: postgresql://postgres:postgres@postgres:5432/sofia_solutions
      APP_MODE: ${APP_MODE:-secure}
    ports:
      - "8001:8001"

  postgres:
    image: postgres:15
    ports:
      - "5432:5432"

  grafana:
    image: grafana/grafana:11.1.0
    ports:
      - "3000:3000"
```

## 10. Catálogo de servicios y lógica de negocio

El catálogo de servicios se ha planteado para que tenga sentido dentro de la plataforma y de la memoria.

Servicios principales:

- `SOC 24/7`
- `Pentesting Premium`
- `IR Retainer`
- `Cloud Security Hardening`

### Relación con el dominio

- `SOC 24/7` se relaciona con eventos, incidentes y monitorización;
- `Pentesting Premium` se relaciona con vectores como SQLi o XSS;
- `IR Retainer` se relaciona con contención y gestión de incidentes;
- `Cloud Security Hardening` se relaciona con superficie expuesta y validación de configuraciones.

Esto permite defender que los servicios no son simples textos comerciales, sino capacidades operativas vinculadas a datos reales del sistema.

## 11. SOC corporativo

El monitor SOC sirve como panel explicativo de negocio y seguridad.

Ruta:

- `http://localhost:8000/admin/security-monitor`

Su función es mostrar de forma comprensible:

- volumen de eventos;
- incidentes críticos;
- vectores más frecuentes;
- países de origen;
- distribución de alertas;
- exposición por cliente;
- relación entre servicios e incidentes.

## 12. Grafana

Grafana se usa como panel técnico de apoyo a la defensa.

Ruta:

- `http://localhost:3000`

Su utilidad es mostrar:

- métricas de peticiones;
- actividad del backend;
- patrones de autenticación;
- comportamiento temporal del sistema.

Grafana no sustituye al SOC. Lo complementa.

### Acceso recomendado durante la defensa

- web corporativa: `http://localhost:8000`
- login vulnerable: `http://localhost:8000/login`
- login seguro: `http://localhost:8000/login-secure`
- dashboard: `http://localhost:8000/dashboard`
- SOC: `http://localhost:8000/admin/security-monitor`
- Swagger: `http://localhost:8001/docs`
- Grafana: `http://localhost:3000`

Credenciales de demostración:

- cuenta administradora: `admin@sofia.local`
- contraseña: `SofiaAdmin2026!`
- Grafana: `admin / admin`

## 13. Docker y despliegue

El entorno se levanta con `docker-compose.yml`.

Contenedores principales:

- `frontend`
- `backend`
- `postgres`
- `grafana`

### Ventajas de esta aproximación

- entorno reproducible;
- despliegue rápido;
- separación clara de servicios;
- facilidad de demostración en local.

## 14. Automatización y scripting

Para reforzar el enfoque ASIX se han incluido scripts para:

- levantar el stack;
- reconstruir contenedores;
- alternar entre modo seguro y vulnerable;
- ejecutar baterías de ataques.

Archivos principales:

- `scripts/start-stack.ps1`
- `scripts/start-stack.sh`
- `scripts/run-attacks.ps1`
- `scripts/run-attacks.sh`

## 15. Qué se puede explicar en una defensa

Orden recomendado:

1. explicar el problema y el objetivo del proyecto;
2. enseñar la arquitectura de red y contenedores;
3. mostrar el frontend corporativo;
4. comparar `/login` y `/login-secure`;
5. ejecutar uno o dos ataques;
6. abrir el monitor SOC;
7. abrir Grafana;
8. explicar base de datos, servicios y automatización;
9. cerrar con conclusiones y mejoras futuras.

## 16. Conclusión

Sofia Solutions se puede defender como una plataforma académica coherente entre:

- negocio;
- seguridad;
- red;
- base de datos;
- scripting;
- Docker;
- observabilidad.

Ese equilibrio es precisamente lo que hace que el proyecto encaje bien en ASIX.
