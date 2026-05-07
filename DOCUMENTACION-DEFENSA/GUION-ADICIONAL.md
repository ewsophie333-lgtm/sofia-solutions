# Guion de defensa del proyecto

## Objetivo del documento

Este archivo está pensado para preparar la defensa oral del proyecto. Incluye:

- una estructura recomendada para exponer;
- preguntas probables del tribunal o profesorado;
- respuestas modelo;
- argumentos técnicos para justificar decisiones de diseño;
- puntos débiles que conviene reconocer sin perder solidez.

## 1. Cómo presentar el proyecto en 2 minutos

Versión breve recomendada:

> Sofia Solutions es una plataforma académica orientada a ASIX que simula una empresa de servicios IT y ciberseguridad. El proyecto combina un frontend corporativo, un backend API, una base de datos PostgreSQL, un monitor SOC, una capa de observabilidad con Grafana y una comparativa entre una versión vulnerable y otra segura. El valor principal del proyecto no está solo en la web, sino en cómo se despliega, securiza, monitoriza y documenta una plataforma completa con enfoque de redes, bases de datos, scripting, Docker y seguridad aplicada.

## 2. Estructura recomendada de la exposición

Orden sugerido:

1. problema y objetivo del proyecto;
2. arquitectura general;
3. tecnologías elegidas;
4. login vulnerable vs seguro;
5. dashboard y monitor SOC;
6. pagos y ataques de manipulación;
7. Docker, red y base de datos;
8. scripts de automatización y ataques;
9. observabilidad con Grafana;
10. conclusiones y mejoras futuras.

## 3. Qué decir en cada bloque

## 3.1 Problema y objetivo

Qué decir:

> Quería construir una plataforma que no fuera solo una web, sino un entorno completo y desplegable que permitiera demostrar administración de servicios, seguridad, observabilidad, bases de datos y automatización. Por eso el proyecto incorpora frontend, backend, PostgreSQL, Docker Compose, Grafana y un sistema dual de seguridad.

## 3.2 Arquitectura

Qué decir:

> La arquitectura es cliente-servidor. El frontend visible se sirve en `localhost:8000`, el backend en `localhost:8001`, PostgreSQL se encarga de la persistencia, Grafana actúa como panel técnico y el monitor SOC es la capa corporativa de visualización de seguridad. Todo el entorno puede levantarse con Docker Compose.

## 3.3 Seguridad dual

Qué decir:

> El proyecto tiene dos rutas de autenticación con la misma apariencia visual pero distinto comportamiento interno. La versión vulnerable permite demostrar malas prácticas y la segura aplica controles como validación más fuerte, rate limiting, protección de sesión y bloqueo de payloads maliciosos.

## 3.4 SOC y observabilidad

Qué decir:

> El SOC corporativo permite visualizar incidentes, vectores, países origen y servicios protegidos, mientras que Grafana se utiliza como panel técnico complementario para mostrar métricas internas y actividad operativa.

## 3.5 Pagos y ataques

Qué decir:

> El módulo de pagos está diseñado para demostrar por qué nunca debe confiarse el importe final al cliente. En la versión vulnerable el importe puede manipularse, mientras que en la segura se valida o recalcula del lado servidor y el intento debe quedar reflejado como evento de seguridad.

## 4. Preguntas probables y cómo responderlas

## 4.1 ¿Por qué este proyecto es adecuado para ASIX?

Respuesta recomendada:

> Porque no se centra solo en desarrollo web. Integra despliegue de servicios, redes, Docker, bases de datos, scripting, control de acceso, monitorización, observabilidad y seguridad. Eso encaja directamente con módulos como Servicios de red e Internet, Administración de SGBD, Seguridad y alta disponibilidad, Implantación de aplicaciones web y el módulo de Proyecto.

## 4.2 ¿Por qué has usado Node/TypeScript en backend si eres de ASIX?

Respuesta recomendada:

> Porque el objetivo no era replicar exactamente el stack visto en clase, sino construir una plataforma administrable y desacoplada. Aun así, la parte visible se ha adaptado a PHP para acercarla más al entorno trabajado, mientras que el backend desacoplado permite justificar arquitectura, APIs, seguridad y despliegue de servicios.

## 4.3 ¿Qué aporta Docker en este proyecto?

Respuesta recomendada:

> Docker permite reproducibilidad, separación de servicios, facilidad de despliegue y control del entorno. Gracias a Docker Compose puedo levantar frontend, backend, base de datos y paneles de observabilidad con una sola orden.

## 4.4 ¿Por qué hay dos logins visualmente iguales?

Respuesta recomendada:

> Porque la intención pedagógica es demostrar que dos interfaces idénticas pueden implicar niveles de seguridad muy distintos. La diferencia está en el backend, la validación, el control de sesión y la reacción ante ataques, no en el aspecto visual.

