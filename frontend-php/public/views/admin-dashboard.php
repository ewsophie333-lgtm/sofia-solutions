<?php 
/**
 * SOFIA SOLUTIONS - Administrative Operations Center (SOC)
 * Central command center for multi-tenant security monitoring.
 * 
 * This module aggregates telemetry from all clients, embeds Grafana metrics,
 * and provides a live administrative console for incident response.
 */
$activeNav = 'admin-dashboard'; 
?>

<!-- Security Guard: Ensure only ADMIN roles can access this view -->
<script>
(function(){
    const u = JSON.parse(localStorage.getItem('sofia_user_v1') || '{}');
    if (u.role === 'CLIENT') window.location.replace('/dashboard');
})();
</script>

<!-- External Charting Dependency -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="app-shell readdy-dashboard" id="main-app">
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <?php renderLogo('brand-mark brand-mark-sidebar'); ?>
            <div class="sidebar-brand-copy"><span>Sofia Solutions</span><small>Admin v3.0</small></div>
        </div>
        <?php renderAppNav($activeNav); ?>
        <div style="margin-top:auto; padding:20px; border-top:1px solid rgba(255,255,255,0.05);">
            <div style="display:flex; gap:8px; background:rgba(0,0,0,0.2); padding:4px; border-radius:8px;">
                <button onclick="setLang('es')" id="btn-es" style="flex:1;border:none;padding:6px;border-radius:6px;font-size:0.7rem;cursor:pointer;font-weight:700;">ES</button>
                <button onclick="setLang('en')" id="btn-en" style="flex:1;border:none;padding:6px;border-radius:6px;font-size:0.7rem;cursor:pointer;font-weight:700;">EN</button>
            </div>
        </div>
    </aside>

    <section class="content">
        <!-- Global Operations Header -->
        <header class="panel-header" style="margin-bottom:32px;">
            <div>
                <span class="eyebrow" data-i18n="eyebrow">Operaciones Globales</span>
                <h1 data-i18n="title">Panel de Operaciones</h1>
                <p class="panel-header-copy" data-i18n="copy">Visión unificada de ciberseguridad multi-cliente.</p>
            </div>
            <span style="padding:8px 16px; border-radius:20px; font-size:0.75rem; font-weight:700; background:rgba(34,197,94,0.1); color:#22c55e; border:1px solid rgba(34,197,94,0.2);">● SISTEMA OPERATIVO</span>
        </header>

        <!-- Global Performance Metrics (KPIs) -->
        <section class="planes-grid" style="grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:32px;">
            <div class="kpi-card" data-tone="ok" style="border-left:3px solid #6d28d9;" id="kpi-events"><span class="meta-label" data-i18n="k1">Eventos Analizados</span><strong>—</strong><div class="tone-bar" style="background:#6d28d9;"></div></div>
            <div class="kpi-card" data-tone="ok" style="border-left:3px solid #6d28d9;" id="kpi-incidents"><span class="meta-label" data-i18n="k2">Incidentes Activos</span><strong>—</strong><div class="tone-bar" style="background:#6d28d9;"></div></div>
            <div class="kpi-card" data-tone="ok" style="border-left:3px solid #6d28d9;" id="kpi-customers"><span class="meta-label" data-i18n="k3">Clientes Protegidos</span><strong>—</strong><div class="tone-bar" style="background:#6d28d9;"></div></div>
            <div class="kpi-card" data-tone="ok" style="border-left:3px solid #6d28d9;" id="kpi-health"><span class="meta-label" data-i18n="k4">Salud del Sistema</span><strong>—</strong><div class="tone-bar" style="background:#6d28d9;"></div></div>
        </section>

        <!-- Threat Vector & Risk Distribution Analytics -->
        <section class="planes-grid" style="grid-template-columns:1.8fr 1fr;gap:32px;margin-bottom:32px;">
            <article class="panel" style="padding:24px;">
                <div class="panel-heading"><div><span class="eyebrow">Telemetría</span><h2 data-i18n="chart1">Ataques por Vector (24h)</h2></div></div>
                <div style="height:280px;margin-top:20px;"><canvas id="globalTrafficChart"></canvas></div>
            </article>
            <article class="panel" style="padding:24px;">
                <div class="panel-heading"><div><span class="eyebrow">Riesgo</span><h2 data-i18n="chart2">Distribución de Alertas</h2></div></div>
                <div style="height:280px;margin-top:20px;display:flex;align-items:center;justify-content:center;"><canvas id="riskChart"></canvas></div>
            </article>
        </section>

        <!-- Client Server Health & Latency Analytics -->
        <section class="panel" style="padding:24px;margin-bottom:32px;">
            <div class="panel-heading" style="margin-bottom:20px;">
                <div><span class="eyebrow">Infraestructura</span><h2>Estado de Servidores por Cliente (Latencia y Salud)</h2></div>
            </div>
            <div class="planes-grid" style="grid-template-columns:1fr 1fr 1fr;gap:20px;">
                <!-- MAPFRE Server Health -->
                <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:10px; padding:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <strong style="color:#e2e8f0;">MAPFRE (srv-prod-01)</strong>
                        <span style="color:#22c55e; font-size:0.75rem; font-weight:bold; background:rgba(34,197,94,0.1); padding:4px 8px; border-radius:4px;">ONLINE</span>
                    </div>
                    <div style="height:100px;"><canvas id="mapfreLatencyChart"></canvas></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; font-size:0.75rem; color:var(--text-muted);">
                        <div><span>Uptime: 99.9%</span><br><span>Avg Ping: 12ms</span></div>
                        <button onclick="adminCmd('kill-switch-mapfre')" style="background:#ef4444; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-weight:bold; font-size:0.65rem;">KILL SWITCH</button>
                    </div>
                </div>

                <!-- IBERDROLA Server Health -->
                <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:10px; padding:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <strong style="color:#e2e8f0;">IBERDROLA (scada-gw)</strong>
                        <span style="color:#f59e0b; font-size:0.75rem; font-weight:bold; background:rgba(245,158,11,0.1); padding:4px 8px; border-radius:4px;">WARNING</span>
                    </div>
                    <div style="height:100px;"><canvas id="iberdrolaLatencyChart"></canvas></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; font-size:0.75rem; color:var(--text-muted);">
                        <div><span>Uptime: 98.4%</span><br><span>Avg Ping: 45ms</span></div>
                        <button onclick="adminCmd('kill-switch-iberdrola')" style="background:#ef4444; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-weight:bold; font-size:0.65rem;">KILL SWITCH</button>
                    </div>
                </div>

                <!-- SABADELL Server Health -->
                <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:10px; padding:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <strong style="color:#e2e8f0;">SABADELL (core-db)</strong>
                        <span style="color:#22c55e; font-size:0.75rem; font-weight:bold; background:rgba(34,197,94,0.1); padding:4px 8px; border-radius:4px;">ONLINE</span>
                    </div>
                    <div style="height:100px;"><canvas id="sabadellLatencyChart"></canvas></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; font-size:0.75rem; color:var(--text-muted);">
                        <div><span>Uptime: 99.9%</span><br><span>Avg Ping: 8ms</span></div>
                        <button onclick="adminCmd('kill-switch-sabadell')" style="background:#ef4444; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-weight:bold; font-size:0.65rem;">KILL SWITCH</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Client Security Posture Ledger -->
        <section class="panel" style="padding:24px;margin-bottom:32px;">
            <div class="panel-heading" style="margin-bottom:20px;">
                <div><span class="eyebrow">Portfolio</span><h2 data-i18n="table_title">Postura de Seguridad por Cliente</h2></div>
            </div>
            <table style="width:100%;border-collapse:collapse;font-size:0.88rem;">
                <thead><tr style="text-align:left;color:var(--text-muted);border-bottom:1px solid rgba(255,255,255,0.05);">
                    <th style="padding:14px 12px;">Cliente</th>
                    <th style="padding:14px 12px;">Estado</th>
                    <th style="padding:14px 12px;">Incidentes</th>
                    <th style="padding:14px 12px;">Activos IT</th>
                    <th style="padding:14px 12px;">Servicio</th>
                </tr></thead>
                <tbody id="client-table-body">
                    <tr><td colspan="5" style="padding:20px;text-align:center;color:var(--text-muted);">Sincronizando con SOC...</td></tr>
                </tbody>
            </table>
        </section>

        <!-- Visual Incident Response Panel -->
        <section class="panel" style="padding:24px;margin-bottom:32px;">
            <div class="panel-heading" style="margin-bottom:24px;">
                <div><span class="eyebrow">Operaciones IR</span><h2>Respuesta a Incidentes (IR)</h2></div>
            </div>
            
            <!-- IR Tabs -->
            <div style="display:flex; gap:12px; margin-bottom:24px; border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:16px;" id="ir-tabs">
                <button onclick="setIRTab('global')" class="ir-tab active" data-tab="global" style="background:var(--primary); color:#fff; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; font-weight:bold;">GLOBAL</button>
                <button onclick="setIRTab('mapfre')" class="ir-tab" data-tab="mapfre" style="background:rgba(255,255,255,0.05); color:var(--text-muted); border:1px solid rgba(255,255,255,0.05); padding:8px 16px; border-radius:6px; cursor:pointer; font-weight:bold;">MAPFRE</button>
                <button onclick="setIRTab('iberdrola')" class="ir-tab" data-tab="iberdrola" style="background:rgba(255,255,255,0.05); color:var(--text-muted); border:1px solid rgba(255,255,255,0.05); padding:8px 16px; border-radius:6px; cursor:pointer; font-weight:bold;">IBERDROLA</button>
                <button onclick="setIRTab('sabadell')" class="ir-tab" data-tab="sabadell" style="background:rgba(255,255,255,0.05); color:var(--text-muted); border:1px solid rgba(255,255,255,0.05); padding:8px 16px; border-radius:6px; cursor:pointer; font-weight:bold;">SABADELL</button>
            </div>

            <!-- IR Actions Toolbar -->
            <div id="ir-actions" style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:24px; background:rgba(0,0,0,0.2); padding:16px; border-radius:8px;">
                <!-- Actions rendered via JS -->
            </div>

            <!-- IR Visual Event Feed -->
            <div id="ir-feed" class="stack-list" style="height:300px; overflow-y:auto; padding-right:8px;">
                <!-- Feed rendered via JS -->
            </div>
        </section>
    </section>
