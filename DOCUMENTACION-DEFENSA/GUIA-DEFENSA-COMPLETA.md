# GUÍA DE DEFENSA — SOFIA SOLUTIONS TFG ASIR 2025-2026
**Autor:** Laura Sofía Gómez Salazar | **Tutor:** Isaac García Simarro | **IES Enric Soler i Godes**

---

## ESTRUCTURA DE LA PRESENTACIÓN (20 minutos)

| Bloque | Contenido | Tiempo |
|--------|-----------|--------|
| 1 | Contexto y problema que resuelve | 2 min |
| 2 | Arquitectura técnica (Docker stack) | 3 min |
| 3 | Demo en vivo: modo vulnerable (ataques) | 6 min |
| 4 | Demo en vivo: modo seguro (contramedidas) | 3 min |
| 5 | SOC Monitor + Grafana + n8n | 3 min |
| 6 | Conclusiones y ampliaciones | 2 min |
| 7 | Preguntas del tribunal | (ilimitado) |

---

## BLOQUE 1 — CONTEXTO (2 min)

**Qué decir:**
> "Sofia Solutions es una plataforma de ciberseguridad gestionada pensada para PYMEs. El problema que resuelve es que muchas empresas pequeñas no tienen recursos para un SOC propio, pero están igual de expuestas a ataques. Esta plataforma simula lo que tendría ese SOC: monitorización en tiempo real, gestión de incidencias, automatización de alertas y un módulo educativo para demostrar vulnerabilidades reales contra contramedidas reales."

**Puntos clave:**
- El proyecto parte de CERO con solo software libre y Docker
- Funciona desde Codespaces sin instalar nada localmente
- Tiene un doble propósito: plataforma SOC operativa + laboratorio de ciberseguridad

---

## BLOQUE 2 — ARQUITECTURA (3 min)

**Stack Docker Compose (7 servicios):**

```
┌─────────────────────────────────────────────────────────────┐
│  SOFIA SOLUTIONS — DOCKER COMPOSE STACK                     │
│                                                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────┐  │
│  │  frontend    │  │   backend    │  │   postgres       │  │
│  │  PHP/Apache  │  │  Node.js+TS  │  │  PostgreSQL 15   │  │
│  │  :8000       │  │  :8001       │  │  :5432           │  │
│  └──────┬───────┘  └──────┬───────┘  └────────┬─────────┘  │
│         │                 │                   │            │
│         └────────────────►│◄──────────────────┘            │
│                           │                                │
│  ┌──────────────┐  ┌──────┴───────┐  ┌──────────────────┐  │
│  │  grafana     │  │  prometheus  │  │   n8n            │  │
│  │  :3000       │  │  :9090       │  │  :5678           │  │
│  └──────────────┘  └──────────────┘  └──────────────────┘  │
│                                                             │
│  ┌──────────────┐                                           │
│  │  tunnel      │ → serveo.net → HTTPS público              │
│  │  SSH Alpine  │   sofia-solutions.serveousercontent.com   │
│  └──────────────┘                                           │
└─────────────────────────────────────────────────────────────┘
```

**Qué decir:**
> "Toda la infraestructura vive en Docker Compose. El frontend es PHP con Apache, el backend es Node.js con TypeScript. Se comunican por la red interna sofia-network. Prometheus recoge métricas del backend cada 15 segundos, Grafana las visualiza, y n8n automatiza las alertas de seguridad. El túnel SSH expone todo públicamente con HTTPS sin necesidad de IP fija."

**Concepto MODO DUAL — importante explicarlo:**
> "La clave del proyecto es la variable APP_MODE. Si la pongo en 'vulnerable', el sistema activa rutas sin protección, consultas SQL sin parametrizar y contraseñas en texto plano. Si la pongo en 'secure', activa Bcrypt, rate limiting, consultas Prisma parametrizadas y CAPTCHA. Con un solo comando cambio entre el estado inseguro y el seguro para hacer la demo comparativa."

