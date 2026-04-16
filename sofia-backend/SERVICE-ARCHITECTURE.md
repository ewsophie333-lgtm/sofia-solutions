# Arquitectura funcional de servicios

## Objetivo

Este documento explica por qué el catálogo de servicios de Sofia Solutions tiene sentido dentro del proyecto y cómo se conecta con:

- el monitor SOC;
- la base de datos;
- los clientes y activos;
- los incidentes;
- los ataques demostrados.

El catálogo no se plantea como una lista estética o comercial. Se plantea como una capa de negocio que da contexto al resto del sistema.

## Idea central

Cada servicio ofrecido por Sofia Solutions representa una capacidad operativa. Eso significa que un servicio debe poder responder a preguntas como:

- qué protege;
- a quién protege;
- qué riesgos ayuda a reducir;
- qué evidencias genera;
- cómo se refleja en el SOC y en el dashboard.

## Servicios modelados

### 1. SOC 24/7

Servicio orientado a:

- monitorización continua;
- detección de actividad anómala;
- correlación de eventos;
- seguimiento de incidentes.

Se relaciona especialmente con:

- `SecurityEvent`
- `Incident`
- `Asset`
- monitor SOC
- Grafana

### 2. Pentesting Premium

Servicio preventivo orientado a:

- descubrir vulnerabilidades antes de explotación;
- evaluar exposición web;
- validar controles de entrada y salida.

Se relaciona con ataques como:

- SQL Injection;
- XSS;
- path traversal.

### 3. IR Retainer

Servicio reactivo orientado a:

- contención;
- investigación;
- priorización de incidentes críticos;
- reducción del tiempo de respuesta.

Se relaciona con:

- incidentes activos;
- tickets;
- estado operativo;
- vectores críticos.

### 4. Cloud Security Hardening

Servicio de endurecimiento orientado a:

- reducir configuraciones inseguras;
- limitar abuso de superficie expuesta;
- validar que la lógica sensible permanezca del lado servidor.

Se relaciona con:

- activos protegidos;
- configuración segura;
- manipulación de pagos;
- traversal y otros fallos de exposición.

## Relación con el modelo de datos

### `Service`

Define la oferta:

- nombre;
- categoría;
- tier;
- SLA;
- precio;
- descripción funcional.

### `Customer`

Representa una organización protegida por uno o varios servicios.

### `Asset`

Representa infraestructura real bajo cobertura:

- firewall;
- WAF;
- VPN;
- mail gateway;
- aplicación;
- consola EDR;
- nodo cloud.

### `Incident`

Representa evidencia operativa del valor del servicio:

- severidad;
- vector;
- estado;
- activo afectado;
- país de origen;
- IP origen.

## Relación con el SOC

El monitor SOC necesita que el dominio de negocio tenga sentido. Si solo existieran usuarios y servicios sin activos ni incidentes, el panel sería decorativo.

Con el modelo actual:

- los clientes aportan contexto empresarial;
- los activos aportan superficie protegida;
- los incidentes aportan señal operativa;
- los servicios justifican por qué esa protección existe.

## Endpoints que lo sostienen

- `GET /api/services`
- `GET /api/services/catalog`
- `GET /api/services/effectiveness`
- `GET /api/services/:id`
- `GET /api/admin/overview`
- `GET /api/admin/security-monitor`

## Relación entre ataques y servicios

| Ataque | Servicio principal relacionado | Justificación |
|---|---|---|
| SQL Injection | Pentesting Premium | detecta fallos de validación y exposición |
| XSS | Pentesting Premium | revisa entradas, salidas y reflexión de payloads |
| Fuerza bruta | SOC 24/7 | detecta abuso de identidad y repetición de intentos |
| Credential Abuse | SOC 24/7 / IR Retainer | correlación y respuesta operativa |
| Malware | SOC 24/7 / IR Retainer | visibilidad e intervención |
| Path Traversal | Cloud Security Hardening | endurecimiento de rutas y permisos |
| Manipulación de pagos | Cloud Security Hardening | refuerzo de lógica de servidor |

## Cómo defenderlo en la memoria

La forma correcta de explicarlo en el TFG es esta:

1. el catálogo representa la capa de negocio;
2. los clientes y activos representan la capa de operación;
3. los ataques representan la capa de riesgo;
4. el SOC y Grafana representan la capa de observabilidad;
5. la API une esas capas mediante datos persistidos y endpoints verificables.

Así el proyecto no se reduce a:

- una web bonita;
- una API aislada;
- o un panel con datos inventados.

Pasa a ser una plataforma coherente entre negocio, seguridad, red, base de datos y operación.

## Comandos útiles

- `npm run services:validate`
- `npm run services:matrix:vuln`
- `npm run services:matrix:secure`
- `npm run attack:defense:vuln`
- `npm run attack:defense:secure`
