# Guía de Configuración y Explicación: n8n SOC Orchestrator

Este documento explica la lógica técnica detrás del sistema de alertas automatizadas de **Sofia Solutions** para que puedas realizar una demostración técnica fluida abriendo los nodos en la interfaz de n8n.

## Arquitectura del Workflow
El flujo de trabajo es lineal y reactivo, diseñado para el procesamiento en tiempo real (Real-Time Response).

### 1. Nodo Webhook: "Webhook Sofia"
*   **Función:** Actúa como el "oído" del sistema. Escucha peticiones HTTP POST externas.
*   **Endpoint:** `/webhook/alert`.
*   **Datos Recibidos:** Recibe un objeto JSON con:
    *   `alertId`: Identificador único.
    *   `severity`: Nivel de gravedad (Critical, High, etc.).
    *   `clientName`: Nombre del cliente afectado.
    *   `title` / `description`: Detalles del incidente.
*   **Punto Clave:** Es la puerta de entrada que conecta el Backend (Node.js/PHP) con la automatización.

### 2. Nodo de Lógica: "Urgent/High" (Nodo IF)
*   **Función:** Filtro de triaje. Decide qué alertas son lo suficientemente importantes como para enviar un correo inmediato al SOC.
*   **Lógica:** Utiliza una expresión regular (Regex) para detectar si la severidad es `critical`, `high` o `urgent`.
*   **Punto Clave:** Evita el "ruido" de alertas menores, asegurando que el equipo de seguridad solo reciba notificaciones críticas.

### 3. Nodo de Acción: "Enviar Email SOC" (Nodo SMTP)
*   **Función:** Generación y envío del reporte técnico profesional.
*   **Plantilla HTML/CSS:**
    *   **Responsive:** Diseñada para verse perfecta en Gmail móvil y escritorio (uso de tablas anidadas y media queries).
    *   **Branding Dinámico:** Se asocia visualmente al Dashboard de Sofia Solutions usando sus colores corporativos (`#0f172a` y `#6366f1`).
*   **Lógica Inteligente (Expresiones):**
    *   **Detección de SOS:** El nodo analiza el texto mediante una expresión: `($title.includes('SOS')) ? 'EMERGENCIA SOS' : ...`. Si detecta "SOS", cambia el icono a 🚨 y el texto para máxima alerta.
    *   **Mapeo de Datos:** Inserta dinámicamente el nombre del cliente, el ID y el timestamp del incidente.
*   **Punto Clave:** No usa imágenes externas para garantizar que el correo cargue instantáneamente y sin errores de seguridad ("mostrar imágenes") en el cliente de correo.

## Cómo explicarlo en la demo:
1.  **Abre el Webhook:** Muestra que es una URL pública que espera datos de cualquier parte del sistema.
2.  **Abre el Nodo IF:** Explica que aquí se definen las reglas de negocio (priorización).
3.  **Abre el Nodo de Email:** Entra en la pestaña "HTML" y muestra cómo usamos código limpio para que el diseño sea "Premium" y cómo las expresiones `{{ ... }}` permiten que cada correo sea único para cada cliente.

---
*Configurado por Antigravity para Sofia Solutions SOC v2026*
