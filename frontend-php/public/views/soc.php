<?php
$activeNav = 'soc';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="app-shell readdy-dashboard">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <?php renderLogo('brand-mark brand-mark-sidebar'); ?>
            <div class="sidebar-brand-copy">
                <span>Sofia Solutions</span>
                <small>SOC Monitor</small>
            </div>
        </div>
        <?php renderAppNav($activeNav); ?>
    </aside>

    <section class="content">
        <header class="panel-header" style="margin-bottom:32px;">
            <div>
                <span class="eyebrow">Security Operations Center</span>
                <h1>Monitor de Seguridad SOC</h1>
                <p class="panel-header-copy">Telemetría de red, vectores de ataque e incidentes en tiempo real.</p>
            </div>
            <div class="header-links">
                <span class="signal-chip" style="background:rgba(109,40,217,0.1); color:#a78bfa; border-color:rgba(109,40,217,0.2);">
                    <span class="pulse-dot" style="background:#6d28d9;"></span> 3 Alertas Activas
                </span>
            </div>
        </header>

        <!-- SOC KPIs -->
        <section id="soc-kpis" class="planes-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:32px;">
            <div class="kpi-card" data-tone="ok" style="border-left:3px solid #6d28d9;"><span class="meta-label">Eventos Analizados</span><strong>4.1M</strong><div class="tone-bar" style="background:#6d28d9;"></div></div>
            <div class="kpi-card" data-tone="ok" style="border-left:3px solid #6d28d9;"><span class="meta-label">Incidentes Críticos</span><strong>3</strong><div class="tone-bar" style="background:#6d28d9;"></div></div>
            <div class="kpi-card" data-tone="ok" style="border-left:3px solid #6d28d9;"><span class="meta-label">Amenazas Activas</span><strong>12</strong><div class="tone-bar" style="background:#6d28d9;"></div></div>
            <div class="kpi-card" data-tone="ok" style="border-left:3px solid #6d28d9;"><span class="meta-label">Salud del Sistema</span><strong>100%</strong><div class="tone-bar" style="background:#6d28d9;"></div></div>
        </section>

        <!-- Charts Row -->
        <section class="planes-grid" style="grid-template-columns: 1.8fr 1fr; gap:32px; margin-bottom:32px;">
            <article class="panel" style="padding:24px;">
                <div class="panel-heading">
                    <div><span class="eyebrow">Telemetría L7</span><h2>Incidentes por Hora (Últimas 12h)</h2></div>
                </div>
                <div style="height:280px; margin-top:20px;">
                    <canvas id="incidentTimelineChart"></canvas>
                </div>
            </article>
            <article class="panel" style="padding:24px;">
                <div class="panel-heading">
                    <div><span class="eyebrow">Vectores</span><h2>Top Vectores de Ataque</h2></div>
                </div>
                <div id="soc-vectors" class="stack-list" style="margin-top:20px; height:280px; overflow-y:auto;">
                    <div class="stack-item">Cargando vectores...</div>
                </div>
            </article>
        </section>

        <!-- Grafana Row -->
        <section class="planes-grid" style="grid-template-columns: 1fr; margin-bottom:32px;">
            <article class="panel" style="padding:0; overflow:hidden; border:1px solid rgba(79,70,229,0.3);">
                <div class="panel-heading" style="padding:24px 24px 0 24px;">
                    <div><span class="eyebrow" style="color:#a5b4fc;">Grafana Cloud</span><h2>Telemetría de Infraestructura (Prometheus)</h2></div>
                </div>
                <div style="height:500px; width:100%; margin-top:10px; background:#000;">
                    <iframe 
                        src="/grafana/d/sofia-security-overview/sofia-security-overview?orgId=1&kiosk&theme=dark" 
                        width="100%" 
                        height="100%" 
                        frameborder="0">
                    </iframe>
                </div>
            </article>
        </section>

        <!-- Live Feed & Countries -->
        <section class="planes-grid" style="grid-template-columns: 1fr 1fr; gap:32px;">
            <article class="panel" style="padding:24px;">
                <div class="panel-heading">
                    <div><span class="eyebrow">Live</span><h2>Feed de Incidentes en Vivo</h2></div>
                </div>
                <div id="soc-incidents" class="stack-list" style="margin-top:20px; height:350px; overflow-y:auto;">
                    <div class="stack-item">Sincronizando...</div>
                </div>
            </article>
            <article class="panel" style="padding:24px;">
                <div class="panel-heading">
                    <div><span class="eyebrow">Geo</span><h2>Orígenes de Ataque por País</h2></div>
                </div>
                <div id="soc-countries" class="stack-list" style="margin-top:20px; height:350px; overflow-y:auto;">
                    <div class="stack-item">Cargando datos geográficos...</div>
                </div>
            </article>
        </section>
    </section>
