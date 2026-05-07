# Guía de Defensa: Guion de Ataques (Demostración Práctica)

Sigue este orden para una demostración fluida y sin errores.

### Paso 1: El Ataque de Inyección SQL (Dramatismo)
1. **Acción:** Abre la Consola de Auditoría (`/consola`).
2. **Explicación:** "Voy a simular un atacante que no conoce ninguna contraseña. Selecciono el Login V1 y el payload de UNION Select."
3. **Resultado:** Se exfiltran los datos bancarios y contraseñas de todos los clientes.
4. **Cierre:** "Ahora cambio al Login V2 (Seguro) y repito el ataque. Como pueden ver, el sistema lo bloquea y no devuelve información sensible."

### Paso 2: El Ataque DDoS (Acción en vivo)
1. **Acción:** Abre el Admin Dashboard y muestra el panel de "Salud" en verde.
2. **Ejecución:** Lanza el script `scripts/sofia-dos.sh`.
3. **Resultado:** Mira cómo el dashboard se pone en rojo automáticamente indicando "CAÍDO / DDoS".
4. **Explicación:** "El sistema ha detectado una denegación de servicio por agotamiento de recursos."

### Paso 3: Post-Explotación (Persistencia)
1. **Acción:** Ejecuta `scripts/lanzar-rootkit.bat`.
2. **Explicación:** "Una vez que el atacante entra, su objetivo es quedarse. Aquí demuestro cómo un rootkit intentaría modificar el registro de Windows para asegurar su persistencia."
