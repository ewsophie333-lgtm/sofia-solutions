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

- Frontend: `8000`
- Backend: `8001`
- PostgreSQL: `5432`
- Prometheus: `9090`

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

- `POST /api/auth/login`
- `POST /api/auth/register`
- `POST /api/auth/refresh`
- `POST /api/auth/logout`
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
