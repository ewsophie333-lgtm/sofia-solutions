# 3. Análisis de requerimientos

## 3.1 Necesidades, requerimientos del cliente y análisis de la situación actual

El proyecto desarrollado para **Sofia Solutions** responde a una necesidad claramente orientada al ámbito de **ASIX**: desplegar, securizar, administrar y monitorizar una plataforma corporativa capaz de representar una empresa de servicios IT y ciberseguridad con una arquitectura realista.

La necesidad principal no era únicamente disponer de una página web corporativa, sino construir un entorno completo que permitiera demostrar:

- autenticación de usuarios con dos niveles de seguridad;
- exposición de servicios empresariales de forma coherente con la lógica del sistema;
- persistencia de datos en una base de datos relacional;
- monitorización de eventos y métricas de seguridad;
- ejecución controlada de ataques sobre una versión vulnerable;
- comparación directa entre una implementación vulnerable y una segura;
- despliegue reproducible mediante contenedores;
- automatización de tareas de administración y validación.

Desde el punto de vista funcional, el cliente necesita una plataforma unificada con cuatro áreas principales:

1. **Capa corporativa**  
   Para mostrar la marca, la oferta de servicios y los accesos a la plataforma.

2. **Capa operativa**  
   Para ofrecer un dashboard con visión de clientes, activos, tickets, incidentes y postura general del servicio.

3. **Capa de monitorización SOC**  
   Para representar la actividad de seguridad, los vectores de ataque, los eventos detectados y el estado del entorno.

4. **Capa de observabilidad técnica**  
   Para visualizar métricas reales en Grafana, alimentadas por la API y orientadas a la defensa y la administración del sistema.

La situación de partida era la inexistencia de una solución previa. No existía infraestructura desplegada, ni base de datos, ni API, ni lógica de negocio, ni monitorización, ni frontend operativo. El proyecto se inició, por tanto, desde una situación de **diseño en blanco**, apoyada únicamente en:

- una referencia visual inicial;
- unos requisitos funcionales definidos manualmente;
- y la necesidad académica de construir una solución que pudiera defenderse como proyecto de ASIX.

Desde el punto de vista técnico, esto implicó definir desde cero:

- la topología de servicios;
- el esquema de base de datos;
- la lógica de autenticación dual;
- los controles de seguridad;
- los scripts de ataque;
- los scripts de despliegue;
- la contenerización con Docker Compose;
- y la capa de visualización técnica con Grafana y un monitor SOC propio.

La plataforma debía además poder demostrar aspectos especialmente relevantes en ASIX:

- separación entre frontend y backend;
- puertos y comunicación entre servicios;
- contenedores enlazados en red interna;
- gestión de credenciales y variables de entorno;
- persistencia de la base de datos;
- exposición controlada de servicios al exterior;
- defensa frente a ataques web;
- y monitorización del comportamiento del sistema.

Este enfoque encaja directamente con varios módulos profesionales que se trabajan en **2º curso de ASIX** en España. En la orden curricular estatal del título aparecen, entre otros, los siguientes módulos especialmente relacionados con el proyecto:

- **Administración de sistemas operativos**
- **Servicios de red e Internet**
- **Implantación de aplicaciones web**
- **Administración de sistemas gestores de bases de datos**
- **Seguridad y alta disponibilidad**
- **Proyecto de administración de sistemas informáticos en red**
- además de los módulos de empleabilidad, inglés profesional y formación en empresa según el currículo más reciente

La relación con el proyecto es directa: la capa backend y su despliegue encajan con administración de servicios, la base de datos con administración de SGBD, Docker y la red entre contenedores con servicios de red, la autenticación dual y los ataques con seguridad y alta disponibilidad, y la integración global del sistema con el módulo de proyecto.

Por tanto, el análisis de necesidades del cliente se traduce en una solución que no solo debe “verse bien”, sino que debe ser **administrable, auditable, desplegable y defendible técnicamente**.

