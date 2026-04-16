param(
  [string]$TemplatePath = "C:\Users\sgomez\Downloads\Plantilla Memoria projecte (1) (1).odt",
  [string]$OutputPath = "C:\Users\sgomez\Desktop\sofia-solutions\Memoria-ASIX-Sofia-Solutions.odt"
)

$ErrorActionPreference = "Stop"

function Escape-Xml([string]$text) {
  $escaped = [System.Security.SecurityElement]::Escape($text)
  return $escaped.Replace("`r`n", "<text:line-break/>").Replace("`n", "<text:line-break/>")
}

function Para([string]$text, [string]$style = "P21") {
  return "<text:p text:style-name=""$style"">$(Escape-Xml $text)</text:p>"
}

function Heading1([string]$bookmark, [string]$ref, [string]$title) {
  return "<text:h text:style-name=""P22"" text:outline-level=""1""><text:bookmark text:name=""$bookmark""/><text:bookmark-start text:name=""$ref""/>$(Escape-Xml $title)<text:bookmark-end text:name=""$ref""/></text:h>"
}

function Heading2([string]$bookmark, [string]$ref, [string]$title) {
  return "<text:h text:style-name=""P25"" text:outline-level=""2""><text:bookmark text:name=""$bookmark""/><text:bookmark-start text:name=""$ref""/>$(Escape-Xml $title)<text:bookmark-end text:name=""$ref""/></text:h>"
}

function ListBlock([string[]]$items) {
  $inner = ($items | ForEach-Object {
    "<text:list-item><text:p text:style-name=""P30"">$(Escape-Xml $_)</text:p></text:list-item>"
  }) -join ""
  return "<text:list text:style-name=""L1"">$inner</text:list>"
}

$section = @()

$section += Heading1 "Bookmark3" "__RefHeading__1013_496126303" "3. Análisis de requerimientos"
$section += '<text:h text:style-name="P24" text:outline-level="1"/>'

$section += Heading2 "Bookmark4" "__RefHeading__1015_496126303" "3.1 Necesidades, requerimientos del cliente y análisis de la situación actual"
$section += Para "El proyecto desarrollado para Sofia Solutions se plantea como una plataforma corporativa orientada a administración de sistemas, seguridad, monitorización y despliegue de servicios. La necesidad principal no era crear únicamente una web visual, sino disponer de una infraestructura funcional que permitiera representar una empresa de servicios IT y ciberseguridad con una base técnica coherente y defendible."
$section += Para "Desde el punto de vista del cliente, la solución debía cubrir varios objetivos simultáneos: ofrecer una presencia corporativa profesional, permitir autenticación de usuarios, centralizar información de servicios, clientes y activos, mostrar una consola SOC realista y permitir demostrar diferencias claras entre una implementación vulnerable y otra segura."
$section += Para "La situación inicial era de partida desde cero. No existía una infraestructura previa, ni backend, ni base de datos, ni monitorización, ni política de autenticación, ni panel de control. Por ello fue necesario diseñar desde el inicio la topología del entorno, la estructura de datos, la lógica de servicios, la autenticación, la monitorización y los procedimientos de despliegue."
$section += Para "En un enfoque claramente alineado con ASIX, el proyecto se orientó a demostrar capacidades relacionadas con redes, contenedores, servicios, scripting, bases de datos, seguridad y observabilidad. No se consideró suficiente una solución puramente estática o centrada solo en frontend."
$section += Para "Este enfoque encaja directamente con varios módulos profesionales que se trabajan en 2º curso de ASIX en España. Entre los módulos más relacionados con este proyecto destacan Administración de sistemas operativos, Servicios de red e Internet, Implantación de aplicaciones web, Administración de sistemas gestores de bases de datos, Seguridad y alta disponibilidad y el módulo de Proyecto de administración de sistemas informáticos en red."
$section += Para "La relación con el proyecto es directa: la capa backend y su despliegue encajan con administración de servicios, la base de datos con administración de SGBD, Docker y la red entre contenedores con servicios de red, la autenticación dual y los ataques con seguridad y alta disponibilidad, y la integración global del sistema con el módulo de proyecto."
$section += ListBlock @(
  "Despliegue y administración de servicios desacoplados.",
  "Autenticación diferenciada entre entorno vulnerable y seguro.",
  "Base de datos relacional con persistencia y seed de datos.",
  "Scripts de ataque para validación ofensiva controlada.",
  "Visualización de métricas y actividad de seguridad en SOC y Grafana."
)
$section += '<text:p text:style-name="P26"/>'

