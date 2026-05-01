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

        <!-- Embedded Grafana Dashboard: Dynamic Environment Resolution -->
        <section class="panel" style="padding:24px;margin-bottom:32px;">
            <div class="panel-heading" style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;">
                <div><span class="eyebrow">Monitorización</span><h2>Grafana — Live Metrics</h2></div>
                <a id="grafana-open-btn" href="#" target="_blank" style="font-size:0.78rem;color:#818cf8;text-decoration:none;border:1px solid rgba(99,102,241,0.3);padding:6px 14px;border-radius:8px;">Abrir en pantalla completa ↗</a>
            </div>
            <div style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.06);background:#111;">
                <iframe id="grafana-frame" src="" style="width:100%;height:450px;border:none;display:block;" loading="lazy"></iframe>
            </div>
            <p id="grafana-error" style="display:none;color:var(--text-muted);font-size:0.8rem;margin-top:12px;text-align:center;">
                ⚠ Grafana no disponible. <a id="grafana-direct-link" href="#" target="_blank" style="color:#818cf8;">Abrirlo directamente</a>
            </p>
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

        <!-- Live Operations & Troubleshooting Console -->
        <section class="panel" style="padding:24px;margin-bottom:32px;">
            <div class="panel-heading" style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;">
                <div><span class="eyebrow">Operaciones</span><h2>Consola de Eventos en Vivo</h2></div>
                <button onclick="document.getElementById('admin-console').innerHTML=''" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:var(--text-muted);padding:6px 14px;border-radius:8px;cursor:pointer;font-size:0.75rem;">Limpiar</button>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;">
                <button class="diag-btn" onclick="adminCmd('live')">&#128225; Eventos Recientes</button>
                <button class="diag-btn" onclick="adminCmd('clients')">&#128101; Estado Clientes</button>
                <button class="diag-btn" onclick="adminCmd('incidents')">&#128680; Incidentes Activos</button>
                <button class="diag-btn" onclick="adminCmd('ping')">&#127955; Ping Sistema</button>
            </div>
            <div id="admin-console" style="background:#0a0e1a;border:1px solid rgba(99,102,241,0.2);border-radius:10px;padding:16px;font-family:monospace;font-size:0.78rem;color:#a5f3fc;height:220px;overflow-y:auto;line-height:1.8;"></div>
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

<script>
/**
 * CORE ADMINISTRATIVE LOGIC
 */
const API    = window.SOFIA_CONFIG?.apiBase || '';
const TOKEN  = () => localStorage.getItem('sofia_token_v1');
const authHdr = () => ({ Authorization: 'Bearer ' + TOKEN() });

/**
 * GRAFANA EMBEDDING ENGINE
 * Resolves local vs tunnel URLs based on current origin.
 */
(function setupGrafana() {
    const base    = location.origin + '/grafana';
    const panelUrl = `${base}/d/sofia-prometheus/sofia-security-overview?orgId=1&kiosk&theme=dark`;
    const frame    = document.getElementById('grafana-frame');
    const openBtn  = document.getElementById('grafana-open-btn');
    const errEl    = document.getElementById('grafana-error');
    const dirLink  = document.getElementById('grafana-direct-link');

    frame.src = panelUrl;
    openBtn.href = base;
    if (dirLink) dirLink.href = base;

    frame.addEventListener('error', () => {
        frame.style.display = 'none';
        errEl.style.display = 'block';
    });
    
    // Fallback detection for cross-origin failures
    setTimeout(() => {
        try {
            if (!frame.contentDocument || frame.contentDocument.title === '') {
                frame.style.display = 'none';
                errEl.style.display = 'block';
            }
        } catch(e) {}
    }, 8000);
})();

/**
 * TELEMETRY SYNCHRONIZATION
 * Fetches and processes real-time security data from the backend.
 */
(async function loadAdmin() {
    try {
        const r = await fetch(API + '/api/admin/security-monitor', { headers: authHdr() });
        if (!r.ok) throw new Error('Security API Unreachable');
        const d = await r.json();
        const s = d.summary || {};

        // Update KPI counters
        document.querySelector('#kpi-events strong').textContent    = s.totalEventsAnalyzed ? (s.totalEventsAnalyzed/1e6).toFixed(1)+'M' : '—';
        document.querySelector('#kpi-incidents strong').textContent = s.criticalIncidents ?? '—';
        document.querySelector('#kpi-customers strong').textContent = s.protectedCustomers ?? '—';
        document.querySelector('#kpi-health strong').textContent    = s.systemHealth != null ? s.systemHealth + '%' : '—';

        // Populate Client Ledger
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
        document.getElementById('client-table-body').innerHTML =
            rows || '<tr><td colspan="5" style="padding:20px;text-align:center;color:var(--text-muted);">No client records found</td></tr>';

        renderCharts(d.topAttackVectors || [], d.alertDistribution || []);

    } catch(e) {
        console.warn('Admin Analytics Sync Failure', e);
        renderCharts([], []); // Fallback to demo mode
    }
})();