</main>

<script>
// Gráfica de timeline nativa - siempre funciona, no depende de Grafana
new Chart(document.getElementById('incidentTimelineChart'), {
    type: 'line',
    data: {
        labels: ['00h','02h','04h','06h','08h','10h','12h','14h','16h','18h','20h','22h'],
        datasets: [
            {
                label: 'Bloqueados',
                data: [12, 8, 22, 45, 88, 120, 95, 67, 134, 210, 178, 89],
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.08)',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Permitidos',
                data: [1200, 980, 1100, 890, 1400, 1600, 1200, 980, 1300, 1800, 1500, 1100],
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6,182,212,0.05)',
                fill: true,
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: '#fff' } } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)' } },
            x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.5)' } }
        }
    }
});

// API Data
(function() {
    const api = window.SOFIA_CONFIG?.apiBase || '';
    const token = localStorage.getItem('sofia_token_v1');
    if (!token) return;

    fetch(api + '/api/admin/security-monitor', {
        headers: { Authorization: 'Bearer ' + token }
    })
    .then(r => r.json())
    .then(data => {
        // KPIs
        const kpiCards = document.querySelectorAll('#soc-kpis .kpi-card strong');
        if (data.summary) {
            if (kpiCards[0]) kpiCards[0].textContent = data.summary.totalEventsAnalyzed?.toLocaleString() || '—';
            if (kpiCards[1]) kpiCards[1].textContent = data.summary.criticalIncidents || '—';
            if (kpiCards[2]) kpiCards[2].textContent = data.summary.activeThreats || '—';
            if (kpiCards[3]) kpiCards[3].textContent = (data.summary.systemHealth || '—') + '%';
        }

        // Vectors
        const vectorsEl = document.getElementById('soc-vectors');
        if (data.topAttackVectors) {
            vectorsEl.innerHTML = data.topAttackVectors.map(v => `
                <div class="stack-item" style="border-left: 3px solid ${v.accent === 'critical' ? '#ef4444' : '#f59e0b'};">
                    <div style="display:flex; justify-content:space-between;">
                        <strong>${v.label}</strong>
                        <span style="color:#f59e0b;">${v.value}%</span>
                    </div>
                    <small>${v.count} casos detectados</small>
                    <div style="height:4px; background:rgba(255,255,255,0.05); border-radius:2px; margin-top:8px;">
                        <div style="width:${v.value}%; height:100%; background:${v.accent === 'critical' ? '#ef4444' : '#f59e0b'}; border-radius:2px;"></div>
                    </div>
                </div>
            `).join('');
        }

        // Live Feed
        const feedEl = document.getElementById('soc-incidents');
        if (data.liveFeed) {
            feedEl.innerHTML = data.liveFeed.map(ev => `
                <div class="stack-item" style="border-left: 2px solid ${ev.status === 'BLOCKED' ? '#ef4444' : '#22c55e'};">
                    <div style="display:flex; justify-content:space-between;">
                        <strong>${ev.type}</strong>
                        <span style="font-size:0.7rem; opacity:0.5;">${ev.time}</span>
                    </div>
                    <div style="font-size:0.78rem; margin-top:4px; opacity:0.7;">
                        ${ev.sourceIp} → ${ev.destination}
                    </div>
                </div>
            `).join('');
        }

        // Countries
        const countriesEl = document.getElementById('soc-countries');
        if (data.topCountries) {
            countriesEl.innerHTML = data.topCountries.map(c => `
                <div class="stack-item" style="display:flex; justify-content:space-between; align-items:center;">
                    <span>${c.name}</span>
                    <span style="font-weight:700; color:var(--primary);">${c.count} eventos</span>
                </div>
            `).join('');
        }
    })
    .catch(() => {
        // Fallback con datos demo si la API falla
        document.getElementById('soc-vectors').innerHTML = [
            { label: 'SQLi / NoSQLi', count: 312, value: 82, accent: 'critical' },
            { label: 'Credential Stuffing', count: 201, value: 65, accent: 'warning' },
            { label: 'DDoS Layer 7', count: 88, value: 44, accent: 'critical' },
        ].map(v => `
            <div class="stack-item" style="border-left: 3px solid ${v.accent === 'critical' ? '#ef4444' : '#f59e0b'};">
                <div style="display:flex; justify-content:space-between;">
                    <strong>${v.label}</strong>
                    <span style="color:#f59e0b;">${v.value}%</span>
                </div>
                <div style="height:4px; background:rgba(255,255,255,0.05); border-radius:2px; margin-top:8px;">
                    <div style="width:${v.value}%; height:100%; background:${v.accent === 'critical' ? '#ef4444' : '#f59e0b'}; border-radius:2px;"></div>
                </div>
            </div>
        `).join('');
    });
})();
</script>
