# SERVICE-ARCHITECTURE

## Objetivo

Este documento justifica por que el catalogo de servicios de Sofia Solutions tiene sentido tecnico dentro del proyecto y como se relaciona con el monitor SOC, los activos, los clientes y los ataques demostrados.

## Idea central

Los servicios no se modelan como tarjetas comerciales aisladas. Cada servicio representa una capacidad operativa que afecta a:

- clientes protegidos
- activos gestionados
- incidentes monitorizados
- cobertura de vectores de ataque
- SLA y nivel de respuesta

## Estructura funcional

### 1. Servicios preventivos

- Pentesting Premium
- Cloud Security Hardening

Finalidad:

- reducir superficie de ataque
- encontrar fallos antes de explotacion
- disminuir exposicion en activos y configuraciones

Vectores asociados:

- SQL Injection
- XSS
- Path Traversal
- Reconnaissance
- Cloud Misconfiguration

### 2. Servicios de deteccion

- SOC 24/7

Finalidad:

- recolectar telemetria
- correlacionar eventos
- detectar abuso de credenciales, phishing, malware y actividad anomala

Vectores asociados:

- Brute Force
- Phishing
- Malware
- Credential Abuse
- SQL Injection

### 3. Servicios de respuesta

- IR Retainer

Finalidad:

- contener incidentes
- reducir impacto
- acelerar investigacion y recuperacion

Vectores asociados:

- Malware
- Credential Abuse
- Phishing
- Brute Force

## Relacion con el modelo de datos

### `Service`

Define la oferta operativa:

- nombre
- categoria
- tier
- SLA
- precio

### `Customer`

Representa la organizacion protegida y referencia su servicio principal.

### `Asset`

Representa infraestructura real bajo cobertura:

- firewalls
- WAF
- VPN
- mail gateways
- aplicaciones
- consolas EDR

### `Incident`

Representa la salida operativa del SOC y la evidencia de que un servicio tiene utilidad real.

## Logica implementada

El backend expone:

- `GET /api/services`
  devuelve servicios enriquecidos con clientes protegidos, activos, riesgo y vectores cubiertos.

- `GET /api/services/catalog`
  devuelve el catalogo detallado con narrativa operativa y metricas por servicio.

- `GET /api/services/effectiveness`
  calcula efectividad, incidentes mitigados, cobertura y justificacion por linea de servicio.

- `GET /api/services/:id`
  devuelve detalle de un servicio concreto.

## Justificacion academica para TFG

En una memoria de ASIR, esta parte se puede defender asi:

1. El catalogo comercial sirve como capa de negocio del sistema.
2. El monitor SOC representa la capa operativa.
3. Los ataques demostrados representan la capa de riesgo.
4. La API une esas tres capas mediante datos persistidos y endpoints verificables.

En consecuencia, el proyecto no enseña solo una web corporativa o una API, sino una plataforma con coherencia entre negocio, seguridad y operacion.

## Ataques y servicios relacionados

| Ataque | Servicio con mayor relacion | Motivo |
|---|---|---|
| SQL Injection | Pentesting Premium | descubre y reduce vectores explotables antes de produccion |
| XSS | Pentesting Premium | valida entrada, salida y sanitizacion |
| Brute Force | SOC 24/7 | detecta repeticion, abuso de identidad y patrones de acceso |
| Credential Abuse | SOC 24/7 / IR Retainer | detecta y contiene uso fraudulento de credenciales |
| Malware | SOC 24/7 / IR Retainer | correlaciona alertas EDR y activa respuesta |
| Path Traversal | Cloud Security Hardening / Pentesting | revisa hardening, rutas y permisos |
| Manipulacion de pagos | Cloud Security Hardening / revisiones seguras | obliga a validar del lado servidor |

## Comandos de demostracion

- `npm run services:validate`
- `npm run services:matrix:vuln`
- `npm run services:matrix:secure`
- `npm run attack:defense:vuln`
- `npm run attack:defense:secure`