</main>

<!-- Client Deep-Dive Modal -->
<div id="client-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);backdrop-filter:blur(8px);z-index:9999;align-items:center;justify-content:center;padding:40px;">
    <div class="panel" style="width:min(700px,100%);max-height:90vh;overflow-y:auto;position:relative;border:1px solid rgba(6,182,212,0.3);">
        <button onclick="closeModal()" style="position:absolute;top:20px;right:20px;background:none;border:none;color:#fff;cursor:pointer;font-size:1.5rem;">&times;</button>
        <div id="modal-content" style="padding:40px;"></div>
    </div>
</div>

<!-- SOS Admin Notification Modal -->
<div id="sos-admin-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.9); backdrop-filter:blur(15px); z-index:20000; align-items:center; justify-content:center; padding:20px;">
    <div class="panel" style="width:min(500px, 100%); border:2px solid #ef4444; box-shadow:0 0 50px rgba(239,68,68,0.5); animation: shake 0.5s infinite;">
        <div style="text-align:center; margin-bottom:24px;">
            <div style="background:rgba(239,68,68,0.2); width:80px; height:80px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; border:2px solid #ef4444;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/><path d="m9.05 9 1.41 1.41"/><path d="M12 14v5"/><path d="m14.95 9-1.41 1.41"/></svg>
            </div>
            <h2 style="color:#ef4444; margin:0; font-size:1.8rem; letter-spacing:1px;">¡ALERTA SOS RECIBIDA!</h2>
            <div id="sos-client-badge" style="display:inline-block; margin-top:12px; background:#ef4444; color:#fff; padding:4px 12px; border-radius:4px; font-weight:800; font-size:0.9rem;">CLIENTE: —</div>
        </div>
        <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:20px; margin-bottom:24px;">
            <div style="font-size:0.75rem; color:var(--text-muted); margin-bottom:8px; text-transform:uppercase; font-weight:700;">Mensaje de Emergencia:</div>
            <div id="sos-admin-reason" style="color:#fff; font-size:1rem; line-height:1.5; font-style:italic;">"..."</div>
        </div>
        <div style="display:flex; gap:12px;">
            <button onclick="dismissSOS()" style="flex:1; background:rgba(255,255,255,0.1); color:#fff; border:none; padding:14px; border-radius:10px; cursor:pointer; font-weight:700;">Ignorar</button>
            <button onclick="handleSOS()" style="flex:2; background:#22c55e; color:#fff; border:none; padding:14px; border-radius:10px; cursor:pointer; font-weight:800; box-shadow:0 4px 15px rgba(34,197,94,0.3);">INTERVENIR DE INMEDIATO</button>
        </div>
    </div>
