# Guía de Defensa: Seguridad (Vulnerable vs Segura)

Este es el punto donde más nota puedes sacar. Debes enfatizar el **antes** y el **después**.

### 1. Gestión de Contraseñas
- **Modo Vulnerable:** Texto Plano. Si alguien accede a la DB (vía SQLi), ve todo.
- **Modo Seguro:** Hasheo con **BCrypt (12 rounds)**.
- **Punto Clave:** "En el modo seguro, aunque la base de datos sea exfiltrada, la información del cliente es ilegible. Hemos pasado de una seguridad inexistente a un estándar bancario."

### 2. Autenticación y SQL Injection
- **Explicación:** En el modo vulnerable, el login concatena strings directamente en la query SQL.
- **Defensa:** "En la versión segura, implemento **consultas parametrizadas**. El input del usuario nunca se ejecuta como código, sino que se trata como un simple dato, anulando cualquier intento de SQLi."

### 3. Rate Limiting y Fuerza Bruta
- **Explicación:** El modo seguro incluye un middleware que limita el número de intentos por IP.
- **Demostración:** "Si intento adivinar una contraseña 10 veces seguidas, el sistema me bloquea automáticamente con un error 429 (Too Many Requests)."
