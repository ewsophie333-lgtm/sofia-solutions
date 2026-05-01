# 🛡️ Guía Maestra de Defensa: Proyecto Sofia Solutions 2026
**Especialidad: ASIX (Administración de Sistemas Informáticos en Red)**

Este documento es tu guion estratégico para una defensa de 10. Está diseñado para impresionar al tribunal mostrando no solo código, sino una **infraestructura robusta, automatizada y securizada**.

---

## 🚀 1. El Concepto (Elevator Pitch)
"Sofia Solutions es una plataforma integral de ciberseguridad gestionada (MSSP). No es solo una web; es un ecosistema completo que combina **monitorización en tiempo real (Grafana/SOC)**, **gestión de clientes** y una **infraestructura automatizada en Docker**. El valor diferencial es su dualidad: permite demostrar vulnerabilidades críticas y su mitigación inmediata mediante estándares industriales."

---

## 🏗️ 2. Arquitectura Técnica (El "Cómo funciona")
*Menciona estos puntos mientras enseñas el código o los diagramas:*

*   **Contenerización:** Todo el stack corre en **Docker**. Hemos orquestado 5 servicios: Backend (Node.js), Frontend (Apache/PHP), Base de Datos (PostgreSQL), Monitorización (Grafana) y un Proxy/Túnel para acceso externo.
*   **Backend Escalable:** Node.js con TypeScript para un tipado fuerte, usando **Prisma ORM** para interactuar con la DB de forma segura (prevención nativa de SQLi).
*   **Frontend Corporativo:** PHP para la lógica de servidor y una interfaz moderna basada en **Glassmorphism**, optimizada para visualización de datos masivos.
*   **Seguridad:** Implementación de **JWT (JSON Web Tokens)** para sesiones, **Bcrypt** (coste 12) para contraseñas y middlewares de **Rate Limiting** para frenar ataques DoS y Brute Force.

---

## 📊 3. Tour de la Plataforma (Demostración en Vivo)

### Paso A: El Dashboard del Cliente
1.  **Entra como Cliente** (`sabadell@sofia.local` / `S0f1a_Sabadell!_2026`).
2.  **Muestra el Health Check:** Enseña el widget de "Estado del Servidor". Explica que hace un **ping real** a la API. Si apagas el contenedor del backend, el widget se pone rojo en tiempo real.
3.  **Consola de Diagnóstico:** Ejecuta el comando `session`. Explica: "Damos al cliente herramientas de autoservicio para verificar su identidad y limpiar su caché local sin perder la sesión JWT".
4.  **Sincronización:** Muestra cómo los "Ataques Bloqueados" y "Tickets" suben cuando hay actividad en el sistema.

### Paso B: El Monitor SOC (Vista Administrador)
1.  **Entra como Admin** (`admin@sofia.local` / `S0f1a_Secur3!_2026`).
2.  **Métricas Globales:** Enseña los KPIs (Eventos Analizados, Salud del Sistema).
3.  **Grafana Integrado:** Desplázate hasta el panel de Grafana. Explica: "Hemos integrado Grafana directamente mediante frames securizados. Aquí vemos la telemetría cruda: ráfagas de tráfico, intentos de login y carga de CPU".
4.  **Live Feed:** Pulsa en "Eventos Recientes" en la consola de admin. Verás el log de ataques en tiempo real.

---

## ⚔️ 4. La Joya de la Corona: Modo Vulnerable vs Seguro
*Aquí es donde ganas la máxima nota. Usa los scripts renombrados:*

1.  **Ataque SQL Injection:**
    *   Ejecuta `.\scripts\levantar-docker.ps1 -Mode vulnerable`.
    *   Usa la "Consola Maestra" (`/consola`) para hacer bypass del login.
    *   **Explicación:** "En modo vulnerable, la query SQL concatena strings directamente. Esto permite el bypass."
2.  **Demostración de Hash:**
    *   Abre **Prisma Studio** (`npx prisma studio`).
    *   Muestra la tabla `User` en modo vulnerable: "Contraseñas en texto plano. Un desastre de seguridad".
    *   Ejecuta `.\scripts\levantar-docker.ps1 -Mode secure`.
    *   Muestra la tabla `User` otra vez: "Ahora son hashes Bcrypt. Aunque nos roben la base de datos, los datos están a salvo".
3.  **Ataque de Fuerza Bruta:**
    *   Usa `.\scripts\demo-ataques.ps1`.
    *   Muestra cómo en modo seguro, el sistema bloquea la IP tras 5 intentos fallidos (HTTP 429).

---

## 💡 5. Preguntas del Tribunal (Q&A Prep)
*   **¿Por qué PHP y Node.js juntos?**
    *   *Respuesta:* "Para demostrar interoperabilidad entre microservicios. PHP gestiona la presentación corporativa y Node.js actúa como una API Gateway de alto rendimiento para el SOC."
*   **¿Cómo garantizas la persistencia de datos?**
    *   *Respuesta:* "Mediante volúmenes de Docker vinculados a PostgreSQL. Los datos sobreviven al reinicio de los contenedores."
*   **¿Es el sistema escalable?**
    *   *Respuesta:* "Sí. Al estar en contenedores, podríamos desplegarlo en un clúster de Kubernetes y replicar el backend según la carga de red."

---

## 🏆 Conclusión
"Este proyecto demuestra que la ciberseguridad no es un producto, sino un proceso. Desde el despliegue automatizado hasta la monitorización visual, Sofia Solutions ofrece una defensa en profundidad (Defense in Depth) preparada para los retos de 2026."
