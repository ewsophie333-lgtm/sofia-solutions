# SECURITY-REPORT

## Resumen

Este proyecto mantiene dos comportamientos de seguridad en paralelo con finalidad academica:

- `v1` o flujo vulnerable
- `v2` o flujo seguro

Ambos comparten base de datos, dominio funcional y rutas equivalentes, pero aplican controles distintos para que la comparativa sea observable.

## Vulnerabilidades intencionales

1. Hashing debil en `src/utils/hash.ts`
   En modo vulnerable se permite un esquema de hashing debil para ilustrar el riesgo de credenciales expuestas y cracking offline.

2. Ausencia de rate limit en `src/middleware/rateLimit.ts`
   El login vulnerable no frena intentos repetidos y facilita demostraciones de brute force.

3. Cookies y sesion debiles en `src/controllers/auth.controller.ts`
   En modo vulnerable las cookies no endurecen el almacenamiento de sesion con los mismos flags que el modo seguro.

4. Validacion insuficiente de importes en `src/controllers/payments.controller.ts`
   El flujo vulnerable permite manipular valores que en modo seguro deben quedar controlados por el servidor.

5. IDS pasivo en `src/middleware/attackDetection.ts`
   En modo vulnerable determinados patrones se registran pero no se bloquean, para evidenciar la diferencia con un bloqueo preventivo.

6. Diferencias visibles solo en comportamiento
   `/login` y `/login-secure` intentan verse iguales. La diferencia se manifiesta en:
   - almacenamiento de token
   - CSRF
   - rate limit
   - mensajes de error
   - bloqueo de payloads

## Correccion en modo seguro

- `bcrypt` con coste 12
- rate limit de autenticacion
- cookies `HttpOnly`, `Secure` y `SameSite=Strict`
- validacion fuerte de password y CSRF
- validacion del precio desde base de datos
- bloqueo `403` ante payloads detectados
- registro y metricas de seguridad para SOC

## Endpoints comparativos

Vulnerable:

- `POST /api/v1/auth/login`
- `POST /api/v1/auth/register`
- `POST /api/v1/auth/refresh`
- `POST /api/v1/auth/logout`

Seguro:

- `GET /api/v2/auth/csrf`
- `POST /api/v2/auth/login`
- `POST /api/v2/auth/register`
- `POST /api/v2/auth/refresh`
- `POST /api/v2/auth/logout`

## Demostracion recomendada

1. Iniciar sesion por `/login` y comprobar el flujo vulnerable.
2. Repetir el mismo escenario en `/login-secure`.
3. Ejecutar los scripts de ataque.
4. Verificar diferencias en:
   - estado HTTP
   - cookies
   - `localStorage`
   - metricas
   - panel SOC