## 3.2 Estudio de alternativas de solución

Durante el análisis se valoraron varias alternativas, no solo desde un punto de vista visual o de programación, sino desde su utilidad real para un proyecto de administración de sistemas, redes y seguridad.

### Alternativa 1. Sitio web estático sin backend ni base de datos

La primera opción consistía en desarrollar únicamente una web corporativa estática con HTML, CSS y JavaScript, sin backend real, sin persistencia y sin monitorización. Esta posibilidad era rápida y simple, pero fue descartada porque no permitía justificar:

- gestión de servicios;
- autenticación;
- diferencias entre seguridad vulnerable y segura;
- pruebas de ataque;
- despliegue de servicios;
- observabilidad;
- ni administración de infraestructura.

Para un proyecto de ASIX, esta alternativa resultaba insuficiente.

### Alternativa 2. Aplicación web visual con datos simulados

También se valoró una aplicación con frontend completo y datos simulados, sin backend real. Habría permitido una maqueta convincente, pero sin lógica de negocio real ni defensa ante ataques. Se descartó porque la parte más importante del proyecto no es solo la interfaz, sino la infraestructura que hay detrás:

- API;
- base de datos;
- autenticación;
- control de accesos;
- métricas;
- logging;
- y validación de amenazas.

### Alternativa 3. Solución única sin separación entre modo vulnerable y seguro

Otra posibilidad era desarrollar un único flujo de autenticación y un único backend con controles parciales. Esta opción simplificaba el desarrollo, pero fue descartada porque anulaba uno de los objetivos clave del proyecto: demostrar de forma práctica cómo una misma necesidad funcional puede resolverse de forma insegura o segura.

La separación en dos modos permite justificar:

- qué vulnerabilidades existen;
- cómo se explotan;
- cómo se bloquean;
- y qué medidas concretas producen esa mejora.

### Alternativa 4. Stack full JavaScript/TypeScript con frontend moderno

La solución inicialmente construida con React, Express, Prisma y PostgreSQL ofrecía una arquitectura moderna, mantenible y válida desde el punto de vista técnico. Sin embargo, para alinear mejor el proyecto con el enfoque de ASIX, se decidió evolucionar la capa visible hacia un frontend servido por Apache/PHP, manteniendo la API y la lógica de seguridad en el backend desacoplado.

Esta decisión permite conservar la parte fuerte del proyecto:

- backend con seguridad y observabilidad real;
- scripts de ataque;
- modelo de datos;
- Docker Compose;
- monitorización;

y, al mismo tiempo, acercar la capa visible a tecnologías vistas en clase, sin alterar la arquitectura principal.

### Alternativa elegida

La solución final elegida se basa en:

- **frontend servido por Apache/PHP** como capa visible;
- **backend Express + TypeScript** como servicio de negocio y seguridad;
- **PostgreSQL + Prisma** como capa de persistencia;
- **Docker Compose** como sistema de despliegue;
- **Grafana** como panel técnico de visualización;
- **SOC corporativo propio** como vista operativa orientada a negocio;
- **scripts de automatización** para levantar el entorno y ejecutar ataques en Windows y Linux.

### Razones de la elección

Esta alternativa fue seleccionada porque equilibra:

- realismo técnico;
- facilidad de despliegue;
- valor demostrativo;
- coherencia con ASIX;
- y claridad para la defensa oral.

Desde el punto de vista formativo, permite trabajar simultáneamente:

- redes y servicios;
- Docker y orquestación;
- seguridad ofensiva y defensiva;
- bases de datos;
- scripting;
- monitorización;
- y administración del entorno.

## 3.3 Elección, valoración económica y diseño de las posibles soluciones

La solución definitiva se apoya en una arquitectura de servicios desacoplados desplegados en contenedores. El sistema se divide en:

- servicio web visible;
- servicio backend;
- base de datos PostgreSQL;
- fuente de métricas;
- capa de visualización mediante Grafana.

