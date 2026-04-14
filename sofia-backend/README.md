# Sofia Backend

Backend academico de Sofia Solutions con dos modos de ejecucion:

- `APP_MODE=vulnerable`
- `APP_MODE=secure`

## Stack

- Node.js 20+
- Express + TypeScript
- Prisma + PostgreSQL 15
- JWT + bcryptjs / MD5
- Zod
- Helmet, CORS, cookies, rate limit
- Prometheus `/metrics`
- Winston
- Swagger `/docs`

## Puertos

- Frontend corporativo: `8000`
- Backend: `8001`
- PostgreSQL: `5432`
- Prometheus: `9090`

## Integracion con frontend

El frontend visible en `http://localhost:8000` se sirve desde el monorepo raiz. La home conserva el preview visual original, mientras que las rutas `/login` y `/login-secure` usan una interfaz React comun con dos implementaciones de autenticacion por debajo. El backend queda desacoplado y expone su API en `8001`, documentacion Swagger en `/docs` y metricas en `/metrics`.

La consecuencia directa es que hoy conviven dos tipos de pantalla:

- paginas servidas con fidelidad casi exacta desde el preview original
- paginas React locales conectadas a datos y seguridad reales

Esto es intencional en el estado actual del proyecto y debe explicarse en la defensa para evitar interpretarlo como una inconsistencia accidental.

## Login dual

Se han anadido dos rutas diferenciadas para demostracion:

- `POST /api/v2/auth/login`
- `POST /api/v1/auth/login`

La pantalla asociada queda en:

- `http://localhost:8000/login`
- `http://localhost:8000/login-secure`
- `http://localhost:8000/login/vulnerable`

El flujo vulnerable permite:

- bypass SQLi simulado
- validacion laxa
- ausencia de rate limit
- reflexion controlada de payload XSS para demostracion

El flujo seguro aplica:

- deteccion y bloqueo
- validacion estricta
- rate limit
- sesion menos expuesta

Documentacion adicional:

- [ATTACK-SCRIPTS.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/ATTACK-SCRIPTS.md)
- [SECURE-LOGIN-EXPLAINED.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/SECURE-LOGIN-EXPLAINED.md)

## Puesta en marcha

1. Opcional: copiar `.env.example` a `.env`
2. Si vas a usar base real, configurar `DATABASE_URL`
3. Ejecutar:

```bash
npm install
npm run prisma:generate
npm run prisma:migrate
npm run prisma:seed
npm run dev
```

Si no existe `.env`, el backend toma valores por defecto desde `.env.example`.

## Endpoints

- `POST /api/v1/auth/login`
- `POST /api/v1/auth/register`
- `POST /api/v1/auth/refresh`
- `POST /api/v1/auth/logout`
- `GET /api/v2/auth/csrf`
- `POST /api/v2/auth/login`
- `POST /api/v2/auth/register`
- `POST /api/v2/auth/refresh`
- `POST /api/v2/auth/logout`
- `GET /api/services`
- `GET /api/services/catalog`
- `GET /api/services/effectiveness`
- `GET /api/services/:id`
- `POST /api/services`
- `POST /api/payments/checkout`
- `GET /api/payments/history`
- `GET /api/tickets`
- `POST /api/tickets`
- `GET /api/tickets/:id/messages`
- `POST /api/tickets/:id/messages`
- `GET /api/admin/overview`
- `GET /api/admin/security-monitor`
- `GET /api/admin/security-events`
- `GET /metrics`

## Modelo SOC

El backend incluye ahora entidades para hacer el monitor mas creible:

- `Customer`
- `Asset`
- `Incident`

## Logica del catalogo de servicios

El catalogo ya no es una lista estatica. Cada servicio se conecta con:

- clientes (`Customer`)
- activos (`Asset`)
- incidentes (`Incident`)
- vectores de ataque cubiertos
- efectividad operativa

Esto permite justificar el valor academico del sistema:

- la capa de negocio define el servicio
- la capa operativa produce telemetria e incidentes
- la capa de seguridad demuestra que ataques cubre o mitiga cada servicio

Referencia ampliada:

- [SERVICE-ARCHITECTURE.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/SERVICE-ARCHITECTURE.md)

Uso practico:

- `Customer` representa tenants o clientes protegidos
- `Asset` representa firewall, VPN, WAF, aplicaciones y consolas EDR
- `Incident` representa detecciones correlacionadas con severidad, vector, estado, IP origen y pais origen

El endpoint `GET /api/admin/security-monitor` agrega esos datos y devuelve:

- resumen de eventos e incidentes
- top attacking countries
- tendencia por severidad
- top attack vectors
- incident timeline
- distribucion por superficie de ataque
- exposicion por cliente
- portfolio de servicios con categoria, tier y SLA

## Verificacion operativa

Pantallas:

- `http://localhost:8000/`
- `http://localhost:8000/login`
- `http://localhost:8000/login-secure`
- `http://localhost:8000/dashboard`
- `http://localhost:8000/admin/security-monitor`

API:

- `GET /health`
- `GET /api/admin/overview`
- `GET /api/admin/security-monitor`
- `GET /api/admin/security-events`


