# Informe de seguridad

## Objetivo

Este informe resume qué vulnerabilidades se introducen de forma intencional en la versión académica vulnerable y cómo se corrigen o mitigan en la versión segura.

El objetivo no es construir una aplicación insegura por error, sino disponer de un entorno controlado para:

- demostrar fallos comunes;
- observar su impacto;
- comparar su corrección;
- relacionar seguridad, monitorización y respuesta.

## Enfoque dual

El proyecto mantiene dos familias de endpoints:

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

Ambos flujos comparten el mismo dominio funcional, pero no los mismos controles.

## Vulnerabilidades intencionales

## 1. Autenticación más débil

En la versión vulnerable se mantiene un comportamiento de autenticación más laxo para poder demostrar:

- hashing débil;
- validación menos rigurosa;
- bypass académico en escenarios concretos.

En la versión segura:

- se endurece el hashing;
- se reduce la tolerancia a entradas manipuladas;
- desaparecen las ramas de bypass didáctico.

## 2. Rate limiting insuficiente

La versión vulnerable permite ataques repetitivos con mayor facilidad, lo que facilita:

- brute force;
- credential stuffing académico;
- automatización intensiva.

La versión segura aplica limitación de intentos.

## 3. Gestión de sesión menos protegida

La versión vulnerable usa una política de sesión más expuesta para poder explicar:

- riesgo de robo o reutilización;
- diferencia entre almacenamiento accesible desde cliente y almacenamiento endurecido.

La versión segura:

- endurece cookies;
- reduce exposición del token;
- mejora el control de sesión.

## 4. Validación de pagos insuficiente

En el flujo vulnerable el valor monetario puede depender del cliente.

En el flujo seguro:

- el importe se recalcula o valida desde el servidor;
- el cliente no decide el precio final.

## 5. IDS pasivo frente a IDS preventivo

En el flujo vulnerable:

- se detectan patrones;
- pero no siempre se bloquea la petición.

En el flujo seguro:

- se detecta;
- se registra;
- se bloquea;
- se deja evidencia para SOC y Grafana.

## Tabla comparativa

| Dimensión | Vulnerable | Seguro |
|---|---|---|
| SQLi/XSS/traversal | Puede dejar pasar | Bloquea |
| Fuerza bruta | Más fácil | Limitada |
| Hashing | Débil | Endurecido |
| Sesión | Menos protegida | Más protegida |
| Pago | Riesgo de manipulación | Validación de servidor |
| Evidencia | Parcial | Completa |

## Cómo demostrarlo

Secuencia práctica:

1. abrir `http://localhost:8000/login`;
2. abrir `http://localhost:8000/login-secure`;
3. lanzar scripts de ataque;
4. comparar códigos HTTP;
5. revisar el SOC en `http://localhost:8000/admin/security-monitor`;
6. revisar Grafana en `http://localhost:3000`.

## Conclusión

La principal enseñanza de este informe es que la seguridad no depende de un solo mecanismo. La diferencia entre el modo vulnerable y el seguro aparece en varias capas:

- autenticación;
- validación;
- detección;
- persistencia;
- observabilidad.
