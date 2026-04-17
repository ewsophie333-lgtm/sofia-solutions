# Guía completa de estudio del proyecto

## Objetivo

Este documento está pensado para estudiar el proyecto de forma completa. Resume:

- qué hace cada capa;
- cómo se relacionan;
- qué tecnologías intervienen;
- qué debes saber explicar sin mirar el código.

## 1. Qué es Sofia Solutions

Sofia Solutions es una plataforma académica que simula una empresa de servicios IT y ciberseguridad.

No es solo una página web. Es una solución compuesta por:

- frontend corporativo;
- backend API;
- base de datos PostgreSQL;
- panel ejecutivo;
- monitor SOC;
- observabilidad técnica en Grafana;
- scripts de ataque;
- despliegue con Docker Compose.

## 2. Objetivo principal del proyecto

El objetivo es demostrar cómo se construye y administra una plataforma completa, comparando una implementación vulnerable con otra segura.

La idea clave del proyecto es esta:

> La misma funcionalidad puede verse igual para el usuario, pero comportarse de forma muy distinta según cómo se diseñe la seguridad del backend.

## 3. Arquitectura general

La arquitectura se divide en varias capas.

## 3.1 Frontend

Se sirve en:

- `http://localhost:8000`

Rutas principales:

- `/`
- `/login`
- `/login-secure`
- `/dashboard`
- `/admin/security-monitor`

Función:

- mostrar la parte corporativa;
- permitir autenticación;
- mostrar panel ejecutivo;
- mostrar monitor SOC;
- enlazar con Grafana.

## 3.2 Backend

Se sirve en:

- `http://localhost:8001`

Función:

- exponer la API REST;
- aplicar reglas de negocio;
- gestionar autenticación;
- proteger rutas;
- consultar la base de datos;
- registrar y devolver datos para dashboard y SOC.

## 3.3 Base de datos

Se usa PostgreSQL.

Función:

- guardar usuarios;
- guardar servicios;
- guardar clientes y activos;
- guardar tickets e incidentes;
- guardar pagos;
- guardar eventos de seguridad.

## 3.4 Observabilidad

Hay dos capas:

- SOC corporativo
- Grafana técnico

El SOC es la capa visible orientada a negocio y seguridad.
Grafana es la capa técnica para métricas y defensa.

## 4. Tecnologías del proyecto

## Frontend visible

- PHP
- HTML
- CSS
- JavaScript

## Backend

- Node.js
- Express
- TypeScript
- Zod
- Prisma

## Base de datos

- PostgreSQL

## Infraestructura

- Docker
- Docker Compose

## Observabilidad

- Grafana
- Prometheus como fuente interna

## 5. Qué hace cada ruta visible

## `/`

Es la página corporativa principal. Su función es presentar:

- la empresa;
- los servicios;
- la propuesta de valor;
- los accesos a la plataforma.

## `/login`

Es el login vulnerable. Visualmente se ve igual que el seguro, pero internamente usa el flujo menos protegido.

## `/login-secure`

Es el login seguro. Usa el flujo reforzado del backend.

## `/dashboard`

Es el panel ejecutivo. Resume servicios, tickets, eventos y postura general.

Debe estar restringido a usuarios administradores.

## `/admin/security-monitor`

Es el monitor SOC. Muestra:

- vectores de ataque;
- incidentes;
- top países;
- servicios protegidos;
- resumen de estado.

También debe estar restringido a administradores.

## 6. Autenticación dual

Esta es una de las partes más importantes del proyecto.

## Idea principal

Hay dos logins con la misma interfaz, pero distinto backend:

- vulnerable
- seguro

## Login vulnerable

Ruta visual:

- `/login`

Endpoint:

- `POST /api/v1/auth/login`

Características:

- menos controles;
- validación más débil;
- pensado para demostrar fallos;
- facilita fuerza bruta o abuso en un entorno controlado.

## Login seguro

Ruta visual:

- `/login-secure`

Endpoints:

- `GET /api/v2/auth/csrf`
- `POST /api/v2/auth/login`

Características:

- validación más estricta;
- protección CSRF;
- mejor control de sesión;
- mejor bloqueo de payloads maliciosos.

## 7. Ataques demostrados

El proyecto incluye ataques controlados para comparar comportamiento.

## 7.1 SQL Injection

Objetivo:

- demostrar bypass o tolerancia insegura en vulnerable;
- demostrar bloqueo en seguro.

## 7.2 XSS

Objetivo:

