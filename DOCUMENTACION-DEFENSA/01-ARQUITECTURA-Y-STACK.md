# Guía de Defensa: Arquitectura y Stack Tecnológico

Este es el cimiento de tu proyecto. Aquí demuestras que sabes gestionar infraestructura moderna.

### 1. El Corazón: Docker Compose
- **Qué decir:** "He diseñado una arquitectura de microservicios orquestada mediante Docker Compose. Esto permite que todo el ecosistema (Frontend, Backend, DB, n8n y Grafana) se despliegue con un solo comando, garantizando que el entorno sea idéntico en desarrollo y producción."
- **Componentes:**
  - **Frontend-PHP**: Servidor Apache que entrega la interfaz corporativa.
  - **Sofia-Backend**: API REST en Node.js (Express + TypeScript).
  - **Database**: PostgreSQL persistente.
  - **SOC Gateway**: n8n para la automatización de alertas.
  - **Tunnel**: Acceso público seguro mediante túneles DNS.

### 2. Acceso Público (Túnel)
- **Punto Clave:** Explica que no has abierto puertos en tu router, sino que usas un túnel seguro que expone la app a internet con una URL profesional (`sofia-solutions.serveousercontent.com`). Esto demuestra conocimientos de redes y conectividad.

### 3. Persistencia con Prisma ORM
- **Qué decir:** "Para la gestión de datos utilizo Prisma. No escribo SQL manual para las operaciones comunes, lo que reduce errores y facilita el mantenimiento de la base de datos PostgreSQL."