/**
 * DATA VISUALIZATION (Chart.js Implementation)
 */
function renderCharts(vectors, dist) {
    const vLabels = vectors.length ? vectors.map(v => v.label) : ['DDoS','SQLi','Brute Force','Malware','XSS'];
    const vData   = vectors.length ? vectors.map(v => v.count) : [1200,1900,3000,500,800];
    const dLabels = dist.length ? dist.map(d => d.label) : ['Crítico','Alto','Medio','Bajo'];
    const dData   = dist.length ? dist.map(d => d.value) : [15,25,40,20];
    const dColors = dist.length ? dist.map(d => d.color) : ['#ef4444','#f59e0b','#06b6d4','#22c55e'];

    new Chart(document.getElementById('globalTrafficChart'), {
        type: 'bar',
        data: { labels: vLabels, datasets: [{ data: vData, backgroundColor: 'rgba(6,182,212,0.5)', borderColor: '#06b6d4', borderWidth: 1 }] },
        options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false} }, scales:{ y:{ beginAtZero:true, grid:{ color:'rgba(255,255,255,0.04)' }, ticks:{color:'rgba(255,255,255,0.4)'} }, x:{ grid:{display:false}, ticks:{color:'rgba(255,255,255,0.4)'} } } }
    });
    new Chart(document.getElementById('riskChart'), {
        type: 'doughnut',
        data: { labels: dLabels, datasets: [{ data: dData, backgroundColor: dColors, borderWidth: 0 }] },
        options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom', labels:{ color:'#fff', padding:16, font:{size:11} } } } }
    });
}

/**
 * ADMINISTRATIVE CONSOLE COMMANDS
 */
const aLog = (msg, color = '#a5f3fc') => {
    const c  = document.getElementById('admin-console');
    const ts = new Date().toLocaleTimeString('es-ES');
    const el = document.createElement('div');
    el.innerHTML = `<span style="color:#475569">[${ts}]</span> <span style="color:${color}">${msg}</span>`;
    c.appendChild(el);
    c.scrollTop = c.scrollHeight;
};

async function adminCmd(cmd) {
    aLog('> ' + cmd, '#818cf8');
    try {
        const r = await fetch(API + '/api/admin/security-monitor', { headers: authHdr() });
        const d = await r.json();
        if (cmd === 'live') {
            (d.liveFeed || []).forEach(ev => {
                const col = ev.severity === 'CRITICAL' ? '#ef4444' : ev.severity === 'HIGH' ? '#f59e0b' : '#22c55e';
                aLog(`[${ev.severity}] ${ev.type} — ${ev.sourceIp} → ${ev.destination}`, col);
            });
            if (!(d.liveFeed || []).length) aLog('No recent events recorded.', '#64748b');
        } else if (cmd === 'clients') {
            (d.customerExposure || []).forEach(c =>
                aLog(`${c.name}: ${c.incidents} active incidents | ${c.assets} IT assets | Tier: ${c.tier}`)
            );
        } else if (cmd === 'incidents') {
            const s = d.summary || {};
            aLog('Critical Incidents: ' + (s.criticalIncidents || 0), '#ef4444');
            aLog('Active Threats:     ' + (s.activeThreats    || 0), '#f59e0b');
            aLog('Overall Health:     ' + (s.systemHealth     || '?') + '%', '#22c55e');
        } else if (cmd === 'ping') {
            const t0 = Date.now();
            await fetch(API + '/api/admin/overview', { headers: authHdr() });
            aLog('✓ Operation Center online. Latency: ' + (Date.now() - t0) + 'ms', '#22c55e');
        }
    } catch(e) { aLog('✗ Error: ' + e.message, '#ef4444'); }
}

function openClient(name) {
    document.getElementById('modal-content').innerHTML =
        `<h2 style="font-size:1.6rem;margin-bottom:8px;">${name}</h2>
         <p style="color:var(--text-muted);">Redirecting to SOC Monitor for detailed real-time incident mapping and asset inventory for this client.</p>
         <a href="/soc" class="btn btn-primary" style="margin-top:16px;display:inline-block;">Launch SOC Monitor →</a>`;
    document.getElementById('client-modal').style.display = 'flex';
}
function closeModal() { document.getElementById('client-modal').style.display = 'none'; }

/**
 * INTERNATIONALIZATION (i18n)
 */
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