## 4.5 ¿Qué ataques se pueden demostrar?

Respuesta recomendada:

> Principalmente SQL Injection (Bypass y Exfiltración de PII como IBAN/Tarjetas/CVV), XSS, path traversal, fuerza bruta y manipulación del checkout. La idea no es hacer ofensiva real sobre sistemas externos, sino mostrar cómo la versión vulnerable tolera o registra sin bloquear, mientras que la segura detecta, rechaza y genera evidencia.

## 4.6 ¿Qué papel tiene Grafana?

Respuesta recomendada:

> Grafana es el panel técnico. No sustituye al SOC corporativo, lo complementa. El SOC está orientado a negocio y seguridad visible, mientras que Grafana ayuda a enseñar métricas internas y observabilidad del sistema.

## 4.7 ¿Qué papel tiene PostgreSQL?

Respuesta recomendada:

> PostgreSQL es la base de datos relacional del proyecto. Persiste usuarios, servicios, clientes, activos, incidentes, tickets, pagos y eventos de seguridad. Eso permite que el sistema tenga trazabilidad y no dependa de datos mock sin persistencia.

## 4.8 ¿Cómo controlas quién puede ver el dashboard y el SOC?

Respuesta recomendada:

> A nivel backend, las rutas `/api/admin/*` exigen autenticación y rol `ADMIN`. A nivel frontend, si una ruta protegida responde `401` o `403`, se redirige a `/login`. De esa forma no cualquier visitante puede visualizar estadísticas o incidentes.

## 4.9 ¿Qué parte consideras más importante del proyecto?

Respuesta recomendada:

> La integración entre capas. Lo importante no es solo la web o solo la API, sino que frontend, backend, base de datos, seguridad, observabilidad, scripts y despliegue funcionen juntos y puedan explicarse como una sola plataforma coherente.

## 4.10 ¿Qué mejorarías si tuvieses más tiempo?

Respuesta recomendada:

> Añadiría un checkout visual más completo con simulación de fraude reflejada en el SOC, más visualizaciones en tiempo real, roles más detallados, 2FA real, pruebas end-to-end y mejor automatización de documentación y despliegue.

## 5. Preguntas más técnicas

## 5.1 ¿Cómo diferencias el modo seguro del vulnerable?

Respuesta:

> Mediante rutas separadas en autenticación y lógica condicional controlada por el backend. No son dos proyectos distintos; es el mismo dominio funcional con dos políticas de seguridad diferentes.

## 5.2 ¿Dónde se bloquean los ataques?

Respuesta:

> Principalmente en middleware y validación de backend. El frontend no debe ser la defensa principal. La versión segura detecta patrones maliciosos, limita intentos y endurece la gestión de sesión.

## 5.3 ¿Cómo demuestras la diferencia?

Respuesta:

> Con scripts de ataque, respuestas HTTP distintas, cambios en el comportamiento del sistema y visualización posterior en los paneles y métricas.

## 5.4 ¿Por qué el panel SOC no lo puede ver cualquiera?

Respuesta:

> Porque en un entorno real mostrar incidentes, vectores, países origen o postura de seguridad a usuarios no autorizados sería una mala práctica. Por eso está restringido a perfiles administradores.

## 6. Errores o críticas que te pueden hacer

## 6.1 “Esto parece mezcla de tecnologías”

Cómo responder:

> Es una decisión consciente. La parte visible se adaptó a PHP para alinearla mejor con el entorno de aprendizaje, mientras que el backend desacoplado mantiene una arquitectura más moderna y defendible desde el punto de vista de servicios, seguridad y APIs.

## 6.2 “No está pensado para producción”

Cómo responder:

> Correcto. Es un proyecto académico y se documenta así. Lo relevante es que demuestra arquitectura, control de acceso, seguridad dual, observabilidad y despliegue reproducible en local.

## 6.3 “¿Por qué no has usado solo PHP?”

Cómo responder:

> Se podría hacer, pero habría supuesto rehacer gran parte del backend y perder la separación clara entre frontend y API. La solución actual permite defender mejor desacoplamiento, APIs, seguridad, scripts y observabilidad.

## 7. Qué no debes decir en la defensa

- no digas que el proyecto “es solo una web”
- no lo vendas como un simple diseño visual
- no ocultes que es un entorno académico
- no digas que está preparado para producción si no lo está
- no centres la defensa en estética; céntrala en integración y operación

## 8. Cierre recomendado

Versión de cierre:

> En conclusión, Sofia Solutions me ha permitido construir y documentar una plataforma completa donde convergen servicios, red, base de datos, seguridad, automatización y observabilidad. La parte más valiosa del proyecto es que no solo funciona, sino que además permite demostrar por qué una misma funcionalidad puede ser insegura o robusta según cómo se diseñe y se administre.