### Valoración económica

Al tratarse de un proyecto académico, la implantación se ha realizado sobre entorno local y software libre, por lo que el coste directo de licencias es nulo. No obstante, para realizar una valoración realista, se puede estimar el siguiente escenario:

#### Costes de software

- Apache/PHP: **0 EUR**
- Node.js / Express / TypeScript: **0 EUR**
- PostgreSQL / Prisma: **0 EUR**
- Docker / Docker Compose: **0 EUR**
- Grafana: **0 EUR**
- librerías auxiliares de validación, logging y seguridad: **0 EUR**

#### Costes de infraestructura estimados

En un escenario real de pequeña empresa o entorno piloto:

- VPS o servidor principal: **20 a 40 EUR/mes**
- base de datos o nodo adicional: **15 a 30 EUR/mes**
- almacenamiento y retención de logs: **10 a 25 EUR/mes**
- dominio y certificados: **10 a 20 EUR/año**

#### Coste de implantación técnica

Si la solución se valorase como servicio profesional, el coste no estaría solo en el desarrollo web, sino en:

- análisis de arquitectura;
- despliegue de contenedores;
- modelado y administración de base de datos;
- endurecimiento y configuración de seguridad;
- scripts de automatización;
- pruebas de ataque y validación;
- documentación y operación inicial.

Una implantación funcional de este tipo podría situarse razonablemente entre **3.000 y 6.500 EUR**, dependiendo de si se incluye soporte posterior, automatizaciones adicionales y una integración real con sistemas de terceros.

### Diseño final de la solución

La arquitectura elegida se organiza en las siguientes capas:

1. **Capa de acceso web**  
   Servida por Apache/PHP, responsable de la interfaz visible y la navegación.

2. **Capa de aplicación y seguridad**  
   Implementada en Express + TypeScript, donde residen:
   - autenticación;
   - sesiones;
   - servicios;
   - tickets;
   - pagos simulados;
   - eventos de seguridad;
   - métricas;
   - y lógica del SOC.

3. **Capa de persistencia**  
   Basada en PostgreSQL, con Prisma como herramienta de modelado y acceso.

4. **Capa de observabilidad**  
   Formada por la exposición de métricas y su visualización en Grafana.

5. **Capa de automatización y validación**  
   Compuesta por scripts de despliegue y scripts de ataque para modo vulnerable y seguro.

Esta estructura permite justificar la solución no solo como aplicación web, sino como **plataforma de servicios desplegada y administrada**, que es el enfoque más coherente para ASIX.

# 4. Implementación

La implementación del proyecto se ha llevado a cabo desde una perspectiva de servicios, seguridad y administración, no únicamente de desarrollo web. La solución final integra red interna entre contenedores, autenticación dual, base de datos relacional, visualización de métricas y automatización multiplataforma.

## 4.1 Diagramas y esquemas

### Mapa funcional del sistema

La plataforma expone las siguientes rutas visibles:

- `/` → portal corporativo
- `/login` → acceso vulnerable
- `/login-secure` → acceso seguro
- `/dashboard` → panel operativo
- `/admin/security-monitor` → monitor SOC

Además, la infraestructura expone:

- `http://localhost:8001` → API backend
- `http://localhost:8001/docs` → documentación Swagger
- `http://localhost:3000` → Grafana

### Esquema de red y servicios

La solución se despliega mediante Docker Compose, de modo que cada servicio comparte una red interna privada:

- `frontend` → servicio web Apache/PHP
- `backend` → API Express/TypeScript
- `postgres` → base de datos relacional
- `grafana` → visualización externa de métricas

Desde el punto de vista de red:

- el usuario solo necesita acceder a `8000`, `8001` y `3000`;
- PostgreSQL queda como servicio persistente de datos;
- Grafana se utiliza como herramienta visual de apoyo técnico para la defensa del proyecto.

### Esquema de datos

