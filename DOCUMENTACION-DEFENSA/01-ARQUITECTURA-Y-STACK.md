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

### 2. Acceso Público Seguro (TLS 1.3)
- **Cifrado en Tránsito:** La aplicación es servida mediante un túnel SSH reverso con cifrado **TLS 1.3**. Esto garantiza que todas las credenciales y datos de clientes viajen cifrados desde el navegador hasta nuestro nodo de salida.
- **Certificado SSL:** "Como pueden observar en el candado verde del navegador, el dominio `serveousercontent.com` utiliza un certificado válido emitido por una CA reconocida, protegiendo contra ataques de Man-in-the-Middle (MitM)."

### 3. Persistencia y Modelado de Datos
- **Prisma ORM:** "Para la gestión de datos utilizo Prisma. Esto no solo facilita el desarrollo, sino que añade una capa de abstracción de seguridad (Query Sanitization) por defecto en todos los módulos, excepto en aquellos que he dejado vulnerables deliberadamente para la demostración."
- **PostgreSQL:** El motor de base de datos está aislado dentro de la red interna de Docker, por lo que no es accesible desde el exterior si no es a través de nuestra API controlada.
