<?php $activeNav = 'dashboard'; ?>
<script>(function(){const u=JSON.parse(localStorage.getItem('sofia_user_v1')||'{}');if(u.role==='ADMIN')window.location.href='/admin';})();</script>
<style>
.pay-modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);backdrop-filter:blur(8px);z-index:9999;align-items:center;justify-content:center;}
.pay-card{background:#0f0c1d;border:1px solid rgba(6,182,212,0.3);border-radius:20px;padding:40px;width:min(480px,90vw);}
.plan-btn{width:100%;padding:14px;border:none;border-radius:12px;font-weight:700;font-size:0.9rem;cursor:pointer;margin-top:12px;transition:all 0.2s;}
.ticket-row{display:flex;justify-content:space-between;align-items:center;padding:14px 16px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:10px;margin-bottom:8px;transition:background 0.15s;}
.ticket-row:hover{background:rgba(255,255,255,0.04);}
.ticket-status{font-size:0.72rem;font-weight:700;padding:3px 10px;border-radius:20px;}
.status-open{background:rgba(239,68,68,0.1);color:#ef4444;border:1px solid rgba(239,68,68,0.2);}
.status-progress{background:rgba(245,158,11,0.1);color:#f59e0b;border:1px solid rgba(245,158,11,0.2);}
.status-closed{background:rgba(34,197,94,0.1);color:#22c55e;border:1px solid rgba(34,197,94,0.2);}
</style>

<main class="app-shell readdy-dashboard">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <?php renderLogo('brand-mark brand-mark-sidebar'); ?>
            <div class="sidebar-brand-copy"><span>Sofia Solutions</span><small>Protección 24/7</small></div>
        </div>
        <?php renderAppNav($activeNav); ?>
        <div style="margin-top:auto;padding:20px;border-top:1px solid rgba(255,255,255,0.05);">
            <div style="display:flex;gap:8px;background:rgba(0,0,0,0.2);padding:4px;border-radius:8px;">
                <button onclick="setLang('es')" id="btn-es" style="flex:1;border:none;padding:6px;border-radius:6px;font-size:0.7rem;cursor:pointer;font-weight:700;">ES</button>
                <button onclick="setLang('en')" id="btn-en" style="flex:1;border:none;padding:6px;border-radius:6px;font-size:0.7rem;cursor:pointer;font-weight:700;">EN</button>
            </div>
        </div>
    </aside>

    <section class="content">
        <header class="panel-header" style="margin-bottom:32px;">
            <div>
                <span class="eyebrow" data-i18n="eyebrow">Postura de Seguridad Corporativa</span>
                <h1 data-i18n="title">Panel de Gestión de Ciberseguridad</h1>
            </div>
            <div style="display:flex;align-items:center;gap:20px;">
                <div style="text-align:right;">
                    <div style="font-size:0.72rem;color:var(--text-muted);" data-i18n="score_label">Security Score</div>
                    <div style="font-size:2.2rem;font-weight:800;color:#22c55e;line-height:1;">94<span style="font-size:1rem;color:var(--text-muted);">/100</span></div>
                </div>
            </div>
        </header>

        <!-- KPIs -->
        <section class="planes-grid" style="grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:36px;">
            <div class="kpi-card" data-tone="ok"><span class="meta-label" data-i18n="k1">Activos Protegidos</span><strong>12</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="ok"><span class="meta-label" data-i18n="k2">Uptime</span><strong>99.9%</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="warn"><span class="meta-label" data-i18n="k3">Vulnerabilidades</span><strong>2</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="ok"><span class="meta-label" data-i18n="k4">SLA Cumplido</span><strong>100%</strong><div class="tone-bar"></div></div>
        </section>

        <!-- Plans -->
        <section style="margin-bottom:40px;">
            <div class="panel-heading" style="margin-bottom:24px;">
                <div><span class="eyebrow" data-i18n="plans_eyebrow">Suscripción</span><h2 data-i18n="plans_title">Planes de Cobertura</h2></div>
            </div>
            <div class="planes-grid" style="grid-template-columns:repeat(3,1fr);gap:24px;">
                <!-- Individual (current) -->
                <article class="plan-card" style="position:relative;">
                    <div style="position:absolute;top:16px;right:16px;background:rgba(6,182,212,0.15);color:#06b6d4;font-size:0.65rem;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid rgba(6,182,212,0.3);" data-i18n="badge_current">PLAN ACTUAL</div>
                    <span class="meta-label">Individual</span>
                    <div class="price" style="font-size:1.8rem;margin:12px 0;">€499<small style="font-size:0.9rem;color:var(--text-muted);">/mes</small></div>
                    <ul class="plan-features" style="margin-bottom:20px;"><li data-i18n="f1a">Monitorización 8/5</li><li data-i18n="f1b">1 Endpoint protegido</li><li data-i18n="f1c">Alertas por email</li></ul>
                    <button class="btn btn-secondary btn-sm" style="width:100%;opacity:0.6;cursor:default;" data-i18n="btn_current">Plan Activo</button>
                </article>
                <!-- Business (recommended) -->
                <article class="plan-card" style="border-color:var(--primary);background:rgba(6,182,212,0.03);position:relative;">
                    <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--primary);color:#000;font-size:0.65rem;font-weight:800;padding:4px 14px;border-radius:20px;white-space:nowrap;" data-i18n="badge_rec">RECOMENDADO</div>
                    <span class="meta-label" style="color:var(--primary);">Business Max</span>
                    <div class="price" style="font-size:1.8rem;margin:12px 0;">€1,500<small style="font-size:0.9rem;color:var(--text-muted);">/mes</small></div>
                    <ul class="plan-features" style="margin-bottom:20px;"><li data-i18n="f2a">SOC 24/7 Global</li><li data-i18n="f2b">15 Endpoints</li><li data-i18n="f2c">IR Retainer incluido</li></ul>
                    <button onclick="openPayment('Business Max','1500')" class="btn btn-primary btn-sm" style="width:100%;" data-i18n="btn_upgrade">Contratar Ahora</button>
                </article>
                <!-- Enterprise -->
                <article class="plan-card">
                    <span class="meta-label">Enterprise Elite</span>
                    <div class="price" style="font-size:1.8rem;margin:12px 0;">€4,200<small style="font-size:0.9rem;color:var(--text-muted);">/mes</small></div>
                    <ul class="plan-features" style="margin-bottom:20px;"><li data-i18n="f3a">SOC Dedicado 24/7</li><li data-i18n="f3b">Activos ilimitados</li><li data-i18n="f3c">CISO virtual incluido</li></ul>
                    <button onclick="openPayment('Enterprise Elite','4200')" class="btn btn-secondary btn-sm" style="width:100%;" data-i18n="btn_contact">Contactar Ventas</button>
                </article>
            </div>
        </section>

        <!-- Tickets + Docs + Feed -->
        <section class="planes-grid" style="grid-template-columns:1.5fr 1fr;gap:32px;">
            <div style="display:flex;flex-direction:column;gap:24px;">
                <!-- TICKETS -->
                <article class="panel" style="padding:24px;">
                    <div class="panel-heading" style="margin-bottom:20px;">
                        <div><span class="eyebrow" data-i18n="tickets_eyebrow">Soporte Técnico</span><h2 data-i18n="tickets_title">Mis Tickets</h2></div>
                        <button class="btn btn-primary btn-sm" onclick="openNewTicket()" data-i18n="btn_new_ticket">+ Nuevo Ticket</button>
                    </div>
                    <div id="tickets-list">
                        <div class="ticket-row">
                            <div><strong>#2201 — Análisis de Intrusión SQLi</strong><div style="font-size:0.75rem;color:var(--text-muted);margin-top:3px;">Abierto hace 15 min · Prioridad: Alta</div></div>
                            <span class="ticket-status status-open" data-i18n="s_open">Sin Revisar</span>
                        </div>
                        <div class="ticket-row">
                            <div><strong>#2195 — Configuración de WAF personalizado</strong><div style="font-size:0.75rem;color:var(--text-muted);margin-top:3px;">Actualizado hace 3h · Analista: Sofia G.</div></div>
                            <span class="ticket-status status-progress" data-i18n="s_prog">En Proceso</span>
                        </div>
                        <div class="ticket-row">
                            <div><strong>#2150 — Renovación de certificado SSL</strong><div style="font-size:0.75rem;color:var(--text-muted);margin-top:3px;">Cerrado ayer</div></div>
                            <span class="ticket-status status-closed" data-i18n="s_done">Resuelto</span>
                        </div>
                    </div>
                </article>

                <!-- Documents -->
                <article class="panel" style="padding:24px;">
                    <div class="panel-heading" style="margin-bottom:20px;">
                        <div><span class="eyebrow" data-i18n="docs_eyebrow">Finanzas</span><h2 data-i18n="docs_title">Facturas e Informes</h2></div>
                    </div>
                    <div class="stack-list">
                        <div class="stack-item" style="display:flex;justify-content:space-between;align-items:center;">
                            <div><strong data-i18n="inv1">Factura Abril 2026</strong><div style="font-size:0.75rem;color:var(--text-muted);">PDF · 1.2 MB</div></div>
                            <a href="/download.php?file=invoice_1023.pdf" class="btn btn-outline btn-sm" data-i18n="btn_dl">Descargar</a>
                        </div>
                        <div class="stack-item" style="display:flex;justify-content:space-between;align-items:center;">
                            <div><strong data-i18n="inv2">Informe Mensual Mar 2026</strong><div style="font-size:0.75rem;color:var(--text-muted);">PDF · 3.4 MB</div></div>
                            <a href="/download.php?file=report_march_2026.pdf" class="btn btn-outline btn-sm" data-i18n="btn_dl">Descargar</a>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Live Feed -->
            <article class="panel" style="padding:24px;">
                <div class="panel-heading" style="margin-bottom:20px;">
                    <div><span class="eyebrow">Live</span><h2 data-i18n="feed_title">Actividad de Seguridad</h2></div>
                    <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;box-shadow:0 0 8px #22c55e;display:inline-block;animation:pulse 2s infinite;"></span>
                </div>
                <div id="live-event-feed" style="display:flex;flex-direction:column;gap:8px;height:400px;overflow-y:auto;"></div>
            </article>
        </section>
    </section>
</main>

<!-- PAYMENT MODAL -->
<div class="pay-modal" id="pay-modal">
    <div class="pay-card">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:24px;">
            <div><span class="eyebrow">Checkout Seguro</span><h2 id="pay-plan-name" style="margin:4px 0;font-size:1.4rem;">Plan</h2></div>
            <button onclick="closePayment()" style="background:none;border:none;color:#fff;font-size:1.3rem;cursor:pointer;">&times;</button>
        </div>
        <div style="background:rgba(6,182,212,0.05);border:1px solid rgba(6,182,212,0.15);border-radius:12px;padding:16px;margin-bottom:20px;">
            <div style="display:flex;justify-content:space-between;"><span style="color:var(--text-muted);" data-i18n="pay_subtotal">Subtotal mensual</span><strong id="pay-amount"></strong></div>
            <div style="display:flex;justify-content:space-between;margin-top:8px;"><span style="color:var(--text-muted);" data-i18n="pay_tax">IVA (21%)</span><strong id="pay-tax"></strong></div>
            <div style="display:flex;justify-content:space-between;margin-top:12px;padding-top:12px;border-top:1px solid rgba(255,255,255,0.05);"><span style="font-weight:700;" data-i18n="pay_total">Total</span><strong id="pay-total" style="color:var(--primary);font-size:1.1rem;"></strong></div>
        </div>
        <div style="display:grid;gap:12px;margin-bottom:20px;">
            <div><label style="font-size:0.72rem;color:var(--text-muted);display:block;margin-bottom:5px;" data-i18n="pay_card">Número de tarjeta</label><input style="width:100%;padding:12px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;color:#fff;box-sizing:border-box;" placeholder="4532 •••• •••• 8910"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div><label style="font-size:0.72rem;color:var(--text-muted);display:block;margin-bottom:5px;" data-i18n="pay_exp">Caducidad</label><input style="width:100%;padding:12px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;color:#fff;box-sizing:border-box;" placeholder="MM/AA"></div>
                <div><label style="font-size:0.72rem;color:var(--text-muted);display:block;margin-bottom:5px;">CVV</label><input style="width:100%;padding:12px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;color:#fff;box-sizing:border-box;" placeholder="•••"></div>
            </div>
        </div>
        <button onclick="processPayment()" class="plan-btn" style="background:linear-gradient(135deg,#06b6d4,#7c3aed);color:#fff;" data-i18n="pay_btn">Confirmar Pago Seguro 🔒</button>
        <p style="font-size:0.7rem;color:var(--text-muted);text-align:center;margin-top:12px;" data-i18n="pay_note">Pago procesado mediante cifrado TLS 1.3. Puedes cancelar en cualquier momento.</p>
    </div>
</div>

<style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.3}}</style>

<script>
function openPayment(plan, amount) {
    const a = parseInt(amount), tax = Math.round(a*0.21), total = a+tax;
    document.getElementById('pay-plan-name').textContent = plan;
    document.getElementById('pay-amount').textContent = '€'+a.toLocaleString()+'/mes';
    document.getElementById('pay-tax').textContent = '€'+tax.toLocaleString();
    document.getElementById('pay-total').textContent = '€'+total.toLocaleString()+'/mes';
    document.getElementById('pay-modal').style.display = 'flex';
}
function closePayment(){ document.getElementById('pay-modal').style.display='none'; }
function processPayment(){
    closePayment();
    const t = document.createElement('div');
    t.innerHTML = '<div style="position:fixed;top:24px;right:24px;z-index:99999;background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#22c55e;padding:16px 24px;border-radius:14px;font-weight:700;backdrop-filter:blur(10px);">✓ Plan actualizado correctamente</div>';
    document.body.appendChild(t);
    setTimeout(()=>t.remove(), 3500);
}
function openNewTicket(){
    const subject = prompt('Describe brevemente el problema:');
    if(!subject) return;
    const list = document.getElementById('tickets-list');
    const row = document.createElement('div');
    row.className = 'ticket-row';
    row.innerHTML = `<div><strong>#${Math.floor(Math.random()*1000)+2300} — ${subject}</strong><div style="font-size:0.75rem;color:var(--text-muted);margin-top:3px;">Abierto ahora</div></div><span class="ticket-status status-open">Sin Revisar</span>`;
    list.prepend(row);
}

// Live feed
(function(){
    const api = window.SOFIA_CONFIG?.apiBase||'';
    const token = localStorage.getItem('sofia_token_v1');
    const feed = document.getElementById('live-event-feed');
    function makeFeedItem(type, endpoint, blocked){
        const el = document.createElement('div');
        el.style.cssText = `padding:12px 14px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.04);border-left:3px solid ${blocked?'#ef4444':'#22c55e'};border-radius:8px;`;
        el.innerHTML = `<div style="display:flex;justify-content:space-between;"><strong style="font-size:0.8rem;">${type}</strong><span style="font-size:0.68rem;color:${blocked?'#ef4444':'#22c55e'}">${blocked?'BLOQUEADO':'OK'}</span></div><div style="font-size:0.72rem;color:var(--text-muted);margin-top:2px;">${endpoint}</div>`;
        return el;
    }
    if(token){
        fetch(api+'/api/admin/overview',{headers:{Authorization:'Bearer '+token}})
        .then(r=>r.json()).then(data=>{
            if(data.securityEvents){
                data.securityEvents.slice(0,10).forEach(ev=>{
                    feed.appendChild(makeFeedItem(ev.type, ev.endpoint, ev.action==='BLOCKED'));
                });
            }
        }).catch(()=>{
            ['Brute Force','SQLi Attempt','Port Scan','DDoS L7','XSS Probe'].forEach((t,i)=>{
                feed.appendChild(makeFeedItem(t,'iberdrola-fw-edge-lb0'+i,i%3!==0));
            });
        });
    }
})();

// i18n
const i18n={es:{eyebrow:"Postura de Seguridad Corporativa",title:"Panel de Gestión de Ciberseguridad",score_label:"Security Score",k1:"Activos Protegidos",k2:"Uptime",k3:"Vulnerabilidades",k4:"SLA Cumplido",plans_eyebrow:"Suscripción",plans_title:"Planes de Cobertura",badge_current:"PLAN ACTUAL",badge_rec:"RECOMENDADO",f1a:"Monitorización 8/5",f1b:"1 Endpoint protegido",f1c:"Alertas por email",f2a:"SOC 24/7 Global",f2b:"15 Endpoints",f2c:"IR Retainer incluido",f3a:"SOC Dedicado 24/7",f3b:"Activos ilimitados",f3c:"CISO virtual incluido",btn_current:"Plan Activo",btn_upgrade:"Contratar Ahora",btn_contact:"Contactar Ventas",tickets_eyebrow:"Soporte Técnico",tickets_title:"Mis Tickets",btn_new_ticket:"+ Nuevo Ticket",s_open:"Sin Revisar",s_prog:"En Proceso",s_done:"Resuelto",docs_eyebrow:"Finanzas",docs_title:"Facturas e Informes",inv1:"Factura Abril 2026",inv2:"Informe Mensual Mar 2026",btn_dl:"Descargar",feed_title:"Actividad de Seguridad",pay_subtotal:"Subtotal mensual",pay_tax:"IVA (21%)",pay_total:"Total",pay_card:"Número de tarjeta",pay_exp:"Caducidad",pay_btn:"Confirmar Pago Seguro 🔒",pay_note:"Pago procesado mediante cifrado TLS 1.3."},en:{eyebrow:"Corporate Security Posture",title:"Cybersecurity Management Panel",score_label:"Security Score",k1:"Protected Assets",k2:"Uptime",k3:"Vulnerabilities",k4:"SLA Compliance",plans_eyebrow:"Subscription",plans_title:"Coverage Plans",badge_current:"CURRENT PLAN",badge_rec:"RECOMMENDED",f1a:"8/5 Monitoring",f1b:"1 Protected Endpoint",f1c:"Email Alerts",f2a:"Global 24/7 SOC",f2b:"15 Endpoints",f2c:"IR Retainer included",f3a:"Dedicated 24/7 SOC",f3b:"Unlimited assets",f3c:"Virtual CISO included",btn_current:"Active Plan",btn_upgrade:"Subscribe Now",btn_contact:"Contact Sales",tickets_eyebrow:"Technical Support",tickets_title:"My Tickets",btn_new_ticket:"+ New Ticket",s_open:"Unreviewed",s_prog:"In Progress",s_done:"Resolved",docs_eyebrow:"Finance",docs_title:"Invoices & Reports",inv1:"April 2026 Invoice",inv2:"Monthly Report Mar 2026",btn_dl:"Download",feed_title:"Security Activity",pay_subtotal:"Monthly subtotal",pay_tax:"VAT (21%)",pay_total:"Total",pay_card:"Card number",pay_exp:"Expiry",pay_btn:"Confirm Secure Payment 🔒",pay_note:"Payment secured with TLS 1.3 encryption."}};
function setLang(l){localStorage.setItem('sofia_lang',l);document.querySelectorAll('[data-i18n]').forEach(el=>{const k=el.getAttribute('data-i18n');if(i18n[l]&&i18n[l][k])el.textContent=i18n[l][k];});document.getElementById('btn-es').style.background=l==='es'?'var(--primary)':'rgba(255,255,255,0.05)';document.getElementById('btn-en').style.background=l==='en'?'var(--primary)':'rgba(255,255,255,0.05)';}
(function(){setLang(localStorage.getItem('sofia_lang')||'es');})();
</script>