$section += Heading2 "Bookmark5" "__RefHeading__1017_496126303" "3.2 Estudio de alternativas de solución"
$section += Para "Durante el análisis se valoraron varias alternativas. La primera consistía en realizar una web estática sin backend ni base de datos. Esta solución fue descartada porque no permitía justificar control de accesos, monitorización, seguridad ni administración de servicios."
$section += Para "La segunda alternativa era una aplicación visual con datos simulados, útil para una maqueta, pero insuficiente para demostrar aspectos clave del proyecto como la explotación de vulnerabilidades, el bloqueo de ataques, la persistencia de información o la observabilidad."
$section += Para "También se valoró mantener una única implementación sin separación entre modo vulnerable y modo seguro. Esta opción simplificaba el desarrollo, pero dificultaba la finalidad académica del proyecto, que exige demostrar con claridad qué cambia cuando se aplican medidas de defensa reales."
$section += Para "La solución finalmente seleccionada se basa en un frontend servido por Apache/PHP para la capa visible, un backend en Express y TypeScript para la lógica de negocio y seguridad, PostgreSQL como base de datos, Docker Compose para el despliegue del entorno completo y Grafana como herramienta de visualización técnica."
$section += ListBlock @(
  "Frontend visible servido por Apache/PHP, más alineado con tecnologías trabajadas en clase.",
  "Backend desacoplado con API REST para autenticación, servicios, tickets y seguridad.",
  "PostgreSQL y Prisma para persistencia relacional y trazabilidad de datos.",
  "Docker Compose como herramienta de despliegue y administración del stack.",
  "Grafana como panel técnico y SOC propio como capa corporativa de monitorización."
)
$section += Para "Esta alternativa fue elegida porque equilibra realismo técnico, valor formativo, facilidad de despliegue y coherencia con un proyecto de ASIX centrado en servicios, seguridad, scripting, redes y operación del sistema."
$section += '<text:p text:style-name="P26"/><text:p text:style-name="P26"/>'

$section += Heading2 "Bookmark6" "__RefHeading__1019_496126303" "3.3 Elección, valoración económica y diseño de las posibles soluciones"
$section += Para "La solución final se compone de varios servicios conectados entre sí: un frontend visible, un backend de aplicación, una base de datos PostgreSQL, una fuente interna de métricas y una capa de visualización técnica con Grafana. Este diseño permite presentar la solución como una plataforma desplegada y administrada, no solo como una aplicación web."
$section += Para "Desde el punto de vista económico, al tratarse de un proyecto académico, se ha trabajado íntegramente con software libre y despliegue local. Por tanto, el coste de licencias es nulo. No obstante, en un escenario real, habría que contemplar costes de servidor, almacenamiento, logs, dominio y operación continua."
$section += ListBlock @(
  "Software libre utilizado: Apache/PHP, Node.js, Express, PostgreSQL, Prisma, Docker y Grafana: 0 EUR en licencias.",
  "Infraestructura estimada en un escenario real: entre 45 y 90 EUR mensuales según servidor, base de datos y retención de logs.",
  "Coste de implantación técnica estimado: entre 3000 y 6500 EUR según alcance, automatización y soporte."
)
$section += Para "La solución escogida es la más adecuada porque permite justificar la arquitectura, la seguridad, la operación del entorno y la monitorización desde una perspectiva de ASIX. Además, permite relacionar los servicios ofertados con clientes, activos, incidentes, métricas y controles de defensa."
$section += '<text:p text:style-name="P27"/><text:p text:style-name="P27"/><text:p text:style-name="P27"/>'

