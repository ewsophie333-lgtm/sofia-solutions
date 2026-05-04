# Guía de Configuración: Automatización de Alertas con n8n

## 1. Acceso a n8n
- URL: http://localhost:5678
- Crea una cuenta y accede

## 2. Crear Webhook para Recibir Alertas

### Paso 1: Nuevo Workflow
- Click en "+ New Workflow"
- Nombre: "Sofia Alert Email Notification"

### Paso 2: Agregar nodo Webhook
1. Click en el símbolo "+
2. Busca "Webhook" y selecciona "Webhook"
3. Configura:
   - **Method**: POST
   - **Authentication**: None (or Basic if needed)
   - El webhook URL será algo como: `http://n8n:5678/webhook/alert`

### Paso 3: Agregar nodo de Email
1. Click "+" para agregar otro nodo
2. Busca "Gmail" o "SMTP Email"
   - Si usas **Gmail**: Necesitas configurar OAuth
   - Si usas **SMTP**: Configura con un servicio como SendGrid, Mailgun, etc.

3. Configura el email:
   - **To**: `ewsophie333@gmail.com`
   - **Subject**: `[ALERTA URGENTE] {{$json.severity}} - {{$json.title}}`
   - **Body**:
   ```
   Alerta Crítica Recibida:
   
   Título: {{$json.title}}
   Descripción: {{$json.description}}
   Severidad: {{$json.severity}}
   Hora: {{$json.timestamp}}
   Usuario: {{$json.userName}}
   
   ID de Alerta: {{$json.alertId}}
   ```

### Paso 4: Conectar nodos
- Conecta el nodo Webhook al nodo de Email
- Click en "Execute Workflow" para guardar

## 3. Notas Importantes

### Para Gmail (OAuth):
```bash
# Crear una "App Password" en Google Account
# 1. Ve a myaccount.google.com/apppasswords
# 2. Crea una contraseña específica para n8n
# 3. Usa esa contraseña en el nodo de email
```

### Para SMTP (Alternativa):
```
SMTP Server: smtp.gmail.com (o tu proveedor)
SMTP Port: 587
Username: tu-email@gmail.com
Password: tu-app-password-o-contraseña
TLS: true
```

## 4. Probar la Automatización

### Endpoint para crear alertas:
```bash
curl -X POST http://localhost:8001/api/alerts/urgent \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Alerta de Prueba",
    "description": "Esta es una alerta de prueba para validar n8n",
    "severity": "CRITICAL"
  }'
```

### Alternativa: Simular webhook directamente
```bash
curl -X POST http://localhost:5678/webhook/alert \
  -H "Content-Type: application/json" \
  -d '{
    "alertId": 1,
    "title": "Prueba de Alerta Urgente",
    "description": "Prueba de descripción",
    "severity": "CRITICAL",
    "timestamp": "2026-05-04T07:15:00Z",
    "recipientEmail": "ewsophie333@gmail.com",
    "userName": "admin"
  }'
```

## 5. Integración Completa

Una vez configurado n8n, las alertas se enviarán automáticamente cuando:
1. Un cliente presione el botón "Alerta Urgente" en el dashboard
2. O cuando el sistema detecte eventos críticos
3. El email llegará a ewsophie333@gmail.com con todos los detalles

## 6. Dashboard Sofia

Para disparar una alerta desde el frontend:
```javascript
// En la consola del navegador o en tu código
fetch('/api/alerts/urgent', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + localStorage.getItem('sofia_user_v1.token'),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    title: 'Alerta Urgente - Intrusión Detectada',
    description: 'Se detectó acceso no autorizado en asset crítico',
    severity: 'CRITICAL'
  })
})
.then(r => r.json())
.then(data => console.log('Alerta enviada:', data))
```

## Referencias
- n8n Docs: https://docs.n8n.io
- Webhook: https://docs.n8n.io/nodes/n8n-nodes-base.webhook/
- Email: https://docs.n8n.io/nodes/n8n-nodes-base.gmail/
