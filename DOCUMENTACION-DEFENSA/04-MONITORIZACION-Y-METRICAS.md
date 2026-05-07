# Guía de Defensa: Monitorización y Telemetría

Este panel es el que "entra por los ojos" al tribunal. Úsalo para demostrar control total.

### 1. Panel de Salud en Tiempo Real
- **Demostración:** "He programado un sistema de detección de latencia. Si el backend se ralentiza o cae (por ejemplo, durante un ataque DDoS), el dashboard cambia a un estado visual de 'CRÍTICO' automáticamente."
- **Tecnología:** JavaScript con `AbortController` para detectar timeouts.

### 2. Grafana: El Estándar de la Industria
- **Qué decir:** "Para la monitorización de métricas avanzadas utilizo Grafana. Recolectamos datos de login, errores y volumen de tráfico para visualizar patrones de ataque."
- **Punto Clave:** "Esto permite realizar un análisis forense después de un ataque, viendo exactamente a qué hora empezó la ráfaga de peticiones."

### 3. Chart.js en el Dashboard
- **Explicación:** Los gráficos de neón que ves en el dashboard no son imágenes estáticas; son datos reales procesados en el navegador del administrador para facilitar la toma de decisiones.