$section += Heading1 "Bookmark7" "__RefHeading__1021_496126303" "4. Implementación"
$section += '<text:p text:style-name="P28"/>'
$section += Para "La implementación se ha realizado con una arquitectura modular y orientada a servicios, priorizando aspectos de despliegue, seguridad, bases de datos, scripting y observabilidad. El proyecto no se ha tratado como una simple web, sino como una plataforma administrable y reproducible."

$section += Heading2 "Bookmark8" "__RefHeading__1023_496126303" "4.1 Diagramas y esquemas"
$section += Para "El sistema se compone de varias rutas visibles y varios servicios internos. A nivel funcional, la plataforma presenta una página principal, dos rutas de login, un dashboard y un monitor SOC. A nivel de infraestructura, se despliegan servicios diferenciados para frontend, backend, base de datos, fuente de métricas y visualización."
$section += ListBlock @(
  "Mapa del sitio: /, /login, /login-secure, /dashboard y /admin/security-monitor.",
  "Esquema de servicios: frontend, backend, PostgreSQL, Prometheus interno y Grafana.",
  "Esquema de datos: usuarios, servicios, clientes, activos, incidentes, tickets, pagos y eventos de seguridad."
)

$section += Heading2 "Bookmark10" "__RefHeading__1025_496126303" "4.2 Herramientas de software"
$section += ListBlock @(
  "Apache y PHP para la capa visible del sistema.",
  "Node.js 20+, Express y TypeScript para la API REST y la lógica de seguridad.",
  "PostgreSQL 15 y Prisma para la persistencia relacional.",
  "Docker y Docker Compose para el despliegue completo.",
  "Grafana para la visualización técnica de métricas.",
  "Winston para logging estructurado.",
  "Zod, Helmet, CORS, cookies seguras y rate limiting para refuerzo de seguridad."
)
$section += Para "Estas herramientas se seleccionaron por su estabilidad, capacidad de despliegue local, facilidad de automatización y coherencia con un proyecto orientado a administración de sistemas."

$section += Heading2 "Bookmark12" "__RefHeading__614_2124264076" "4.3 Herramientas de hardware"
$section += Para "No ha sido necesario emplear hardware específico. El proyecto se ha desarrollado y probado en un equipo estándar, pero la solución está pensada para poder desplegarse sobre servidor Linux, máquina virtual o infraestructura local con Docker."
$section += Para "Desde el punto de vista de ASIX, el valor principal no está en un dispositivo concreto, sino en la administración de servicios, la red entre contenedores, la persistencia de datos y la securización del entorno."

$section += Heading2 "Bookmark14" "__RefHeading__618_2124264076" "4.4 Lenguajes"
$section += ListBlock @(
  "PHP para la capa visible servida por Apache.",
  "HTML y CSS para estructura y presentación.",
  "JavaScript para lógica en cliente y consumo de API.",
  "TypeScript para backend, validación y scripts de prueba.",
  "SQL relacional mediante PostgreSQL y Prisma.",
  "YAML para Docker Compose y provisión de Grafana.",
  "PowerShell y shell script para automatización en Windows y Linux."
)

$section += Heading2 "" "__RefHeading__624_2124264076" "4.5 Codificación"
$section += Para "La codificación se ha organizado por capas. La capa visible se sirve desde Apache/PHP y consume la API. La capa de aplicación reside en el backend Express/TypeScript, donde se implementan autenticación, control de sesiones, servicios, tickets, pagos simulados, eventos de seguridad y métricas."
$section += Para "Una parte especialmente relevante de la codificación para este proyecto ha sido la creación de scripts de ataque y scripts de operación. Estos scripts permiten levantar el entorno, alternar entre modo vulnerable y seguro, ejecutar ataques automatizados y comprobar el comportamiento del sistema en ambos escenarios."
$section += ListBlock @(
  "Scripts de despliegue del stack para Windows y Linux.",
  "Scripts de ataque SQLi, XSS, path traversal, fuerza bruta y manipulación de pagos.",
  "Scripts de validación de servicios y efectividad defensiva.",
  "Endpoints diferenciados entre autenticación vulnerable y segura."
)

