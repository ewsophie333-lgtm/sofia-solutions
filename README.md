# Sofia Solutions

Monorepo del proyecto final con dos aplicaciones:

- [frontend/](C:/Users/sgomez/Desktop/sofia-solutions/frontend): landing corporativa basada en el build original del preview de Readdy, ajustada para ejecutarse en local con fidelidad visual alta.
- [sofia-backend/](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend): Express + TypeScript + Prisma con modos `vulnerable` y `secure`.

## Puertos

- Frontend: `http://localhost:8000`
- Login vulnerable: `http://localhost:8000/login`
- Login seguro: `http://localhost:8000/login-secure`
- Alias de compatibilidad: `http://localhost:8000/login/vulnerable`
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
- El frontend actual prioriza replica visual del preview original y no una reimplementacion por componentes.

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

## Estado actual del frontend

- la web publica en `8000` reproduce el preview original de Readdy
- el logo se sobreescribe localmente por un SVG transparente mas grande
- el backend y su documentacion siguen siendo editables y academicos
- el login en `/login` se ha reemplazado por una vista propia con modo seguro e inseguro
- el SOC monitor en `/admin/security-monitor` ya consume datos del backend en vez de arrays mock

## Verificacion visual

Actualmente existen **dos familias visuales** en el frontend:

- ` / `
  usa el bundle original del preview de Readdy para conservar la fidelidad visual casi exacta respecto a la referencia.
- `/login`, `/login-secure`, `/dashboard` y `/admin/security-monitor`
  usan pantallas React propias, conectadas al backend local y al flujo academico de seguridad.

Esto explica por que algunas pantallas se ven "demasiado parecidas" al original y otras muestran los cambios funcionales y de marca pedidos durante el desarrollo.

Si se quiere una experiencia completamente uniforme, hay dos caminos validos:

- migrar todas las pantallas del preview a componentes React editables
- o servir mas rutas directamente desde el bundle original y limitar los cambios a overrides de branding y datos

El estado actual prioriza:

- home con maxima fidelidad visual
- login dual real
- dashboard y SOC monitor conectados a la API local

## SOC realista

El backend ahora modela:

- clientes protegidos
- activos monitorizados
- incidentes correlacionados
- cartera de servicios operativos

Endpoint principal del monitor:

- `GET /api/admin/security-monitor`

Ese endpoint alimenta:

- KPIs de eventos, incidentes, salud y activos
- paises origen
- tendencia SIEM
- vectores de ataque
- feed de incidentes
- exposicion por cliente
- portfolio de servicios con SLA

## Endpoints de visualizacion

- Home: `http://localhost:8000/`
- Login vulnerable: `http://localhost:8000/login`
- Login seguro: `http://localhost:8000/login-secure`
- Dashboard: `http://localhost:8000/dashboard`
- SOC monitor: `http://localhost:8000/admin/security-monitor`
- API backend: `http://localhost:8001`
- Swagger: `http://localhost:8001/docs`
- Metricas: `http://localhost:8001/metrics`

## Documentacion de ataques y login

- [ATTACK-SCRIPTS.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/ATTACK-SCRIPTS.md)
- [SECURE-LOGIN-EXPLAINED.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/SECURE-LOGIN-EXPLAINED.md)

La documentacion detallada del backend queda en [sofia-backend/README.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/README.md).

Documentacion academica ampliada en formato APA:

- [DOCUMENTACION-APA.md](C:/Users/sgomez/Desktop/sofia-solutions/sofia-backend/DOCUMENTACION-APA.md)