- demostrar reflexión insegura o aceptación del payload;
- demostrar bloqueo en seguro.

## 7.3 Path Traversal

Objetivo:

- demostrar tolerancia de cadenas peligrosas;
- demostrar detección y rechazo.

## 7.4 Fuerza bruta

Objetivo:

- mostrar ausencia de control suficiente en vulnerable;
- mostrar limitación de intentos en seguro.

## 7.5 Manipulación de pagos

Objetivo:

- demostrar que el cliente no debe decidir el importe final.

En vulnerable:

- el importe enviado por el cliente puede persistirse.

En seguro:

- el backend valida o recalcula el importe real.

## 8. Pagos

El sistema tiene un checkout simulado.

No procesa tarjetas reales, pero sí modela:

- servicio contratado;
- importe;
- moneda;
- marca de tarjeta;
- últimos 4 dígitos;
- transacción persistida.

Esto permite explicar:

- lógica de negocio;
- validación del lado servidor;
- manipulación de importes;
- persistencia de pagos;
- importancia de no confiar en el frontend.

## 9. Dashboard

El dashboard está pensado para una visión ejecutiva.

Debe explicarse así:

- resume el estado general del servicio;
- muestra actividad reciente;
- presenta KPIs;
- ofrece visibilidad sobre tickets y seguridad;
- sirve como capa de lectura para un cliente o responsable de seguridad.

## 10. Monitor SOC

El SOC es la parte más orientada a seguridad visible.

Debe explicarse así:

- muestra actividad e incidentes;
- sintetiza vectores, orígenes y servicios afectados;
- ayuda a visualizar lo que los ataques generan;
- permite conectar ataques, servicios y postura defensiva.

## 11. Control de acceso

No todo el mundo puede ver el dashboard o el SOC.

Lógica:

- las rutas del backend bajo `/api/admin/*` exigen autenticación y rol `ADMIN`;
- si el frontend intenta cargarlas sin sesión válida, redirige a `/login`;
- así se evita que cualquier visitante pueda ver estadísticas o incidentes.

Cuenta demo administradora:

- `admin@sofia.local`
- `SofiaAdmin2026!`

## 12. Base de datos

Entidades más importantes:

- `User`
- `Service`
- `Customer`
- `Asset`
- `Incident`
- `Ticket`
- `TicketMessage`
- `Payment`
- `SecurityEvent`

## Cómo explicarlas

### User

Representa usuarios de la plataforma y sus roles.

### Service

Representa la oferta comercial y operativa.

### Customer

Representa clientes protegidos.

### Asset

Representa infraestructura protegida.

### Incident

Representa incidentes o actividad correlacionada.

### Ticket

Representa soporte o continuidad operativa.

### Payment

Representa pagos o contratación simulada.

### SecurityEvent

Representa evidencia de actividad o ataques.

## 13. Docker

Docker Compose levanta el entorno completo.

Servicios principales:

- frontend
- backend
- postgres
- prometheus
- grafana

Ventaja:

- despliegue reproducible;
- entorno consistente;
- facilidad para defensa y demostración.

## 14. Scripts

Hay scripts para:

- arrancar el entorno;
- reconstruirlo;
- lanzar ataques;
- validar diferencias entre modos.

Esto es muy importante para ASIX porque demuestra automatización real.

## 15. Por qué encaja con ASIX

Porque combina:

- redes;
- servicios;
- contenedores;
- bases de datos;
- seguridad;
- scripting;
- despliegue;
- observabilidad.

No es solo un proyecto visual. Es un sistema administrable y defendible.

## 16. Qué debes memorizar sí o sí

- objetivo del proyecto;
- arquitectura general;
- diferencia entre login vulnerable y seguro;
- por qué el SOC y el dashboard no son públicos;
- qué ataques se pueden demostrar;
- por qué Docker es importante;
- qué aporta PostgreSQL;
- por qué Grafana complementa al SOC;
- por qué esto tiene sentido en ASIX.

## 17. Resumen final corto para estudiar

Versión corta:

> Sofia Solutions es una plataforma académica que simula una empresa de servicios IT y ciberseguridad. Tiene frontend corporativo, backend API, PostgreSQL, Docker Compose, dashboard, monitor SOC, Grafana y una comparativa entre seguridad vulnerable y segura. Su valor principal está en demostrar despliegue, administración, control de acceso, ataques, mitigaciones, monitorización y observabilidad en un entorno coherente y defendible para ASIX.
