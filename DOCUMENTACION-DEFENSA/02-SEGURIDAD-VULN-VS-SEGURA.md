# Guía de Defensa: Seguridad (Vulnerable vs Segura)

Este es el punto donde más nota puedes sacar. Debes enfatizar el **antes** y el **después**.

### 1. Gestión de Contraseñas y Criptografía
- **Modo Vulnerable:** Texto Plano. Almacenamiento directo que permite la lectura inmediata de secretos en caso de fuga de datos.
- **Modo Seguro:** Hasheo mediante **BCrypt con Work Factor de 12**.
- **Detalle Técnico:** "No solo guardamos el hash; BCrypt añade un **Salt** aleatorio por cada usuario, lo que anula los ataques de Rainbow Tables (tablas precomputadas). El factor de coste 12 asegura que un ataque de fuerza bruta offline sea computacionalmente inviable."

### 2. Autenticación y SQL Injection
- **Explicación:** En el modo vulnerable, el login concatena strings directamente en la query SQL.
- **Defensa:** "En la versión segura, implemento **consultas parametrizadas**. El input del usuario nunca se ejecuta como código, sino que se trata como un simple dato, anulando cualquier intento de SQLi."

### 3. Rate Limiting y Fuerza Bruta
- **Explicación:** El modo seguro incluye un middleware que limita el número de intentos por IP.
- **Demostración:** "Si intento adivinar una contraseña 10 veces seguidas, el sistema me bloquea automáticamente con un error 429 (Too Many Requests)."
