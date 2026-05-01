<?php $activeNav = 'admin-dashboard'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<main class="app-shell readdy-dashboard" id="main-app">
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
        <header class="panel-header" style="margin-bottom:32px;">
            <div>
                <span class="eyebrow" data-i18n="eyebrow">Operaciones Globales</span>
                <h1 data-i18n="title">Panel de Operaciones</h1>
                <p class="panel-header-copy" data-i18n="copy">Visión unificada de ciberseguridad multi-cliente.</p>
            </div>
            <span style="padding:8px 16px; border-radius:20px; font-size:0.75rem; font-weight:700; background:rgba(34,197,94,0.1); color:#22c55e; border:1px solid rgba(34,197,94,0.2);">● SISTEMA OPERATIVO</span>
        </header>

        <!-- KPIs -->
        <section class="planes-grid" style="grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:32px;">
            <div class="kpi-card" data-tone="ok"><span class="meta-label" data-i18n="k1">Eventos (24h)</span><strong>4.1M</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="bad"><span class="meta-label" data-i18n="k2">Incidentes Activos</span><strong>3</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="ok"><span class="meta-label" data-i18n="k3">Clientes Activos</span><strong>142</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="ok"><span class="meta-label" data-i18n="k4">Disponibilidad</span><strong>100%</strong><div class="tone-bar"></div></div>
        </section>

        <!-- Charts -->
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

        <!-- Client Table -->
        <section class="panel" style="padding:24px;margin-bottom:32px;">
            <div class="panel-heading" style="margin-bottom:20px;">
                <div><span class="eyebrow">Portfolio</span><h2 data-i18n="table_title">Postura de Seguridad por Cliente</h2></div>
            </div>
            <table style="width:100%;border-collapse:collapse;font-size:0.88rem;">
                <thead><tr style="text-align:left;color:var(--text-muted);border-bottom:1px solid rgba(255,255,255,0.05);">
                    <th style="padding:14px 12px;" data-i18n="th1">Cliente</th>
                    <th style="padding:14px 12px;" data-i18n="th2">Estado</th>
                    <th style="padding:14px 12px;" data-i18n="th3">Score</th>
                    <th style="padding:14px 12px;" data-i18n="th4">Sector</th>
                    <th style="padding:14px 12px;" data-i18n="th5">Acción</th>
                </tr></thead>
                <tbody id="client-table-body"></tbody>
            </table>
        </section>

        <!-- Tickets -->
        <section class="planes-grid" style="grid-template-columns:repeat(3,1fr);gap:24px;">
            <article class="panel" style="border-top:3px solid #ef4444;padding:20px;">
                <h3 style="font-size:0.85rem;margin-bottom:14px;color:#ef4444;" data-i18n="tnew">Sin Revisar</h3>
                <div class="stack-list">
                    <div class="stack-item"><strong>Iberdrola: SQLi detectado</strong><small>#2201 · hace 15m</small></div>
                    <div class="stack-item"><strong>MAPFRE: Brute Force</strong><small>#2205 · hace 1h</small></div>
                </div>
            </article>
            <article class="panel" style="border-top:3px solid #f59e0b;padding:20px;">
                <h3 style="font-size:0.85rem;margin-bottom:14px;color:#f59e0b;" data-i18n="tinprog">En Proceso</h3>
                <div class="stack-list">
                    <div class="stack-item"><strong>Repsol: Análisis OT</strong><small>#2190 · Analista: Carlos R.</small></div>
                    <div class="stack-item"><strong>Nordex: Mitigación DDoS</strong><small>#2195 · Analista: Sofia G.</small></div>
                </div>
            </article>
            <article class="panel" style="border-top:3px solid #22c55e;padding:20px;">
                <h3 style="font-size:0.85rem;margin-bottom:14px;color:#22c55e;" data-i18n="tdone">Resueltos</h3>
                <div class="stack-list">
                    <div class="stack-item" style="opacity:0.55;"><strong>Mercadona: Log4j Fix</strong><small>#2180 · Cerrado ayer</small></div>
                    <div class="stack-item" style="opacity:0.55;"><strong>Iberdrola: Falso positivo</strong><small>#2175 · Cerrado ayer</small></div>
                </div>
            </article>
        </section>
    </section>
</main>

<!-- Client Detail Modal -->
<div id="client-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);backdrop-filter:blur(8px);z-index:9999;align-items:center;justify-content:center;padding:40px;">
    <div class="panel" style="width:min(900px,100%);max-height:90vh;overflow-y:auto;position:relative;border:1px solid rgba(6,182,212,0.3);">
        <button onclick="closeModal()" style="position:absolute;top:20px;right:20px;background:none;border:none;color:#fff;cursor:pointer;font-size:1.5rem;">&times;</button>
        <div id="modal-content" style="padding:40px;"></div>
    </div>
</div>

<script>
const clients = {
    "Iberdrola S.A.": { sector:"Energía & OT", score:74, status:"ALERTA", color:"#ef4444", assets:48, sla:"99.8%", alerts:["SQLi en portal cliente","Fuerza bruta VPN"], traffic:[65,59,80,81,56,55,40] },
    "MAPFRE Seguros": { sector:"Finanzas", score:92, status:"PROTEGIDO", color:"#22c55e", assets:124, sla:"100%", alerts:["Escaneo de puertos bloqueado"], traffic:[28,48,40,19,86,27,90] },
    "Mercadona S.A.": { sector:"Retail & Supply", score:85, status:"REVISIÓN", color:"#f59e0b", assets:210, sla:"99.9%", alerts:["Anomalía tráfico L7"], traffic:[45,25,16,36,67,18,76] }
};

