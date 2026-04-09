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
- `POST /api/services`
- `POST /api/payments/checkout`
- `GET /api/payments/history`
- `GET /api/tickets`
- `POST /api/tickets`
- `GET /api/tickets/:id/messages`
- `POST /api/tickets/:id/messages`
- `GET /api/admin/overview`
- `GET /api/admin/security-events`
- `GET /metrics`