---

## BLOQUE 3 — DEMO ATAQUES VULNERABLES (6 min)

### 3.1 SQL Injection — Auth Bypass (2 min)

**Flujo de la demo:**
1. Abrir `/consola`
2. Seleccionar módulo "Inyección SQL" → target "Login Inseguro (v1)"
3. Payload: `' OR '1'='1'--`
4. Ejecutar → mostrar que la terminal dice "EXPLOIT EXITOSO"

**Qué decir:**
> "La query que ejecuta el backend en modo vulnerable es directamente:
> SELECT * FROM User WHERE email = '[INPUT]'
> Al meter ' OR '1'='1'--, la condición siempre es verdadera y el servidor devuelve el primer usuario de la base de datos, que es el admin. Sin saber la contraseña, tenemos acceso completo."

**Qué NO decir:** no entres en detalles técnicos de PostgreSQL que no domines.

### 3.2 SQL Injection — Exfiltración UNION (1 min)

**Qué decir:**
> "Con un UNION SELECT podemos extraer columnas de otras tablas. Aquí vemos cómo se extraen los emails y contraseñas de todos los usuarios. En el modo vulnerable, las contraseñas están en texto plano."

### 3.3 Fuerza Bruta (1.5 min)

**Qué decir:**
> "El ataque de diccionario prueba contraseñas una por una. En modo v1 no hay rate limiting, así que el atacante puede probar miles de combinaciones sin ser bloqueado. La consola usa un diccionario tipo rockyou y en la posición 9 encuentra la contraseña correcta."

### 3.4 IDOR — Acceso a datos ajenos (1 min)

**Qué decir:**
> "IDOR significa Insecure Direct Object Reference. En esta demo accedemos al ticket #1024 que pertenece a MAPFRE, siendo un usuario diferente. El backend no verifica si el usuario autenticado es el propietario del recurso. Vemos datos sensibles: IP del servidor y contraseña de acceso."

### 3.5 DoS — Inundación HTTP (0.5 min)

**Qué decir:**
> "Lanzamos 200 peticiones simultáneas contra la API. En modo vulnerable no hay rate limiting global, por lo que todas pasan. Esto simula un ataque de denegación de servicio que podría saturar el servidor."

---

## BLOQUE 4 — CONTRAMEDIDAS MODO SEGURO (3 min)

**Qué decir:**
> "Ahora cambiamos APP_MODE=secure y ejecutamos los mismos ataques:"

| Ataque | Resultado en modo vulnerable | Resultado en modo seguro |
|--------|------------------------------|--------------------------|
| SQLi bypass | ✓ ÉXITO — acceso sin contraseña | ✗ BLOQUEADO — Prisma parametriza |
| Fuerza bruta | ✓ CRACKED en intento 9 | ✗ HTTP 429 tras 5 intentos |
| IDOR | ✓ Datos ajenos expuestos | ✗ 403 — verificación de propietario |
| Contraseñas | ✓ Texto plano extraíble | ✗ Bcrypt $2b$12$ irreversible |

**Demo Bcrypt — muy impactante:**
> "En modo seguro, si ejecutamos el mismo UNION SELECT, las contraseñas que vemos son hashes Bcrypt: $2b$12$... Es computacionalmente imposible revertirlos en tiempo razonable. Son 2^12 = 4096 rondas de hash."

---

## BLOQUE 5 — SOC + GRAFANA + N8N (3 min)

### SOC Monitor en vivo

**Qué decir:**
> "El panel SOC se actualiza solo cada 8 segundos. Cuando el backend detecta un patrón de ataque —SQLi, fuerza bruta, path traversal— registra el evento con IP origen, tipo de ataque y si fue bloqueado o no. Los analistas ven esto en tiempo real sin refrescar."

**Mostrar:** tarjetas con total de eventos, ataques bloqueados, sesiones activas.

### Grafana

