# Arquitectura, defensa y operación

## Propósito del documento

Este documento resume el proyecto desde una perspectiva práctica de implantación y defensa técnica. Está pensado para complementar el README y servir como guía rápida para explicar el sistema sin tener que recorrer todo el código.

## 1. Topología del entorno

El entorno completo se despliega con Docker Compose y queda formado por:

- `frontend`: capa web corporativa
- `backend`: API REST y lógica de seguridad
- `postgres`: persistencia de datos
- `prometheus`: recolección interna de métricas
- `grafana`: visualización técnica

## 2. Flujo de red

1. El usuario accede al frontend por `localhost:8000`.
2. El frontend llama al backend por `localhost:8001`.
3. El backend consulta PostgreSQL.
4. El backend expone métricas internas.
5. Prometheus las recoge.
6. Grafana las visualiza.

## 3. Capas de la solución

### Capa de presentación

- home corporativa
- login vulnerable
- login seguro
- dashboard
- monitor SOC

### Capa de aplicación

- autenticación
- servicios
- tickets
- pagos
- monitorización

### Capa de seguridad

- rate limit
- detección de ataques
- validación
- control de sesión
- eventos de seguridad

### Capa de datos

- usuarios
- clientes
- servicios
- activos
- incidentes
- tickets
- pagos
- eventos de seguridad

## 4. Elementos que justifican el proyecto

Este proyecto tiene valor académico porque combina:

- una red de servicios desplegable;
- una base de datos relacional real;
- automatización multiplataforma;
- un frontend entendible para cliente;
- un backend explicable para defensa técnica;
- una comparativa clara entre seguridad débil y seguridad reforzada.

## 5. Evidencias recomendadas

Para una presentación o memoria conviene capturar:

- `docker compose ps`
- la home
- `/login`
- `/login-secure`
- `/dashboard`
- `/admin/security-monitor`
- Swagger
- Grafana
- una ejecución de ataques
- una consulta a tablas relevantes en PostgreSQL

## 6. Relación entre ataques, servicios y defensa

La fortaleza del proyecto está en que cada ataque tiene traducción funcional:

- SQLi y XSS justifican pentesting;
- brute force justifica SOC;
- incidentes críticos justifican IR Retainer;
- manipulación de importes justifica hardening y validación del lado servidor.

## 7. Conclusión operativa

El proyecto puede defenderse como una plataforma de servicios IT y ciberseguridad capaz de:

- desplegarse;
- monitorizarse;
- atacarse de forma controlada;
- observar su comportamiento;
- explicar por qué una configuración segura responde mejor que una vulnerable.