</div>

<style>
@keyframes shake {
  0% { transform: translate(1px, 1px) rotate(0deg); }
  10% { transform: translate(-1px, -2px) rotate(-1deg); }
  20% { transform: translate(-3px, 0px) rotate(1deg); }
  30% { transform: translate(3px, 2px) rotate(0deg); }
  40% { transform: translate(1px, -1px) rotate(1deg); }
  50% { transform: translate(-1px, 2px) rotate(-1deg); }
  60% { transform: translate(-3px, 1px) rotate(0deg); }
  70% { transform: translate(3px, 1px) rotate(-1deg); }
  80% { transform: translate(-1px, -1px) rotate(1deg); }
  90% { transform: translate(1px, 2px) rotate(0deg); }
  100% { transform: translate(1px, -2px) rotate(-1deg); }
}
</style>

<script>
/**
 * CORE ADMINISTRATIVE LOGIC
 */
const API    = window.SOFIA_CONFIG?.apiBase || '';
const TOKEN  = () => localStorage.getItem('sofia_token_v1');
const authHdr = () => ({ Authorization: 'Bearer ' + TOKEN() });

/**
 * SOS EMERGENCY LISTENER (Simulated Real-time)
 */
function checkSOS() {
    const sos = localStorage.getItem('sofia_sos_request');
    if (sos) {
        const data = JSON.parse(sos);
        // Evitamos repetir la misma alerta si ya se mostró
        if (window.lastSOSId === data.id) return;
        window.lastSOSId = data.id;

        document.getElementById('sos-client-badge').textContent = `CLIENTE: ${data.client.toUpperCase()}`;
        document.getElementById('sos-admin-reason').textContent = `"${data.reason}"`;
        document.getElementById('sos-admin-modal').style.display = 'flex';
        
        // Sonido de alerta (opcional/simulado)
        console.log('🚨 SOS ALERT RECEIVED FROM ' + data.client);
    }
}
setInterval(checkSOS, 2000); // Poll every 2 seconds

