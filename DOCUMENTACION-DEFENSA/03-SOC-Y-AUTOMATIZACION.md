# Guía de Defensa: SOC y Automatización (n8n)

Aquí demuestras tu capacidad de orquestación y respuesta ante incidentes.

### 1. El Orquestador n8n
- **Qué decir:** "He implementado un SOC (Security Operations Center) automatizado. No dependemos de que un humano mire el panel 24/7. n8n actúa como nuestro cerebro de respuesta."
- **Flujo:** Webhook -> Lógica de Triaje -> Alerta Dinámica.

### 2. El Sistema SOS
- **Escenario:** El cliente pulsa el botón de emergencia SOS en su dashboard.
- **Automatización:** 
  1. El Backend envía un payload a n8n.
  2. n8n detecta la palabra clave "SOS".
  3. Se genera un correo de **Alta Prioridad** con diseño rojo de emergencia.
  4. El incidente se registra automáticamente en el monitor maestro del administrador.

### 3. Notificaciones Profesionales
- **Punto Clave:** "Los correos electrónicos que genera el sistema son HTML corporativo. Esto asegura que el analista reciba la información crítica (IP, Tipo de Ataque, Cliente) de forma visual e inmediata."
