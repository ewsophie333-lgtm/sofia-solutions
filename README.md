# Sofia Solutions

Proyecto final orientado a **ASIX**, centrado en seguridad ofensiva y defensiva.

## 🌐 Puntos de Acceso (Entorno de Defensa)

- **Plataforma Corporativa:** [https://sofia-solutions.serveousercontent.com](https://sofia-solutions.serveousercontent.com)
- **Consola de Auditoría:** [https://sofia-solutions.serveousercontent.com/consola](https://sofia-solutions.serveousercontent.com/consola)
- **Panel SOC Administrativo:** [https://sofia-solutions.serveousercontent.com/admin](https://sofia-solutions.serveousercontent.com/admin)
- **Grafana Metrics:** [https://sofia-solutions.serveousercontent.com/grafana/](https://sofia-solutions.serveousercontent.com/grafana/)
- **Workflow n8n:** [https://sofia-n8n.serveousercontent.com](https://sofia-n8n.serveousercontent.com)

---

## 🔐 Credenciales de Demostración

| Rol | Usuario | Contraseña |
|-----|---------|------------|
| **Administrador SOC** | `admin@sofia.local` | `admin123` |
| **Cliente (MAPFRE)** | `mapfre@sofia.local` | `mapfre123` |
| **Cliente (IBERDROLA)** | `iberdrola@sofia.local` | `iberdrola123` |
| **Cliente (MERCADONA)** | `mercadona@sofia.local` | `mercadona123` |

---

## 🚀 Guía para la Defensa (Paso a Paso)

### 1. Demostración Ofensiva (Modo Vulnerable)
1. Asegúrate de que el sistema esté en **Modo Vulnerable** (`scripts/ACTIVAR-MODO-VULNERABLE.bat`).
2. Accede a la **Consola de Auditoría** (`/consola`).
3. Ejecuta un **SQL Injection** en el login. Observa cómo el sistema permite el acceso sin contraseña.
4. Ejecuta un ataque de **Fuerza Bruta**. La consola mostrará las contraseñas probadas con estilo Hydra.
5. Ejecuta un **XSS Reflejado** seleccionando "Robo de Cookies". Observa cómo se abre **Burp Suite Interceptor** automáticamente para permitirte modificar la sesión (Escalada de Privilegios).
6. Verifica que las alertas llegan al **SOC** y al correo Gmail configurado.

### 2. Monitorización y SOC
1. Abre el **Panel SOC** (`/admin`).
2. Observa el panel de **"Exposición de Riesgo por Cliente"** para ver a Mercadona y otros clientes en tiempo real.
3. El gráfico de **"Incidentes por Hora"** reflejará los ataques realizados desde la consola.
4. Muestra **Grafana** para ver las métricas técnicas agregadas (Prometheus).

### 3. Respuesta ante Incidentes (SOS)
1. Inicia sesión como cliente (ej. MAPFRE o MERCADONA).
2. Pulsa el botón **SOS** en el dashboard.
3. Cambia a la vista de Administrador y observa la notificación de emergencia inmediata.
4. Pulsa **"Tomar control del caso"** para simular la intervención del SOC.

### 4. Demostración Defensiva (Modo Seguro)
1. Cambia a **Modo Seguro** (`scripts/ACTIVAR-MODO-SEGURO.bat`).
2. Intenta repetir la fuerza bruta. Verás que el sistema bloquea la IP tras solo **3 intentos fallidos** (Rate Limiting estricto).
3. Intenta el SQLi; el **WAF Shield** detectará el payload y denegará la petición.
4. Explica cómo el **Hashing de Bcrypt** y la **Validación Estricta** protegen los datos.

---

## 📊 Visualización de Base de Datos
Si el tribunal solicita ver los datos internos (usuarios, logs de seguridad, tickets):
1. Ejecuta el script `scripts/ABRIR-DATABASE-GUI.bat`.
2. Accede a: [http://localhost:5555](http://localhost:5555)
3. Aquí podrás mostrar la tabla `SecurityEvent` con todos los ataques detectados en tiempo real.

---

## 🛠️ Estructura del Proyecto
- `frontend-php/`: Interfaz visual (Apache + PHP 8.2).
- `sofia-backend/`: API Core (Node.js + Express + Prisma).
- `docker-compose.yml`: Orquestación completa del stack.
- `scripts/`: Herramientas de automatización para la defensa.