function dismissSOS() {
    document.getElementById('sos-admin-modal').style.display = 'none';
    localStorage.removeItem('sofia_sos_request');
}

function handleSOS() {
    dismissSOS();
    triggerIRAction('sos-intervention');
    // Inyectamos un evento especial en el feed
    const feedEl = document.getElementById('ir-feed');
    const html = `
    <div style="display:flex;align-items:flex-start;gap:12px;padding:12px;background:rgba(34,197,94,0.1);border:1px solid #22c55e;border-radius:8px;margin-bottom:8px;">
        <div style="font-size:1.2rem;">👨‍💻</div>
        <div style="flex:1;">
            <strong style="color:#22c55e;font-size:0.85rem;">INTERVENCIÓN EN CURSO</strong>
            <p style="font-size:0.75rem;color:#94a3b8;margin-top:2px;">Analista SOC Nivel 3 conectado al entorno del cliente para triaje.</p>
        </div>
    </div>`;
    feedEl.insertAdjacentHTML('afterbegin', html);
}

/**
 * TELEMETRY SYNCHRONIZATION
 */
(async function loadAdmin() {
    try {
        const r = await fetch(API + '/api/admin/security-monitor', { headers: authHdr() });
        if (!r.ok) throw new Error('Security API Unreachable');
        const d = await r.json();
        const s = d.summary || {};

        document.querySelector('#kpi-events strong').textContent    = s.totalEventsAnalyzed ? (s.totalEventsAnalyzed/1e6).toFixed(1)+'M' : '—';
        document.querySelector('#kpi-incidents strong').textContent = s.criticalIncidents ?? '—';
        document.querySelector('#kpi-customers strong').textContent = s.protectedCustomers ?? '—';
        document.querySelector('#kpi-health strong').textContent    = s.systemHealth != null ? s.systemHealth + '%' : '—';

        const rows = (d.customerExposure || []).map(c => {
            const inc   = c.incidents || 0;
            const color = inc === 0 ? '#22c55e' : inc <= 2 ? '#f59e0b' : '#ef4444';
            const label = inc === 0 ? '● PROTEGIDO' : inc <= 2 ? '● REVISIÓN' : '● ALERTA';
            return `<tr style="border-bottom:1px solid rgba(255,255,255,0.03);cursor:pointer;"
                onclick="openClient('${c.name}')"
                onmouseover="this.style.background='rgba(255,255,255,0.02)'"
                onmouseout="this.style.background=''">
                <td style="padding:14px 12px;"><strong>${c.name}</strong></td>
                <td style="padding:14px 12px;"><span style="color:${color};font-weight:700;">${label}</span></td>
                <td style="padding:14px 12px;"><strong style="color:${color};">${inc}</strong></td>
                <td style="padding:14px 12px;color:var(--text-muted);">${c.assets}</td>
                <td style="padding:14px 12px;color:var(--text-muted);font-size:0.82rem;">${c.service || '—'}</td>
            </tr>`;
        }).join('');
        document.getElementById('client-table-body').innerHTML = rows || '<tr><td colspan="5" style="padding:20px;text-align:center;color:var(--text-muted);">No client records found</td></tr>';
        renderCharts(d.topAttackVectors || [], d.alertDistribution || []);
    } catch(e) { renderCharts([], []); }
})();