**Qué decir:**
> "Prometheus recoge métricas del backend vía el endpoint /metrics. La métrica principal es sofia_login_attempts_total etiquetada por modo y resultado. En Grafana vemos el pico de intentos fallidos justo cuando ejecutamos el ataque de fuerza bruta. Esto es lo que un analista SOC real usaría para investigar un incidente."

### n8n — Alertas SOS

**Qué decir:**
> "Cuando un cliente pulsa el botón SOS de emergencia, el frontend llama a la API, que dispara un webhook a n8n. n8n tiene un flujo configurado que procesa la alerta y envía un email al SOC. El correo llega a ewsophie333@gmail.com con el nombre del cliente y el nivel de urgencia."

**Mostrar:** el workflow de n8n con los nodos Webhook → Set → Send Email.

---

## BLOQUE 6 — CONCLUSIONES (2 min)

**Qué decir:**
> "Lo que más valoro de este proyecto es que conecta teoría y práctica de forma directa. Puedes ver los ataques OWASP que estudiamos en el módulo de Seguridad ejecutarse en un sistema real, y luego ver cómo las contramedidas los bloquean. 
> TypeScript fue el mayor descubrimiento: al principio parecía trabajo extra, pero el compilador me ahorraba horas de debugging.
> Si tuviera que continuar, añadiría detección de anomalías con ML y un mapa geográfico de ataques con GeoIP."

---

## PREGUNTAS DEL TRIBUNAL — CON RESPUESTAS

### Sobre la arquitectura

**P: ¿Por qué usaste Docker Compose en lugar de un VPS directo?**
> "Docker Compose me da portabilidad total. El mismo entorno funciona en mi portátil, en Codespaces y en cualquier servidor Linux. Con un VPS habría tenido que instalar y configurar todo manualmente, y replicarlo habría sido mucho más difícil. Con Docker, todo está declarado en el docker-compose.yml y cualquiera puede levantar el proyecto con un solo comando."

**P: ¿Cómo se comunican los contenedores entre sí?**
> "Están todos en la misma red Docker bridge llamada sofia-network. Se comunican por nombre de servicio: el backend llama a 'postgres' en el puerto 5432, no a una IP. Docker resuelve internamente el DNS. PostgreSQL no expone ningún puerto al exterior en producción, solo es accesible desde dentro de esa red."

**P: ¿Qué pasa si el contenedor de postgres se cae?**
> "Los datos persisten en un volumen Docker llamado postgres-data. Si el contenedor se cae y lo reinicio, los datos siguen ahí. El volumen está montado en /var/lib/postgresql/data dentro del contenedor. Para un entorno de producción real añadiría backups automáticos con pg_dump a un bucket S3."

**P: ¿Cómo funciona el túnel serveo.net?**
> "El servicio tunnel es un contenedor Alpine con openssh-client. Establece un reverse SSH tunnel hacia serveo.net: cualquier petición que llega a sofia-solutions.serveousercontent.com se redirige al frontend en el puerto 80 dentro de la red Docker. Serveo proporciona el certificado SSL/TLS automáticamente, así que toda la comunicación exterior va cifrada."

---

### Sobre la base de datos

**P: ¿Por qué elegiste PostgreSQL y no MySQL?**
> "PostgreSQL es 100% open source sin licencia dual como MySQL. Tiene mejor soporte para tipos de datos avanzados y la integración con Prisma es excelente. Además, para el modo vulnerable necesitaba ejecutar queries raw con $queryRawUnsafe, que Prisma soporta en PostgreSQL de forma nativa."

**P: ¿Qué es Prisma y qué ventaja aporta?**
> "Prisma es un ORM para Node.js que genera un cliente TypeScript tipado a partir del schema de la base de datos. Eso significa que si intento hacer una query a un campo que no existe, TypeScript me avisa en tiempo de compilación, no en tiempo de ejecución. En modo seguro, todas las queries pasan por Prisma y son automáticamente parametrizadas, lo que elimina el SQLi."

