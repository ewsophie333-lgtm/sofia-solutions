# 🛡️ Sofia Solutions - Security & SOC Monitoring

[![Codespaces](https://img.shields.io/static/v1?label=GitHub+Codespaces&message=Open&color=blue&logo=github)](https://github.com/codespaces/new?hide_repo_select=true&ref=entrega-final&repo=ewsophie333-lgtm/sofia-solutions)

Proyecto Final ASIR orientado a la demostración de arquitecturas de seguridad ofensiva y monitorización defensiva (SOC).

---

## 📂 Materiales del Proyecto (Guía para el Jurado y Evaluadores)

Para facilitar la evaluación del proyecto por parte de los miembros del tribunal y cualquier auditor externo, se proporciona acceso directo a toda la documentación de soporte, guías técnicas y material interactivo de verificación:

| Documento | Descripción | Enlace |
| :--- | :--- | :--- |
| **Guía de Verificación** | Guion y pautas discursivas detalladas paso a paso para la exposición oral | [Abrir Guía](./GUIA_MAESTRA_DEFENSA.html) |
| **Guía Completa (Técnica)** | Arquitectura interna, criptografía aplicada y funcionamiento del WAF | [Abrir Guía](./GUIA_TECNICA_COMPLETA.html) |
| **Guion de Ataques** | Procedimientos exactos y comandos para la explotación controlada | [Ver Guion](./GUION_DE_ATAQUES.html) |

---

## 🌐 Puntos de Acceso (Producción y Auditoría)

| Módulo | Acceso Público | Puerto Local |
|--------|----------------|--------------|
| **Presentaci&oacute;n Ejecutiva** | [Ver Diapositivas](./PRESENTACION_EJECUTIVA_SOFIA.html) | Estilo Consultor&iacute;a |
| **Presentaci&oacute;n SOC** | [Ver Diapositivas](./PRESENTACION_SOFIA.html) | Reveal.js Din&aacute;mico |
| **Plataforma Corporativa** | [Acceder](https://sofia-solutions.serveousercontent.com) | `8000` |
| **Consola de Auditoría** | [Acceder](https://sofia-solutions.serveousercontent.com/consola) | `/consola` |
| **Panel SOC (Admin)** | [Acceder](https://sofia-solutions.serveousercontent.com/admin) | `/admin` |
| **Métricas Técnicas (Grafana)** | [Acceder](https://sofia-solutions.serveousercontent.com/grafana/) | `3000` |
| **Webhooks (n8n)** | [Acceder](https://sofia-n8n.serveousercontent.com) | `5678` |

---

## 🔐 Credenciales Maestras (Simplificadas)


Para facilitar la demostración ante el tribunal, se han unificado todas las contraseñas:

| Rol | Usuario (Email) | Contraseña |
|-----|-----------------|------------|
| **Administrador SOC** | `admin@sofia.local` | `admin123` |
| **Cliente Corporativo** | `iberdrola@sofia.local` | `iberdrola123` |
| **Auditor / Hacking** | (No requiere cuenta - Inyección SQL) | `N/A` |

---

---

## 🗄️ Acceso a la Base de Datos y Herramientas

Para que el tribunal de evaluación pueda inspeccionar los registros de incidentes, usuarios y la estructura de datos, se exponen los servicios de almacenamiento e instrumentación en canales locales seguros.

### 🛑 🔒 Nota de Seguridad y Arquitectura (Justificación para la Defensa)
> [!IMPORTANT]
> **¿Por qué la Base de Datos y Prisma Studio están en `localhost` y no expuestos públicamente por un dominio?**
>
> 1. **Principio de Mínima Exposición (Reducción de Superficie de Ataque)**: Exponer el puerto de base de datos (`5432`) o un visualizador administrativo a un subdominio público en internet permitiría a bots automatizados realizar ataques de fuerza bruta al usuario `postgres` o explotar inyecciones y vulnerabilidades del motor.
> 2. **Docker Network Isolation**: La base de datos vive dentro de una red virtual privada y aislada de Docker (`sofia-solutions_default`). Solo el backend tiene comunicación interna (`postgres:5432`).
> 3. **Uso Administrativo Seguro**: En una implementación de nivel corporativo real de *Sofia Solutions*, el acceso a bases de datos e instrumentación se realiza exclusivamente a través de una **VPN corporativa** o **túneles seguros SSH** locales (Local Port Forwarding), simulados aquí mediante el mapeo selectivo a `localhost`.

---

### 1. 📊 Prisma Studio (Visualizador de Base de Datos Web Interactivo)
Prisma Studio es una interfaz gráfica web premium para explorar, filtrar e insertar datos en tiempo real en la base de datos de Sofia.

*   **Paso 1 (Arrancar el servicio)**: Dado que es una utilidad administrativa de desarrollo, no corre de manera pasiva para no consumir recursos del sistema. Para levantarla, abre una nueva terminal y ejecuta:
    ```bash
    cd sofia-backend
    npx prisma studio --port 5555
    ```
*   **Paso 2 (Acceder al servicio)**:
    *   **En Local**: Abre en tu navegador `http://localhost:5555`
    *   **En GitHub Codespaces**: Ve a la pestaña **Ports (Puertos)** abajo en el editor, localiza el puerto `5555`, haz clic derecho -> *Port Visibility* -> y cámbialo a **Public**. Luego haz clic en el icono del globo terráqueo para abrir el enlace seguro autogenerado por GitHub.
*   **Tablas Disponibles**: Permite auditar visualmente las tablas `Usuario` (para validar el hashing con Bcrypt), `Incidente` (el feed del SIEM) y `Ticket` (las solicitudes de soporte de los tenants).

### 2. 🐘 Acceso Directo a PostgreSQL (Base de Datos Relacional)
Si el jurado prefiere usar clientes externos profesionales de bases de datos como **DBeaver**, **pgAdmin** o **DataGrip**:
*   **Host/Servidor**: `localhost` (o `127.0.0.1`)
*   **Puerto**: `5432` *(Nota: Si usas Codespaces, recuerda poner el puerto 5432 en "Public" en la pestaña "Ports" de VS Code para que tu cliente local pueda redirigirse)*
*   **Usuario**: `postgres`
*   **Contraseña**: `postgres`
*   **Base de datos**: `sofia_solutions`
*   **URL de Conexión**: `postgresql://postgres:postgres@localhost:5432/sofia_solutions`

### 3. 📈 Observabilidad y Métricas (Grafana & Prometheus)
*   **Panel Grafana**: `http://localhost:3000` (puerto `3000`). Acceso anónimo de solo lectura preconfigurado para ver CPU, red e incidentes geolocalizados de forma interactiva.
*   **Prometheus Endpoint**: `http://localhost:9090` (puerto `9090`). Recolector de métricas de red.

### 4. 🔗 Automatización de Alertas (n8n Webhook Manager)
*   **Interfaz de n8n**: `http://localhost:5678` (puerto `5678`). Permite monitorizar en tiempo real el flujo de automatización y orquestación de incidentes que envía alertas al analista del SOC.

---

## 🚀 Guía de Despliegue en Codespaces

Sofia Solutions está optimizado para ejecutarse en la nube de GitHub en segundos:

1. Haz clic en el botón **"Open in Codespaces"** arriba.
2. Espera a que Docker termine de levantar los 7 contenedores (aprox. 1-2 min).
3. Aparecerá un aviso de puertos abiertos. **Asegúrate de poner todos los puertos en "Public"** (Click derecho sobre el puerto -> Port Visibility -> Public).
4. El sistema autogenerará el túnel de seguridad y podrás acceder a las URLs del README.

---

## ⚡ Manual de Auditoría y Verificación de Contramedidas (Para el Jurado y Evaluadores)

Este apartado sirve como una guía metodológica estructurada para que los miembros del tribunal, evaluadores y cualquier auditor técnico puedan replicar, auditar y validar de forma controlada el comportamiento de la plataforma *Sofia Solutions* en ambos estados operacionales (Vulnerable y Protegido).

---

### 🔴 Fase 1: Auditoría Ofensiva (Demostración de Brechas en Modo Vulnerable)

El objetivo de esta fase es simular un entorno empresarial desprovisto de protecciones perimetrales para evidenciar la gravedad de los vectores de ataque más explotados en la capa de aplicación (OWASP Top 10).

#### Paso 1.1: Activación del Estado Inseguro (Modo Vulnerable)
*   **Acción**: Ejecutar el script `scripts/ACTIVAR-MODO-VULNERABLE.bat` (o `ACTIVAR-MODO-VULNERABLE.sh` en sistemas basados en Linux).
*   **Efecto en el Backend**: Desactiva el Web Application Firewall (WAF) perimetral, deshabilita las firmas de saneamiento de datos y desactiva el Rate Limiting. El backend pasa a operar en modo transparente.

#### Paso 1.2: Explotación de Inyección SQL (SQLi) - Bypass de Login
*   **Procedimiento**:
    1. Acceda a la pantalla de inicio de sesión legítima en `/login` o utilice la **Consola de Auditoría Interactiva** en `/consola`.
    2. Introduzca el siguiente payload malicioso en el campo de **Correo Electrónico**:
       ```sql
       admin@sofia.local' OR 1=1 --
       ```
    3. Escriba cualquier texto en la contraseña y envíe el formulario.
*   **Resultado Técnico Esperado**: El backend ejecuta una query SQL no parametrizada contra la base de datos PostgreSQL. Debido a la falta de sanitización, el operador `' OR 1=1 --` rompe la lógica de la consulta haciendo que siempre devuelva un valor verdadero, autenticando al auditor como Administrador del sistema sin conocer la clave secreta.

#### Paso 1.3: Explotación de Secuestro de Sesión y Escalada (Burp Suite Simulation)
*   **Procedimiento**:
    1. Desde la Consola de Auditoría (`/consola`), ejecute el ataque interactivo y abra el simulador del proxy **Burp Suite**.
    2. Modifique la cookie de sesión para alternar el rol de un usuario ordinario (`CLIENT`) a un rol de administrador (`ADMIN`).
*   **Resultado Técnico Esperado**: Se demuestra el impacto de un secuestro de sesión y la escalada de privilegios a través de la manipulación de cookies desprotegidas en tránsito.

---

### 🟢 Fase 2: Auditoría Defensiva (Validación de Robustez en Modo Seguro)

El objetivo de esta fase es demostrar cómo la suite defensiva de *Sofia Solutions* neutraliza de forma automática los mismos ataques de la Fase 1, garantizando la integridad de los activos del tenant.

#### Paso 2.1: Activación del Estado Protegido (Modo Seguro)
*   **Acción**: Ejecutar el script `scripts/ACTIVAR-MODO-SEGURO.bat` (o `ACTIVAR-MODO-SEGURO.sh` en Linux).
*   **Efecto en el Backend**: Reactiva el escudo de firmas defensivas del WAF perimetral, impone el uso de hashing criptográfico Bcrypt con factor de costo `12` para claves, activa validaciones de JWT estrictas y habilita el limitador de tasa de peticiones.

#### Paso 2.2: Mitigación Activa de Inyección SQL (SQLi)
*   **Procedimiento**: Repita el inicio de sesión introduciendo el payload `' OR 1=1 --`.
*   **Resultado Técnico Esperado**: El WAF intercepta el tráfico HTTP entrante en la capa L7, detecta el patrón malicioso en los datos serializados mediante expresiones regulares optimizadas y aborta la petición de inmediato, respondiendo al cliente con un código HTTP `403 Forbidden` y bloqueando la intrusión.

#### Paso 2.3: Mitigación de Fuerza Bruta y Ataques de Diccionario (Rate Limiting)
*   **Procedimiento**: Realice más de 3 intentos de inicio de sesión con contraseñas incorrectas para el usuario `admin@sofia.local`.
*   **Resultado Técnico Esperado**: El middleware de Rate Limiting detecta el abuso de peticiones concurrentes por IP y bloquea la conexión del cliente de forma temporal, previniendo el compromiso de credenciales y la denegación de servicio (DoS).

---

### 🕵️‍♂️ Fase 3: Post-Explotación y Persistencia (Sofia Audit Framework)

Para enriquecer la evaluación técnica, el sistema incluye un framework didáctico de post-explotación en la consola del auditor.

*   **Herramienta**: El script `sofia-audit-framework.py` (ejecutable mediante `lanzar-rootkit.bat`) simula un vector de persistencia avanzado y exfiltración de metadatos de bajo ruido.
*   **Uso del Jurado**: Permite comprobar cómo, tras una intrusión controlada, un atacante intenta instalar puertas traseras (backdoors) y cómo el sistema monitoriza silenciosamente dichas actividades de persistencia para recolectar evidencias que posteriormente se inyectan en el feed del SOC.

---

### 📡 Fase 4: Telemetría SOC y Orquestación Automatizada (SOAR)

Una vez neutralizados o detectados los ataques, la plataforma traslada los metadatos forenses al cuadro de mando centralizado.

1.  **Monitorización Cartográfica**: Acceda al panel `/admin` (SOC). Podrá auditar cómo el mapa de incidentes en tiempo real geolocaliza al atacante (IP, ciudad y país de origen) correlacionando metadatos.
2.  **Automatización SOAR (n8n)**: Al detectarse incidentes de severidad crítica (e.g. ataques SQLi en modo seguro o alertas de persistencia del rootkit), un webhook seguro dispara un flujo automático en n8n, el cual notifica por correo electrónico SMTP enriquecido al analista de guardia con los detalles forenses del atacante.

---


## 🛡️ Arquitectura de Seguridad
Sofia Solutions implementa una defensa en profundidad:
- **Perímetro:** WAF Rules y Rate Limiting.
- **Lógica:** Validación de Schemas y Sanitización.
- **SOC:** Telemetría enriquecida con **IP/Geo** y respuesta ante incidentes automatizada.

---

## 🏗️ Stack Tecnológico
- **Frontend:** PHP 8.2 + Apache (Proxy Inverso).
- **Backend:** Node.js v20 + Express + Prisma ORM.
- **Base de Datos:** PostgreSQL 15.
- **Observabilidad:** Prometheus + Grafana + cAdvisor.
- **Automatización:** n8n Workflow Automation.
- **Túnel de Red:** Serveo SSL Tunneling.

---
© 2026 Sofia Solutions - Proyecto Final ASIR
