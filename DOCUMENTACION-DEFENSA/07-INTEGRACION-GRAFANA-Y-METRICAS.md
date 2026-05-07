# Guía Técnica: Integración de Grafana y Telemetría

Esta guía explica el flujo de datos desde que ocurre un evento hasta que se visualiza en el SOC.

### 1. El Flujo de Datos (Pipeline de Observabilidad)
El sistema utiliza una arquitectura estándar de la industria:
1.  **Instrumentación:** El Backend (Node.js) usa la librería `prom-client` para registrar eventos (login exitoso, fallo 401, error 500).
2.  **Exposición:** Las métricas se exponen en un endpoint oculto: `/api/metrics`.
3.  **Recolección (Scraping):** Prometheus (contenedor interno) consulta este endpoint cada 15 segundos y guarda los datos en su base de datos de series temporales.
4.  **Visualización:** Grafana se conecta a Prometheus como "Data Source" y transforma esos números en gráficos de líneas y medidores.

### 2. Embebiendo paneles en el Dashboard
- **Técnica:** Utilizamos **IFrames con Anonymous Auth**.
- **Seguridad:** Grafana está configurado en el archivo `custom.ini` para permitir que sus paneles se vean dentro de nuestro dominio Sofia Solutions, pero bloqueando cualquier intento de edición externa.
- **Dato para la defensa:** "No estamos usando imágenes estáticas. El dashboard de Sofia Solutions consume los paneles de Grafana en tiempo real, lo que nos permite ver ráfagas de ataques DoS o picos de errores de autenticación mientras ocurren."

### 3. Qué estamos monitorizando
- **Error Rate:** Correlación entre peticiones totales y fallos (Detección de inestabilidad).
- **Auth Metrics:** Ratio de logins fallidos vs exitosos (Detección de Fuerza Bruta).
- **System Health:** Latencia de respuesta del API Gateway (Detección de DDoS por agotamiento).