**P: Explica el esquema E-R de tu base de datos.**
> "Tengo cinco entidades principales: User (autenticación y roles), Client (empresas cliente), Ticket (incidencias de seguridad), SecurityEvent (log de ataques detectados) y Service (catálogo de servicios IT). Un Client puede tener muchos Tickets. Un User puede tener muchos Tickets asignados. Los SecurityEvents los genera el middleware de detección automáticamente."

**P: ¿Cómo funciona la detección de SQL Injection en el middleware?**
> "El middleware attackDetection analiza el path, query params y body de cada petición buscando patrones regex como comillas simples, UNION SELECT, OR 1=1, SLEEP(), etc. Si detecta un patrón y estamos en modo seguro, bloquea la petición con un 403 y registra el evento como SecurityEvent en la base de datos. En modo vulnerable, deja pasar la petición pero igual la registra para que aparezca en el SOC."

---

### Sobre la seguridad

**P: Explica cómo funciona Bcrypt.**
> "Bcrypt es un algoritmo de hash diseñado específicamente para contraseñas. Tiene un 'cost factor' —en mi caso 12— que determina cuántas rondas de procesamiento hace: 2^12 = 4096 rondas. Cada vez que hasheas la misma contraseña, el resultado es diferente porque usa un salt aleatorio. Esto hace que los rainbow tables sean inútiles. En modo vulnerable guardo las contraseñas en texto plano para poder hacer la demo comparativa."

**P: ¿Qué es JWT y cómo lo implementaste?**
> "JSON Web Token es un estándar para transmitir información de autenticación de forma compacta. Tengo dos tipos: accessToken con expiración de 15 minutos y refreshToken con 7 días. Cuando el usuario hace login, el backend firma el token con una clave secreta definida en JWT_SECRET. El frontend guarda el token y lo envía en cada petición al backend en la cabecera Authorization. El middleware requireAuth verifica la firma y extrae el rol del usuario."

**P: ¿Cómo implementaste el aislamiento de sesiones entre admin y cliente?**
> "Uso cookies HTTP-only con sufijos según el rol: accessToken_ADMIN y accessToken_CLIENT. Así, si estás logado como admin y cliente a la vez en distintas pestañas, las cookies no se sobreescriben. El middleware requireAuth es inteligente: si la ruta es /admin/*, busca primero accessToken_ADMIN; si es /dashboard/*, busca accessToken_CLIENT. Esto es importante para la demo porque puedo mostrar ambas sesiones activas simultáneamente."

**P: ¿Qué es un ataque IDOR?**
> "IDOR, Insecure Direct Object Reference, ocurre cuando una aplicación usa identificadores predecibles (como IDs numéricos 1, 2, 3...) para acceder a recursos, sin verificar que el usuario autenticado sea el propietario de ese recurso. En mi demo, accedo al ticket #1024 siendo un usuario diferente al propietario. En modo seguro, el middleware verifica que customer.userId === req.user.id antes de devolver el recurso."

**P: ¿Cómo funciona el rate limiting?**
> "Uso el paquete express-rate-limit. En modo seguro, configuro máximo 5 intentos de login por IP en una ventana de 15 minutos. Si se supera, el servidor devuelve HTTP 429 Too Many Requests. El middleware de fuerza bruta también dispara un webhook a n8n para notificar al SOC. En modo vulnerable, ese middleware no está aplicado a la ruta /api/v1/auth/login, lo que permite el ataque de diccionario."

---

### Sobre el frontend y tecnologías

**P: ¿Por qué PHP para el frontend?**
> "PHP con Apache es lo que aprendemos en el módulo de Implantación de Aplicaciones Web. Es fácil de dockerizar con la imagen oficial php:apache. Para el panel de admin y el SOC no necesitaba un framework reactivo complejo; PHP con server-side rendering es más que suficiente y más ligero. Para la parte de métricas en tiempo real usé JavaScript puro con fetch polling cada 8 segundos."

