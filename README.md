# Sofia Solutions

Monorepo del proyecto final con dos aplicaciones:

- [frontend/](C:/Users/sgomez/Desktop/sofia-solutions/frontend): React + TypeScript, version editable de la web y panel principal 2026.
- [sofia-backend/](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend): Express + TypeScript + Prisma con modos `vulnerable` y `secure`.

## Puertos

- Frontend: `http://localhost:8000`
- Panel: `http://localhost:8000/dashboard`
- Backend: `http://localhost:8001`
- Swagger: `http://localhost:8001/docs`
- Metricas: `http://localhost:8001/metrics`

## Scripts raiz

- `npm run dev`
- `npm run dev:vuln`
- `npm run dev:secure`
- `npm run dev:frontend`
- `npm run dev:backend`
- `npm run dev:backend:vuln`
- `npm run dev:backend:secure`
- `npm run build:frontend`
- `npm run build:backend`
- `npm run build`
- `npm run docker:up`
- `npm run docker:down`
- `npm run docker:logs`

## Ataques automatizados

- `npm run attack:sqli:vuln`
- `npm run attack:sqli:secure`
- `npm run attack:xss:vuln`
- `npm run attack:xss:secure`
- `npm run attack:traversal:vuln`
- `npm run attack:traversal:secure`
- `npm run attack:payment:vuln`
- `npm run attack:payment:secure`
- `npm run attack:all:vuln`
- `npm run attack:all:secure`

## Docker

Docker ahora se puede lanzar directamente desde la raiz.

- Si quieres desarrollo rapido, usa npm para frontend y backend.
- Si quieres todo el stack listo, usa [docker-compose.yml](C:/Users/sgomez/Desktop/sofia-solutions/docker-compose.yml).

Modo por defecto en Docker: `secure`

Si quieres levantar Docker en modo vulnerable:

```bash
set APP_MODE=vulnerable
docker compose up -d --build
```

Ejemplo:

```bash
docker compose up -d
```

Eso levanta:

- `frontend` en `8000`
- `postgres` en `5432`
- `backend` en `8001`
- `prometheus` en `9090`

La documentacion detallada del backend queda en [sofia-backend/README.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/README.md).

Documentacion academica ampliada en formato APA:

- [DOCUMENTACION-APA.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/DOCUMENTACION-APA.md)