La base de datos se ha diseñado para conectar negocio, operación y seguridad. Entre las entidades principales se encuentran:

- `User`
- `Service`
- `Customer`
- `Asset`
- `Incident`
- `Ticket`
- `TicketMessage`
- `Payment`
- `SecurityEvent`

Gracias a esta estructura, los servicios ofertados no son elementos decorativos, sino piezas con efecto real en:

- clientes protegidos;
- activos monitorizados;
- incidentes asociados;
- métricas de cobertura;
- y eficacia de defensa.

### Diagrama entidad-relación

![Diagrama entidad-relación de la base de datos](memoria/diagramas/base-datos-er.svg)

## 4.2 Herramientas de software

Las principales herramientas de software utilizadas han sido:

- **Apache/PHP** para la capa visible del sistema
- **Node.js 20+** para la ejecución del backend
- **Express.js** como API REST
- **TypeScript** para reforzar robustez y mantenibilidad
- **Prisma ORM** como herramienta de modelado de base de datos
- **PostgreSQL 15** como motor de persistencia
- **Docker y Docker Compose** para desplegar el entorno completo
- **Grafana** para visualización técnica
- **Prom-client** para exposición de métricas
- **Winston** para logging estructurado
- **Zod** para validación de entradas
- **Helmet**, **rate limiting**, **CORS** y **cookies seguras** para el refuerzo de la seguridad

Además, se han creado scripts de automatización específicos para:

- levantar el stack en Windows;
- levantar el stack en Linux;
- lanzar ataques en entorno vulnerable;
- validar el bloqueo en entorno seguro.

## 4.3 Herramientas de hardware

No ha sido necesario emplear hardware especializado, ya que la solución se ha diseñado para ejecutarse sobre un equipo de desarrollo estándar. Sin embargo, el proyecto sí se ha planteado con una mentalidad de infraestructura real, por lo que podría trasladarse a:

- un servidor Linux;
- una máquina virtual;
- un host con Docker;
- o un entorno de laboratorio con varias máquinas.

Desde el punto de vista de ASIX, el valor no está en el hardware concreto, sino en la capacidad de desplegar servicios, administrarlos y conectarlos de forma controlada.

## 4.4 Lenguajes

Los lenguajes utilizados en el proyecto son:

- **PHP** para la capa visible servida por Apache
- **HTML** para la estructura de páginas
- **CSS** para maquetación visual
- **JavaScript** para lógica en cliente y consumo de la API
- **TypeScript** para el backend y scripts avanzados
- **SQL relacional** a través de PostgreSQL y Prisma
- **YAML** para Docker Compose y configuración de Grafana
- **PowerShell y shell script** para automatización multiplataforma

Esto permite defender el proyecto tanto desde la óptica de desarrollo como desde la óptica de administración y scripting.

## 4.5 Codificación

La codificación se ha organizado por capas y responsabilidades:

### Capa web

Se ha desarrollado un frontend visible servido por Apache/PHP, encargado de:

- renderizar la portada;
- presentar el login;
- mostrar dashboard y SOC;
- consumir la API del backend;
- mantener una experiencia corporativa uniforme.

### Capa backend

El backend Express + TypeScript concentra:

- autenticación vulnerable y segura;
- servicios y catálogo;
- tickets y mensajes;
- pagos simulados;
- eventos de seguridad;
- métricas;
- datos para dashboard y SOC.

### Middleware y seguridad

La lógica crítica de seguridad reside en middleware y controladores específicos, donde se implementan:

- detección de patrones maliciosos;
- rate limiting;
- validación de entradas;
- gestión de cookies y JWT;
- logging;
- diferenciación entre modo vulnerable y modo seguro.

### Fragmento del login seguro

Este fragmento representa el uso del token CSRF y el envío autenticado al endpoint seguro:

![Captura del flujo de login seguro](memoria/diagramas/codigo-login-seguro.svg)