**P: ¿Qué es el diseño Glassmorphism/Cyberpunk que mencionas?**
> "Es un estilo visual moderno que usa efectos de cristal translúcido con blur, colores neón sobre fondo oscuro y bordes luminosos. Lo implementé con CSS puro usando backdrop-filter: blur(), gradientes RGBA semitransparentes y sombras de texto con text-shadow en colores cyan y violeta. No usé ningún framework CSS como Tailwind; todo el CSS es custom."

**P: ¿Cómo funciona el chat flotante?**
> "El chat flotante es un widget PHP que incluyo en todas las páginas. Al abrirlo, el usuario puede escribir mensajes que se envían a la API del backend. En la versión actual es un chatbot básico con respuestas predefinidas para preguntas frecuentes sobre los planes de servicio y soporte técnico. La ampliación sería conectarlo a un LLM vía API."

**P: ¿Qué es Prometheus y cómo lo usaste?**
> "Prometheus es un sistema de monitorización que recoge métricas en formato key-value. En mi backend añadí el paquete prom-client para exponer el endpoint /metrics. La métrica principal es sofia_login_attempts_total con labels mode (secure/vulnerable) y result (success/failed). Prometheus hace scraping de ese endpoint cada 15 segundos y guarda el historial. Grafana luego consulta Prometheus con queries PromQL para visualizar las gráficas."

---

### Sobre el despliegue

**P: ¿Cómo lo desplegarías en producción real?**
> "Para producción real sustituiría el túnel serveo.net por un dominio propio con certificado Let's Encrypt gestionado por Nginx como reverse proxy. Los secretos como JWT_SECRET y contraseñas de BBDD irían en variables de entorno o un gestor de secretos como HashiCorp Vault. Añadiría también un sistema de backups automáticos de PostgreSQL y alertas de uptime."

**P: ¿Cómo funciona GitHub Codespaces con tu proyecto?**
> "Codespaces crea un contenedor de desarrollo en la nube basado en el .devcontainer del repositorio. Ahí tengo configurado Docker-in-Docker para poder ejecutar Docker Compose dentro del propio Codespace. Los puertos 8000 y 8001 se marcan como Public para que sean accesibles desde internet. Así puedo hacer la demo desde el navegador sin instalar nada localmente."

**P: ¿Cuánto costaría desplegar esto en producción?**
> "Con un VPS tipo Hetzner CX21 (2 vCPU, 4GB RAM) son unos 5,83€/mes. El dominio .com son unos 11€/año. SSL con Let's Encrypt es gratuito. En total, menos de 10€/mes para un entorno completamente funcional. Comparado con Splunk SIEM que cuesta entre 15.000 y 40.000€/año solo en licencias, la diferencia es enorme."

---

### Preguntas trampa — las más difíciles

**P: ¿Por qué las vulnerabilidades que demuestras no son las del sistema real, sino simuladas?**
> "Las vulnerabilidades son reales pero están implementadas de forma controlada e intencionada solo en el modo vulnerable. Es como un campo de tiro: existe para practicar de forma segura. El sistema de doble modo con APP_MODE garantiza que nunca se active una vulnerabilidad por error en producción. En un entorno real de pentesting, se usaría una instancia dedicada en red aislada."

**P: ¿Tiene tu sistema vulnerabilidades que no controlaste?**
> "Probablemente sí. Ningún sistema es 100% seguro. Por ejemplo, no implementé protección CSRF completa en el frontend PHP. El chat flotante podría ser vulnerable a XSS si no se escapa correctamente toda la entrada. La gestión de sesiones PHP podría mejorarse con tokens de sesión rotativos. Estas son mejoras que formarían parte de una auditoría de seguridad real del propio sistema."