$section += Heading2 "" "__RefHeading__626_2124264076" "4.6 Administración"
$section += Para "La administración del sistema se ha planteado como parte central del proyecto. Cada servicio tiene una función específica y puede arrancarse, detenerse y supervisarse de forma independiente. El backend centraliza la lógica de negocio, el frontend expone la interfaz, PostgreSQL almacena los datos y Grafana permite visualizar el estado técnico del sistema."
$section += Para "También se han tenido en cuenta usuarios, roles y permisos. El sistema contempla perfiles autenticados y control de acceso según rol y tipo de endpoint consumido, de forma que la parte administrativa y la parte operativa queden diferenciadas."

$section += Heading2 "" "__RefHeading__628_2124264076" "4.7 Aspectos de seguridad"
$section += Para "La seguridad es el eje principal del proyecto. Se han implementado dos comportamientos diferenciados: modo vulnerable y modo seguro. El objetivo es demostrar de forma práctica cómo puede explotarse una mala implementación y cómo se bloquea esa misma técnica en una versión corregida."
$section += Para "Para ello se han desarrollado scripts maliciosos y pruebas automatizadas que permiten lanzar ataques sobre el login vulnerable y repetirlos en el login seguro. Entre los ataques considerados se encuentran la inyección SQL, el XSS, el path traversal, la fuerza bruta y la manipulación insegura de parámetros."
$section += ListBlock @(
  "Modo vulnerable: acepta comportamientos inseguros con fines docentes.",
  "Modo seguro: aplica validación estricta, hashing robusto, rate limiting, CSRF, cookies seguras y bloqueo de patrones maliciosos.",
  "Eventos y métricas: el sistema registra actividad sospechosa y la expone para visualización en SOC y Grafana.",
  "Base de datos y contenedores: separación de servicios y persistencia controlada dentro del entorno Docker."
)
$section += Para "La visualización final del comportamiento del sistema no se apoya en Prometheus como interfaz visible, sino en Grafana y en el monitor SOC propio. De este modo, la defensa del proyecto puede centrarse en la parte realmente útil para ASIX: seguridad, redes, servicios, scripting, monitorización y administración."
$section += '<text:p text:style-name="P39"><text:soft-page-break/></text:p>'

$newSection = ($section -join "")

$tempRoot = Join-Path $PWD "temp\memoria_build"
$workDir = Join-Path $tempRoot "work"

if (Test-Path $workDir) {
  Remove-Item $workDir -Recurse -Force
}
New-Item -ItemType Directory -Force -Path $workDir | Out-Null

Copy-Item $TemplatePath (Join-Path $tempRoot "template.zip") -Force
Expand-Archive -LiteralPath (Join-Path $tempRoot "template.zip") -DestinationPath $workDir -Force

$contentPath = Join-Path $workDir "content.xml"
$content = Get-Content $contentPath -Raw -Encoding UTF8

$pattern = '(?s)<text:h text:style-name="P22" text:outline-level="1"><text:bookmark text:name="Bookmark3".*?<text:h text:style-name="P22" text:outline-level="1"><text:bookmark text:name="Bookmark18"'
$replacement = $newSection + '<text:h text:style-name="P22" text:outline-level="1"><text:bookmark text:name="Bookmark18"'
$updated = [regex]::Replace($content, $pattern, $replacement, 1)

if ($updated -eq $content) {
  throw "No se pudo reemplazar la sección 3-4 en content.xml"
}

[System.IO.File]::WriteAllText($contentPath, $updated, [System.Text.UTF8Encoding]::new($false))

if (Test-Path $OutputPath) {
  Remove-Item $OutputPath -Force
}

$zipOut = Join-Path $tempRoot "memoria_out.zip"
if (Test-Path $zipOut) {
  Remove-Item $zipOut -Force
}

Compress-Archive -Path (Join-Path $workDir '*') -DestinationPath $zipOut -Force
Copy-Item $zipOut $OutputPath -Force

Write-Host "Documento generado en: $OutputPath"