```js
const csrfResponse = await fetch('/api/v2/auth/csrf', {
  credentials: 'include'
});

const { csrfToken } = await csrfResponse.json();

const loginResponse = await fetch('/api/v2/auth/login', {
  method: 'POST',
  credentials: 'include',
  headers: {
    'Content-Type': 'application/json',
    'x-csrf-token': csrfToken
  },
  body: JSON.stringify({ email, password })
});
```

### Scripts relevantes

Una parte importante de la implementación para ASIX no está solo en la aplicación, sino en los scripts creados para operar el entorno:

- scripts de levantado del stack;
- scripts de ejecución de ataques;
- scripts de validación defensiva;
- seed de base de datos;
- utilidades de despliegue y reconstrucción.

Esto refuerza el componente de administración y automatización del proyecto.

### Ejemplos reales de scripts comentados

Para que la implementación no quede en una descripción abstracta, se han preparado scripts reales de operación y validación. Estos scripts están comentados en el proyecto para facilitar su lectura y justificar su uso dentro de la memoria.

#### Script de arranque en Windows

El siguiente ejemplo corresponde al arranque del entorno completo en PowerShell:

![Captura del script de arranque en PowerShell](memoria/diagramas/codigo-start-stack.svg)

```powershell
param(
  [ValidateSet("secure", "vulnerable")]
  [string]$Mode = "secure",
  [switch]$Rebuild
)

$ErrorActionPreference = "Stop"

# Define el modo de demostración del entorno completo.
$env:APP_MODE = $Mode

if ($Rebuild) {
  # Baja y reconstruye todos los servicios.
  docker compose down
  docker compose up -d --build
} else {
  # Arranque rápido reutilizando imágenes previas.
  docker compose up -d
}
```

Este script permite:

- cambiar entre modo seguro y vulnerable;
- reconstruir el entorno completo;
- simplificar el despliegue para defensa y pruebas.

#### Script de ataques en PowerShell

Para ejecutar la batería de pruebas ofensivas controladas se ha creado un script específico:

```powershell
param(
  [ValidateSet("vulnerable", "secure")]
  [string]$Mode = "vulnerable"
)

$ErrorActionPreference = "Stop"

# Lanza la batería de ataques académicos contra el modo indicado.
if ($Mode -eq "vulnerable") {
  npm run attack:sqli:vuln
  npm run attack:xss:vuln
  npm run attack:traversal:vuln
  npm run attack:payment:vuln
  npm run attack:bruteforce:vuln
} else {
  npm run attack:sqli:secure
  npm run attack:xss:secure
  npm run attack:traversal:secure
  npm run attack:payment:secure
  npm run attack:bruteforce:secure
}
```

Su función es ejecutar la misma matriz de ataques sobre ambas versiones para comparar:

- respuestas HTTP;
- diferencias funcionales;
- eventos generados;
- relación entre ataque, servicio y panel SOC.

#### Script de arranque equivalente en Linux

La automatización del despliegue también se implementó para sistemas Linux:

```sh
#!/usr/bin/env sh
set -eu

MODE="${1:-secure}"
REBUILD="${2:-}"

case "$MODE" in
  secure|vulnerable) ;;
  *)
    echo "Uso: ./scripts/start-stack.sh [secure|vulnerable] [--build]"
    exit 1
    ;;
esac

export APP_MODE="$MODE"

if [ "$REBUILD" = "--build" ]; then
  docker compose down
  docker compose up -d --build
else
  docker compose up -d
fi
```

#### Script equivalente en Linux

También se desarrolló una variante en shell script para entornos Linux:

```sh
#!/usr/bin/env sh
set -eu

# Ejecuta la batería de ataques en entorno vulnerable o seguro.
MODE="${1:-vulnerable}"

case "$MODE" in
  vulnerable)
    npm run attack:sqli:vuln
    npm run attack:xss:vuln
    npm run attack:traversal:vuln
    npm run attack:payment:vuln
    ;;
  secure)
    npm run attack:sqli:secure
    npm run attack:xss:secure
    npm run attack:traversal:secure
    npm run attack:payment:secure
    ;;
esac
```

