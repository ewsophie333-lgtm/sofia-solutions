# Guía de Defensa: Ataques Manuales (Demostración de Experto)

Ejecutar ataques manualmente demuestra que tienes un dominio total de la ciberseguridad ofensiva. Aquí tienes los 3 más visuales.

---

### 1. SQL Injection Manual (Bypass de Autenticación por Comentario)
Este ataque es mucho más técnico: permite suplantar a cualquier usuario (incluido el administrador) sin saber su clave.
1. **Paso:** Ve a la página de Login (`/login`) en modo **vulnerable**.
2. **Acción:** En el campo de **Email**, escribe exactamente esto:
   `admin@sofia.local'--`
3. **Acción:** En el campo de contraseña, escribe cualquier palabra aleatoria.
4. **Resultado:** Entrarás directamente al dashboard como administrador.
5. **Explicación:** "No estoy usando un '1=1' genérico. Estoy cerrando la cadena de búsqueda del email con una comilla (`'`) y luego usando el operador de comentario de SQL (`--`). Esto hace que el motor de base de datos ignore el resto de la consulta original, que es la que verifica la contraseña. He forzado al sistema a que acepte al administrador sin validar sus credenciales."

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
