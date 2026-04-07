# SECURITY-REPORT

## Vulnerabilidades intencionales

1. Hashing débil en `src/utils/hash.ts`
2. Falta de rate limit en `src/middleware/rateLimit.ts`
3. Cookies débiles en `src/controllers/auth.controller.ts`
4. Manipulación de importes en `src/controllers/payments.controller.ts`
5. IDS pasivo en `src/middleware/attackDetection.ts`

## Corrección en modo seguro

- bcrypt coste 12
- rate limit para login
- cookies `HttpOnly`, `Secure`, `SameSite=Strict`
- validación del precio desde base de datos
- bloqueo con `403`, métrica y notificación SOC