Con esto se demuestra que la automatización del proyecto no depende de un único sistema operativo.

#### Script de ataques en Linux

Este script reproduce en Linux la misma lógica de pruebas ofensivas:

```sh
#!/usr/bin/env sh
set -eu

MODE="${1:-vulnerable}"

case "$MODE" in
  vulnerable)
    npm run attack:sqli:vuln
    npm run attack:xss:vuln
    npm run attack:traversal:vuln
    npm run attack:payment:vuln
    npm run attack:bruteforce:vuln
    npm run services:matrix:vuln
    ;;
  secure)
    npm run attack:sqli:secure
    npm run attack:xss:secure
    npm run attack:traversal:secure
    npm run attack:payment:secure
    npm run attack:bruteforce:secure
    npm run services:matrix:secure
    ;;
esac
```

#### Script de ataque al checkout

Uno de los ejemplos más importantes es el ataque al flujo de pago, donde se intenta manipular el importe enviado por el cliente:

```ts
const response = await fetch(`${baseUrl}/api/payments/checkout`, {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
    Authorization: `Bearer ${accessToken}`,
    "x-demo-mode": mode
  },
  body: JSON.stringify({
    serviceId: 1,
    amount: 1,
    currency: "EUR",
    last4: "4242",
    brand: "visa"
  })
});
```

Este fragmento se utiliza para demostrar que:

- en modo vulnerable, el backend puede aceptar el `amount` manipulado;
- en modo seguro, el precio debe validarse o recalcularse del lado servidor;
- el intento de manipulación puede vincularse a la capa de seguridad y a la visualización en el panel correspondiente.

#### Validación del precio en el backend seguro

![Captura de la validación del checkout seguro](memoria/diagramas/codigo-checkout-seguro.svg)

```ts
const service = await prisma.service.findUnique({
  where: { id: payload.serviceId }
});

if (!service) {
  throw new NotFoundError('Servicio no encontrado');
}

const safeAmount = service.price;
const manipulated = Number(payload.amount) !== Number(service.price);

if (manipulated) {
  await securityEventsService.register({
    type: 'payment_amount_tampering',
    severity: 'high',
    endpoint: '/api/payments/checkout',
    action: 'blocked'
  });
}
```

#### Extracto del archivo docker-compose.yml

La puesta en marcha del entorno depende de Docker Compose. Este fragmento resume los servicios principales:

```yaml
services:
  frontend:
    build:
      context: .
      dockerfile: frontend-php/Dockerfile
    ports:
      - "8000:80"
    depends_on:
      - backend

  backend:
    build:
      context: ./sofia-backend
      dockerfile: docker/Dockerfile
    environment:
      PORT: 8001
      DATABASE_URL: postgresql://postgres:postgres@postgres:5432/sofia_solutions
      APP_MODE: ${APP_MODE:-secure}
    ports:
      - "8001:8001"
    depends_on:
      - postgres

  postgres:
    image: postgres:15
    ports:
      - "5432:5432"

  grafana:
    image: grafana/grafana:11.1.0
    ports:
      - "3000:3000"
```

#### Justificación de los comentarios en el código

Dentro del proyecto, los fragmentos sensibles o menos evidentes se han comentado para que puedan entenderse rápidamente durante la defensa. Los comentarios no se han añadido como relleno, sino para explicar:

- la finalidad del script;
- la diferencia entre modo vulnerable y seguro;
- la intención de cada bloque de automatización;
- y el valor del código desde una perspectiva de administración y seguridad.

## 4.6 Administración

La plataforma se ha implementado con una visión clara de administración de servicios.

### Gestión de servicios

Cada servicio del entorno tiene una función concreta:

- el frontend sirve la capa visible;
- el backend centraliza la lógica;
- PostgreSQL almacena datos persistentes;
- Grafana presenta métricas;
- el monitor SOC presenta telemetría de negocio y seguridad.

