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
- `prometheus`: recolector interno para Grafana;
- `docker-compose.yml`: orquestación completa del entorno.

## 4. Arquitectura de red y servicios

### Servicios expuestos

- `http://localhost:8000` → frontend corporativo
- `http://localhost:8001` → backend API
- `http://localhost:8001/docs` → Swagger
- `http://localhost:8001/health` → healthcheck
- `http://localhost:3000` → Grafana

### Servicios internos

- `postgres:5432`
- `prometheus` como fuente interna de métricas

La decisión de dejar Prometheus como componente interno responde a un criterio práctico: para una defensa o demostración resulta más profesional enseñar el SOC y Grafana como paneles visibles, manteniendo Prometheus como pieza de infraestructura.

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

## 8. Login dual y seguridad comparativa

Una de las piezas centrales del proyecto es la existencia de dos flujos de autenticación visualmente equivalentes, pero distintos internamente:

- `/login`
- `/login-secure`

Endpoints:

- `POST /api/v1/auth/login`
- `GET /api/v2/auth/csrf`
- `POST /api/v2/auth/login`

### Objetivo pedagógico

La idea es demostrar que dos interfaces casi idénticas pueden implicar niveles de riesgo muy diferentes. Esto permite explicar con claridad:

- por qué la seguridad no es una cuestión estética;
- por qué el backend y la política de sesión importan más que la pantalla;
- cómo se detectan y bloquean ataques reales.

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

## 13. Docker y despliegue

El entorno se levanta con `docker-compose.yml`.

Contenedores principales:

- `frontend`
- `backend`
- `postgres`
- `prometheus`
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
