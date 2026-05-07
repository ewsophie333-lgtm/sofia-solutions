# Guía de Defensa: Ataques Manuales (Demostración de Experto)

Ejecutar ataques manualmente demuestra que tienes un dominio total de la ciberseguridad ofensiva. Aquí tienes los 3 más visuales.

---

### 1. SQL Injection Manual (Bypass de Login)
Este ataque sirve para entrar al sistema sin conocer ninguna contraseña.
1. **Paso:** Ve a la página de Login (`/login`).
2. **Acción:** En el campo de **Email**, escribe exactamente esto:
   `' OR '1'='1'--`
3. **Acción:** En el campo de contraseña, escribe cualquier cosa (ej: `123`).
4. **Explicación:** "Estoy inyectando una condición lógica que siempre es verdadera (`1=1`). El `--` comenta el resto de la query original, haciendo que la base de datos me deje pasar sin validar la contraseña real."

### 2. IDOR Manual (Robo de información por URL)
Este ataque demuestra falta de control de autorización en los objetos.
1. **Paso:** Inicia sesión como un usuario normal (ej: `mapfre@sofia.local`).
2. **Acción:** Una vez dentro del dashboard, fíjate en la URL. Si ves algo como `/tickets/1024`, cámbialo manualmente en la barra del navegador por:
   `/tickets/1` o `/tickets/999`
3. **Explicación:** "Aunque mi usuario solo debería ver sus propios tickets, el sistema no valida si el ID solicitado me pertenece. Simplemente cambiando el número en la URL, puedo acceder a información sensible de otros clientes o incluso del administrador."

### 4. XSS Manual (Robo de Cookies / Alerta)
Este ataque demuestra que el frontend no limpia los datos que recibe del usuario.
1. **Paso:** Ve a cualquier buscador o formulario que refleje tu nombre.
2. **Acción:** En lugar de un nombre, escribe:
   `<script>alert('Vulnerabilidad XSS Detectada - Sofia Solutions')</script>`
3. **Explicación:** "El servidor está confiando ciegamente en lo que escribo. Al no 'sanitizar' la entrada, el navegador ejecuta mi código JavaScript como si fuera parte legítima de la página. En un escenario real, podría usar esto para robar la cookie de sesión del administrador."

---

> [!IMPORTANT]
> **Para impresionar:** Realiza estos ataques primero en el entorno **vulnerable** (donde funcionarán) y luego intenta repetirlos en el entorno **seguro** (donde fallarán). Eso demuestra el valor real de tu implementación de seguridad.