### Usuarios y perfiles

La aplicación contempla perfiles autenticados, especialmente:

- administrador;
- cliente;
- operador o analista.

Los permisos dependen del rol y del endpoint consumido. Esto permite justificar:

- control de accesos;
- separación funcional;
- y visibilidad distinta según el tipo de usuario.

### Automatización administrativa

Se han añadido scripts para simplificar la operación del entorno en dos sistemas:

- **Windows**
- **Linux**

Esto es especialmente relevante en ASIX, ya que demuestra capacidad para:

- preparar entornos reproducibles;
- reducir errores manuales;
- y documentar procedimientos operativos claros.

## 4.7 Aspectos de seguridad

Este apartado es uno de los más importantes del proyecto.

### Prevención de ataques

La solución implementa dos comportamientos diferenciados:

- **modo vulnerable**
- **modo seguro**

En el modo vulnerable se permiten, con fines docentes, ataques como:

- inyección SQL;
- XSS;
- path traversal;
- fuerza bruta;
- manipulación de parámetros de pago;
- gestión insegura de sesiones y tokens.

Para demostrarlo se han desarrollado scripts que lanzan estas pruebas de forma automatizada. Esto permite observar:

- qué peticiones se aceptan en la versión vulnerable;
- cómo impactan sobre autenticación y lógica de negocio;
- y qué registros o efectos generan.

En el modo seguro se han implementado mecanismos como:

- validación estricta de entradas;
- bcrypt para contraseñas;
- tokens y cookies con medidas de protección;
- rate limiting;
- CSRF en flujo seguro;
- mensajes de error genéricos;
- detección y bloqueo de patrones maliciosos;
- logging y métricas asociadas a actividad sospechosa.

### Uso de scripts maliciosos y sentencias de prueba

Para que el proyecto tenga valor demostrativo real, no basta con afirmar que una aplicación es vulnerable o segura. Por ello se han creado scripts específicos para ejecutar pruebas de ataque controladas.

Estos scripts permiten:

- lanzar ataques sobre el login vulnerable;
- repetir el mismo ataque contra el login seguro;
- comparar respuestas;
- analizar bloqueos;
- visualizar actividad posterior en el SOC y en Grafana.

Esto da al proyecto un componente práctico muy fuerte, especialmente útil en defensa oral.

### Base de datos y seguridad

La base de datos juega un papel central no solo como almacenamiento, sino como componente de seguridad:

- persistencia de usuarios;
- almacenamiento de servicios, clientes, activos e incidentes;
- registro de eventos de seguridad;
- soporte para trazabilidad de actividad.

La correcta estructura relacional permite además mantener integridad y coherencia en el sistema.

### Docker y aislamiento

La contenerización también forma parte de la estrategia de seguridad y administración:

- cada servicio está encapsulado;
- la base de datos está separada del frontend;
- la red de contenedores queda controlada;
- el despliegue es repetible;
- y la reconstrucción del entorno es sencilla.

### Observabilidad

La parte visible de monitorización se apoya en dos capas:

- **SOC corporativo propio**, orientado a negocio y operación;
- **Grafana**, orientado a métricas y defensa técnica.

Grafana y el SOC son las herramientas utilizadas para visualizar el comportamiento del sistema.

### Continuidad operativa

Aunque el proyecto no implementa un sistema enterprise completo de backup y restore, sí deja una base sólida para continuidad operativa:

- base de datos persistente;
- seed reproducible;
- despliegue definido en Docker Compose;
- scripts de arranque;
- separación clara entre servicios;
- posibilidad de reconstrucción rápida del entorno.

En conjunto, la implementación está alineada con el enfoque de ASIX porque demuestra no solo desarrollo, sino también:

- administración;
- despliegue;
- securización;
- validación ofensiva;
- observabilidad;
- y operación de servicios.