**P: Si un atacante comprometiera el frontend PHP, ¿podría acceder a la base de datos?**
> "En la configuración de producción, no directamente. PostgreSQL solo es accesible desde la red interna Docker sofia-network. El frontend PHP no tiene credenciales de base de datos; solo se comunica con el backend a través de la API REST en el puerto 8001. El backend sí tiene acceso a PostgreSQL, pero el atacante tendría que comprometer también el contenedor de backend. Esta arquitectura de capas reduce la superficie de ataque."

**P: ¿Cómo se diferencia tu proyecto de una instalación de Metasploitable o DVWA?**
> "DVWA y Metasploitable son herramientas puramente educativas sin valor empresarial real. Sofia Solutions es una plataforma funcional que podría usarse en una empresa real: tiene gestión de clientes, facturación, tickets de soporte y SOC. Las vulnerabilidades son una capa adicional para hacer demos comparativas. Es como comparar un simulador de vuelo de juguete con un avión real que también tiene modo simulación."

---

## TERMINOLOGÍA CLAVE PARA EL TRIBUNAL

| Término | Definición en 1 frase |
|---------|----------------------|
| **SOC** | Centro de Operaciones de Seguridad: equipo que monitoriza amenazas 24/7 |
| **OWASP Top 10** | Lista de las 10 vulnerabilidades más críticas en aplicaciones web |
| **SQLi** | SQL Injection: inserción de código SQL en campos de entrada para manipular la BD |
| **IDOR** | Acceso a recursos ajenos por IDs predecibles sin verificar propietario |
| **Bcrypt** | Algoritmo de hash para contraseñas con salt y cost factor configurable |
| **JWT** | Token firmado digitalmente para autenticar peticiones sin sesión en servidor |
| **Rate Limiting** | Límite de peticiones por IP/tiempo para prevenir fuerza bruta |
| **Prisma ORM** | Capa de abstracción sobre la BD que genera queries tipadas y parametrizadas |
| **Docker Compose** | Herramienta para definir y ejecutar aplicaciones multi-contenedor |
| **Reverse Tunnel** | Conexión SSH que expone un servicio local como si fuera público |
| **Prometheus** | Sistema de monitorización que recoge métricas en formato time-series |
| **Webhook n8n** | URL que recibe peticiones POST para disparar automatizaciones |
| **CSP** | Content Security Policy: cabecera HTTP que restringe qué scripts puede ejecutar el navegador |
| **CAPTCHA** | Prueba para distinguir humanos de bots en formularios |
| **connection pooling** | Reutilización de conexiones a la BD en lugar de crear una nueva por petición |

---

## CHECKLIST PRE-DEFENSA

- [ ] `docker compose up -d` ejecutado y todos los servicios en verde
- [ ] Verificar http://localhost:8000 carga correctamente
- [ ] Login admin funciona: admin@sofia.local / S0f1a_Secur3!_2026
- [ ] Panel SOC carga en /admin/security-monitor
- [ ] Consola de auditoría carga en /consola
- [ ] SQLi bypass funciona (ataque visible en terminal)
- [ ] Brute force completa sin mostrar contraseña real
- [ ] Grafana activo en localhost:3000 con métricas visibles
- [ ] n8n activo en localhost:5678 con workflow de alertas visible
- [ ] APP_MODE=vulnerable para la demo de ataques
- [ ] APP_MODE=secure para la demo de contramedidas
- [ ] Pendrive USB con código fuente y memoria preparado
- [ ] Presentación PRESENTACION_SOFIA.html funcional

---

## COMANDO RÁPIDO DE INICIO PARA LA DEFENSA

```bash
# 1. Modo vulnerable (para demos de ataque)
APP_MODE=vulnerable docker compose up -d

# 2. Cambiar a modo seguro (para demos de contramedidas)
docker compose stop backend
APP_MODE=secure docker compose up -d backend

# 3. Ver logs en tiempo real durante la demo
docker compose logs -f backend

# 4. Ver estado de todos los servicios
docker compose ps
```

---

*Documento generado: Mayo 2026 | Sofia Solutions TFG ASIR*