document.getElementById('client-table-body').innerHTML = Object.entries(clients).map(([name, d]) => `
    <tr style="border-bottom:1px solid rgba(255,255,255,0.03);cursor:pointer;transition:background 0.15s;" 
        onclick="openClient('${name}')" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background=''">
        <td style="padding:16px 12px;"><strong>${name}</strong></td>
        <td style="padding:16px 12px;"><span style="color:${d.color};font-weight:700;">● ${d.status}</span></td>
        <td style="padding:16px 12px;"><strong style="color:${d.color};">${d.score}%</strong></td>
        <td style="padding:16px 12px;color:var(--text-muted);">${d.sector}</td>
        <td style="padding:16px 12px;"><button class="btn btn-outline btn-sm" onclick="event.stopPropagation();openClient('${name}')">Expediente</button></td>
    </tr>`).join('');

let chartInst = null;
function openClient(name) {
    const d = clients[name];
    document.getElementById('modal-content').innerHTML = `
        <span class="eyebrow">${d.sector}</span>
        <h2 style="font-size:1.8rem;margin-bottom:24px;">${name}</h2>
        <div class="planes-grid" style="grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px;">
            <div class="kpi-card" data-tone="${d.score<80?'bad':'ok'}"><span class="meta-label">Security Score</span><strong>${d.score}%</strong></div>
            <div class="kpi-card"><span class="meta-label">Activos</span><strong>${d.assets}</strong></div>
            <div class="kpi-card"><span class="meta-label">SLA</span><strong>${d.sla}</strong></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;">
            <div><h3 style="font-size:0.9rem;margin-bottom:12px;">Alertas Activas</h3>
                <div class="stack-list">${d.alerts.map(a=>`<div class="stack-item" style="border-left:3px solid #ef4444;">${a}</div>`).join('')}</div>
            </div>
            <div><h3 style="font-size:0.9rem;margin-bottom:12px;">Actividad de Red (7 días)</h3>
                <canvas id="clientChart" style="max-height:200px;"></canvas>
            </div>
        </div>`;
    document.getElementById('client-modal').style.display = 'flex';
    if(chartInst) chartInst.destroy();
    chartInst = new Chart(document.getElementById('clientChart'), {
        type:'line', data:{labels:['L','M','X','J','V','S','D'],datasets:[{label:'Gbps',data:d.traffic,borderColor:'#06b6d4',backgroundColor:'rgba(6,182,212,0.08)',fill:true,tension:0.4}]},
        options:{plugins:{legend:{display:false}},scales:{y:{display:false},x:{grid:{display:false},ticks:{color:'rgba(255,255,255,0.4)'}}}}
    });
}
function closeModal(){ document.getElementById('client-modal').style.display='none'; }

window.onload = function() {
    new Chart(document.getElementById('globalTrafficChart'),{type:'bar',data:{labels:['DDoS','SQLi','Brute Force','Malware','Botnets','XSS'],datasets:[{data:[1200,1900,3000,500,2400,800],backgroundColor:'rgba(6,182,212,0.5)',borderColor:'#06b6d4',borderWidth:1}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'rgba(255,255,255,0.04)'},ticks:{color:'rgba(255,255,255,0.4)'}},x:{grid:{display:false},ticks:{color:'rgba(255,255,255,0.4)'}}}}});
    new Chart(document.getElementById('riskChart'),{type:'doughnut',data:{labels:['Crítico','Alto','Medio','Bajo'],datasets:[{data:[15,25,40,20],backgroundColor:['#ef4444','#f59e0b','#06b6d4','#22c55e'],borderWidth:0}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{color:'#fff',padding:16,font:{size:11}}}}}});
};

const i18n={es:{eyebrow:"Operaciones Globales",title:"Panel de Operaciones",copy:"Visión unificada de ciberseguridad multi-cliente.",k1:"Eventos (24h)",k2:"Incidentes Activos",k3:"Clientes Activos",k4:"Disponibilidad",chart1:"Ataques por Vector (24h)",chart2:"Distribución de Alertas",table_title:"Postura de Seguridad por Cliente",th1:"Cliente",th2:"Estado",th3:"Score",th4:"Sector",th5:"Acción",tnew:"Sin Revisar",tinprog:"En Proceso",tdone:"Resueltos"},en:{eyebrow:"Global Operations",title:"Operations Panel",copy:"Unified view of multi-client cybersecurity.",k1:"Events (24h)",k2:"Active Incidents",k3:"Active Clients",k4:"Availability",chart1:"Attacks by Vector (24h)",chart2:"Alert Distribution",table_title:"Security Posture by Client",th1:"Client",th2:"Status",th3:"Score",th4:"Sector",th5:"Action",tnew:"Unreviewed",tinprog:"In Progress",tdone:"Resolved"}};
function setLang(l){localStorage.setItem('sofia_lang',l);document.querySelectorAll('[data-i18n]').forEach(el=>{const k=el.getAttribute('data-i18n');if(i18n[l][k])el.textContent=i18n[l][k];});document.getElementById('btn-es').style.background=l==='es'?'var(--primary)':'rgba(255,255,255,0.05)';document.getElementById('btn-en').style.background=l==='en'?'var(--primary)':'rgba(255,255,255,0.05)';}
(function(){setLang(localStorage.getItem('sofia_lang')||'es');})();
</script>