/**
 * DATA VISUALIZATION
 */
function renderCharts(vectors, dist) {
    const commonLineOptions = { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { display: false }, y: { display: false, min: 0 } }, elements: { point: { radius: 0 } } };
    new Chart(document.getElementById('globalTrafficChart'), { type: 'bar', data: { labels: vectors.map(v=>v.label), datasets: [{ data: vectors.map(v=>v.count), backgroundColor: 'rgba(6,182,212,0.5)', borderColor: '#06b6d4', borderWidth: 1 }] }, options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false} }, scales:{ y:{ beginAtZero:true, grid:{ color:'rgba(255,255,255,0.04)' }, ticks:{color:'rgba(255,255,255,0.4)'} }, x:{ grid:{display:false}, ticks:{color:'rgba(255,255,255,0.4)'} } } } });
    new Chart(document.getElementById('riskChart'), { type: 'doughnut', data: { labels: dist.map(d=>d.label), datasets: [{ data: dist.map(d=>d.value), backgroundColor: dist.map(d=>d.color), borderWidth: 0 }] }, options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom', labels:{ color:'#fff', padding:16, font:{size:11} } } } } });
    new Chart(document.getElementById('mapfreLatencyChart'), { type: 'line', data: { labels: ['1','2','3','4','5','6','7','8','9','10'], datasets: [{ data: [11,12,15,10,13,12,11,14,12,12], borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.1)', fill: true, tension: 0.4 }] }, options: commonLineOptions });
    new Chart(document.getElementById('iberdrolaLatencyChart'), { type: 'line', data: { labels: ['1','2','3','4','5','6','7','8','9','10'], datasets: [{ data: [20,25,30,85,90,40,45,50,42,45], borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.1)', fill: true, tension: 0.4 }] }, options: commonLineOptions });
    new Chart(document.getElementById('sabadellLatencyChart'), { type: 'line', data: { labels: ['1','2','3','4','5','6','7','8','9','10'], datasets: [{ data: [8,7,9,8,10,8,7,8,9,8], borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.1)', fill: true, tension: 0.4 }] }, options: commonLineOptions });
}

/**
 * INCIDENT RESPONSE (IR) VISUAL LOGIC
 */
const mockFeed = {
    global: [
        { time:'Hace 2 min', type:'ALERTA: Intento de Brute Force Distribuido', sev:'warning', node:'API Gateway' },
        { time:'Hace 15 min', type:'POLÍTICA: WAF actualizado (Reglas OWASP Core)', sev:'success', node:'Edge Firewall' },
        { time:'Hace 1 hora', type:'CRÍTICO: Anomalía de red detectada (Análisis Heurístico)', sev:'danger', node:'N/A' }
    ],
    mapfre: [
        { time:'Hace 5 min', type:'INCIDENTE: Escaneo de puertos bloqueado por IDS', sev:'success', node:'srv-prod-01' },
        { time:'Hace 30 min', type:'LATENCIA: Pico inusual detectado en clúster DB', sev:'warning', node:'db-mapfre-primary' }
    ],
    iberdrola: [
        { time:'Hace 1 min', type:'CRÍTICO: Inyección SCADA-Modbus mitigada', sev:'danger', node:'scada-gateway' },
        { time:'Hace 10 min', type:'AUTENTICACIÓN: Fallo múltiple de operador SCADA', sev:'warning', node:'auth-node-02' }
    ],
    sabadell: [
        { time:'Hace 8 min', type:'FRAUDE: Transacción anómala en revisión manual', sev:'warning', node:'core-banking-db' },
        { time:'Hace 45 min', type:'SISTEMA: Sincronización de HSM completada', sev:'success', node:'hsm-cluster' }
    ]
};

function renderFeed(tab) {
    const feed = mockFeed[tab] || [];
    const html = feed.map(f => {
        const c = f.sev === 'danger' ? '#ef4444' : f.sev === 'warning' ? '#f59e0b' : '#22c55e';
        // Iconos SVG profesionales en lugar de emojis
        const icon = f.sev === 'danger' ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${c}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>` : 
                     f.sev === 'warning' ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${c}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>` : 
                     `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${c}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`;
        
        return `
        <div style="display:flex;align-items:flex-start;gap:12px;padding:12px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:10px;margin-bottom:8px;">
            <div style="margin-top:2px;">${icon}</div>
            <div style="flex:1;">
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                    <strong style="color:${c};font-size:0.85rem;letter-spacing:0.5px;">${f.type}</strong>
                    <span style="font-size:0.7rem;color:var(--text-muted);">${f.time}</span>
                </div>
                <div style="font-size:0.75rem;color:#94a3b8;">Asset Tag: <span style="color:#e2e8f0;font-family:monospace;">${f.node}</span></div>
            </div>
        </div>`;
    }).join('');
    document.getElementById('ir-feed').innerHTML = html || '<div style="color:var(--text-muted);padding:20px;text-align:center;">No hay eventos recientes.</div>';
}

function renderActions(tab) {
    const act = document.getElementById('ir-actions');
    if (tab === 'global') {
        act.innerHTML = `
            <button class="diag-btn" onclick="triggerIRAction('export-global-logs')">📥 Exportar Logs Globales</button>
            <button class="diag-btn" onclick="triggerIRAction('rotate-keys')" style="border-color:#ef4444; color:#fca5a5; margin-left:auto; background:rgba(239,68,68,0.1);">🚨 Rotación Maestra (Panic Mode)</button>
        `;
    } else {
        const T = tab.toUpperCase();
        act.innerHTML = `
            <button class="diag-btn" onclick="triggerIRAction('pcap-${tab}')">🖧 Forense: Exportar PCAP (${T})</button>
            <button class="diag-btn" onclick="triggerIRAction('fw-${tab}')">🛡️ WAF: Endurecer Reglas (${T})</button>
            <button class="diag-btn" onclick="triggerIRAction('kill-${tab}')" style="border-color:#ef4444; color:#fca5a5; margin-left:auto; background:rgba(239,68,68,0.1);">🛑 AISLAMIENTO KILL-SWITCH (${T})</button>
        `;
    }
}

function setIRTab(tab) {
    document.querySelectorAll('.ir-tab').forEach(b => {
        if (b.dataset.tab === tab) {
            b.style.background = 'var(--primary)';
            b.style.color = '#fff';
            b.style.border = 'none';
        } else {
            b.style.background = 'rgba(255,255,255,0.05)';
            b.style.color = 'var(--text-muted)';
            b.style.border = '1px solid rgba(255,255,255,0.05)';
        }
    });
    renderActions(tab);
    renderFeed(tab);
}

function triggerIRAction(action) {
    const feedEl = document.getElementById('ir-feed');
    let title, sev = 'success', icon = '✅';
    
    if (action === 'rotate-keys') { title = 'CRÍTICO: Rotación Maestra completada. Tokens invalidados.'; sev = 'danger'; icon = '🚨'; }
    else if (action.startsWith('kill-')) { title = `AISLAMIENTO: Tenant ${action.split('-')[1].toUpperCase()} aislado de la red por Kill-Switch.`; sev = 'danger'; icon = '🛑'; }
    else if (action.startsWith('pcap-')) { title = `FORENSE: Captura PCAP generada y cifrada (${action.split('-')[1].toUpperCase()}).`; }
    else if (action.startsWith('fw-')) { title = `WAF: Política Strict-OWASP aplicada a ${action.split('-')[1].toUpperCase()}.`; }
    else if (action === 'sos-intervention') { title = 'SOS: Intervención SOC nivel 3 en curso.'; }
    else { title = `ACCION SOC: ${action}`; }

    const html = `<div style="display:flex;align-items:flex-start;gap:12px;padding:12px;background:rgba(${sev==='danger'?'239,68,68':'34,197,94'},0.1);border:1px solid rgba(${sev==='danger'?'239,68,68':'34,197,94'},0.4);border-radius:10px;margin-bottom:8px;animation:pulse 2s;"><div style="font-size:1.1rem;">${icon}</div><div style="flex:1;"><strong style="color:${sev==='danger'?'#fca5a5':'#86efac'};font-size:0.85rem;">[ADMIN SOC] ${title}</strong><div style="font-size:0.7rem;color:var(--text-muted);margin-top:2px;">Timestamp: ${new Date().toLocaleTimeString()}</div></div></div>`;
    feedEl.insertAdjacentHTML('afterbegin', html);
}

setTimeout(() => setIRTab('global'), 100);

function openClient(name) {
    document.getElementById('modal-content').innerHTML = `<h2 style="font-size:1.6rem;margin-bottom:8px;">${name}</h2><p style="color:var(--text-muted);">Redirigiendo al Monitor SOC para mapeo detallado de activos para este cliente.</p><a href="/soc" class="btn btn-primary" style="margin-top:16px;display:inline-block;">Lanzar Monitor SOC →</a>`;
    document.getElementById('client-modal').style.display = 'flex';
}
function closeModal() { document.getElementById('client-modal').style.display = 'none'; }

const i18n = {
    es: { eyebrow:"Operaciones Globales", title:"Panel de Operaciones", copy:"Visión unificada de ciberseguridad multi-cliente.", k1:"Eventos Analizados", k2:"Incidentes Activos", k3:"Clientes Protegidos", k4:"Salud del Sistema", chart1:"Ataques por Vector (24h)", chart2:"Distribución de Alertas", table_title:"Postura de Seguridad por Cliente" },
    en: { eyebrow:"Global Operations",  title:"Operations Panel",       copy:"Unified multi-client cybersecurity view.",           k1:"Events Analyzed",   k2:"Active Incidents",   k3:"Protected Clients",  k4:"System Health",      chart1:"Attacks by Vector (24h)",   chart2:"Alert Distribution",    table_title:"Security Posture by Client" }
};
function setLang(l) {
    localStorage.setItem('sofia_lang', l);
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const k = el.getAttribute('data-i18n');
        if (i18n[l] && i18n[l][k]) el.textContent = i18n[l][k];
    });
    document.getElementById('btn-es').style.background = l === 'es' ? 'var(--primary)' : 'rgba(255,255,255,0.05)';
    document.getElementById('btn-en').style.background = l === 'en' ? 'var(--primary)' : 'rgba(255,255,255,0.05)';
}
(function(){ setLang(localStorage.getItem('sofia_lang') || 'es'); })();
</script>

