<?php 
/**
 * SOFIA SOLUTIONS - Client Dashboard
 * Principal interface for corporate clients to manage their security posture.
 * 
 * This file implements a single-page application (SPA) architecture using 
 * vanilla JavaScript to communicate with the Node.js backend.
 */
$activeNav = 'dashboard'; 
?>

<!-- Security Check: Redirect non-client roles -->
<script>
(function(){
    const u = JSON.parse(localStorage.getItem('sofia_user_v1') || '{}');
    if (u.role === 'ADMIN') window.location.href = '/admin';
})();
</script>

<style>
/* CSS Implementation for specialized dashboard components */
.pay-modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); backdrop-filter:blur(8px); z-index:9999; align-items:center; justify-content:center; }
.pay-card { background:#0f0c1d; border:1px solid rgba(6,182,212,0.3); border-radius:20px; padding:40px; width:min(480px, 90vw); }

.planes-grid { align-items: start !important; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) !important; }
.plan-card { 
    height: auto !important; 
    min-height: 350px !important; 
    max-height: fit-content !important; 
    padding: 30px !important;
    display: flex !important;
    flex-direction: column !important;
}
.plan-features { flex-grow: 0 !important; margin-bottom: 24px !important; }
.plan-features li { padding: 8px 0 !important; border-bottom: 1px solid rgba(255,255,255,0.05) !important; }

.btn-primary { 
    background: #6d28d9 !important; 
    background-image: none !important; 
    color: #fff !important; 
    box-shadow: 0 4px 12px rgba(109, 40, 217, 0.4) !important;
    border: none !important;
    border-radius: 999px !important;
    font-weight: 700 !important;
    cursor: pointer !important;
}
.btn-primary:hover { background: #7c3aed !important; transform: translateY(-2px) !important; }

.ticket-row { display:flex; justify-content:space-between; align-items:center; padding:14px 16px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:10px; margin-bottom:8px; transition:background 0.15s; }
.ticket-row:hover { background:rgba(255,255,255,0.04); }
.ticket-status { font-size:0.72rem; font-weight:700; padding:3px 10px; border-radius:20px; }
.status-open { background:rgba(239,68,68,0.1); color:#ef4444; border:1px solid rgba(239,68,68,0.2); }
.status-progress { background:rgba(245,158,11,0.1); color:#f59e0b; border:1px solid rgba(245,158,11,0.2); }
.status-closed { background:rgba(34,197,94,0.1); color:#22c55e; border:1px solid rgba(34,197,94,0.2); }
</style>

<main class="app-shell readdy-dashboard">
    <!-- Main Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <?php renderLogo('brand-mark brand-mark-sidebar'); ?>
            <div class="sidebar-brand-copy"><span>Sofia Solutions</span><small>Protección 24/7</small></div>
        </div>
        <?php renderAppNav($activeNav); ?>
        <div style="margin-top:auto;padding:24px 20px;border-top:1px solid rgba(255,255,255,0.05); text-align:center; color:var(--text-muted); font-size:0.7rem;">
            &copy; 2026 Sofia Solutions
        </div>
    </aside>

    <section class="content">
        <!-- Dashboard Header & Telemetry Summary -->
        <header class="panel-header" style="margin-bottom:32px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <span class="eyebrow" data-i18n="eyebrow">Postura de Seguridad Corporativa</span>
                <h1 data-i18n="title" style="margin:8px 0 0;">Panel de Gestión: <span id="company-name-display" style="color:var(--primary);">Cargando...</span></h1>
            </div>
            <div style="display:flex; align-items:center; gap:24px;">
                <!-- SOS Panic Button -->
                <button onclick="openSOS()" style="background:#ef4444; color:#fff; border:none; padding:10px 20px; border-radius:10px; font-weight:800; cursor:pointer; box-shadow:0 0 15px rgba(239,68,68,0.4); animation: pulse 2s infinite; display:flex; align-items:center; gap:8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/><path d="m9.05 9 1.41 1.41"/><path d="M12 14v5"/><path d="m14.95 9-1.41 1.41"/></svg>
                    AYUDA URGENTE (SOS)
                </button>
                <!-- Localization Switcher -->
                <div style="display:flex;gap:4px;background:rgba(255,255,255,0.03);padding:4px;border-radius:10px;border:1px solid rgba(255,255,255,0.08);">
                    <button onclick="setLang('es')" id="btn-es" class="btn" style="min-height:30px; min-width:40px; padding:0 8px; font-size:0.7rem; border-radius:6px; background:rgba(139,92,246,0.15); color:#fff; border:1px solid rgba(139,92,246,0.3);">ES</button>
                    <button onclick="setLang('en')" id="btn-en" class="btn" style="min-height:30px; min-width:40px; padding:0 8px; font-size:0.7rem; border-radius:6px; background:transparent; color:var(--text-muted); border:none;">EN</button>
                </div>
                <!-- Security Score Component -->
                <div style="text-align:right;">
                    <div style="font-size:0.72rem;color:var(--text-muted);" data-i18n="score_label">Security Score</div>
                    <div style="font-size:2.2rem;font-weight:800;color:#22c55e;line-height:1;">94<span style="font-size:1rem;color:var(--text-muted);">/100</span></div>
                </div>
            </div>
        </header>

        <!-- KPI Grid: Dynamic values loaded via API -->
        <section class="planes-grid" style="grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:36px;">
            <div class="kpi-card" data-tone="ok" id="kpi-assets"><span class="meta-label" data-i18n="k1">Activos Protegidos</span><strong>—</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="ok" id="kpi-uptime"><span class="meta-label" data-i18n="k2">Estado Servidor</span><strong>—</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="warn" id="kpi-blocked"><span class="meta-label" data-i18n="k3">Ataques Bloqueados</span><strong>—</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="ok" id="kpi-tickets"><span class="meta-label" data-i18n="k4">Tickets Abiertos</span><strong>—</strong><div class="tone-bar"></div></div>
        </section>

        <!-- System Health & Infrastructure Monitoring -->
        <section style="margin-bottom:32px;">
            <article class="panel" style="padding:24px;">
                <div class="panel-heading" style="margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;">
                    <div><span class="eyebrow">Infraestructura</span><h2>Estado del Servidor</h2></div>
                    <span id="server-status-dot" style="display:inline-flex;align-items:center;gap:8px;font-size:0.78rem;font-weight:700;padding:6px 14px;border-radius:20px;background:rgba(34,197,94,0.1);color:#22c55e;border:1px solid rgba(34,197,94,0.2);"><span style="width:8px;height:8px;border-radius:50%;background:#22c55e;animation:pulse 2s infinite;display:inline-block;"></span> Comprobando...</span>
                </div>
                <div class="planes-grid" style="grid-template-columns:repeat(3,1fr);gap:16px;">
                    <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:12px;padding:16px;">
                        <div style="font-size:0.72rem;color:var(--text-muted);margin-bottom:6px;">API Backend</div>
                        <div id="srv-api" style="font-weight:700;font-size:1rem;">—</div>
                        <div id="srv-latency" style="font-size:0.7rem;color:var(--text-muted);margin-top:2px;">Latencia: —</div>
                    </div>
                    <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:12px;padding:16px;">
                        <div style="font-size:0.72rem;color:var(--text-muted);margin-bottom:6px;">Base de Datos</div>
                        <div id="srv-db" style="font-weight:700;font-size:1rem;">—</div>
                        <div style="font-size:0.7rem;color:var(--text-muted);margin-top:2px;">PostgreSQL 15</div>
                    </div>
                    <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:12px;padding:16px;">
                        <div style="font-size:0.72rem;color:var(--text-muted);margin-bottom:6px;">SOC Monitor</div>
                        <div id="srv-soc" style="font-weight:700;font-size:1rem;">—</div>
                        <div style="font-size:0.7rem;color:var(--text-muted);margin-top:2px;">Eventos en tiempo real</div>
                    </div>
                </div>
            </article>
        </section>

        <!-- Subscription Management Section -->
        <section style="margin-bottom:40px;">
            <div class="panel-heading" style="margin-bottom:24px;">
                <div><span class="eyebrow" data-i18n="plans_eyebrow">Suscripción</span><h2 data-i18n="plans_title">Planes de Cobertura</h2></div>
            </div>
            <div class="planes-grid" style="grid-template-columns:repeat(3,1fr);gap:24px;">
                <!-- Individual Tier -->
                <article class="plan-card" style="position:relative;">
                    <div style="position:absolute;top:16px;right:16px;background:rgba(109,40,217,0.2);color:#a78bfa;font-size:0.65rem;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid rgba(109,40,217,0.3);" data-i18n="badge_current">PLAN ACTUAL</div>
                    <span class="meta-label">Individual</span>
                    <div class="price" style="font-size:1.8rem;margin:12px 0;">€499<small style="font-size:0.9rem;color:var(--text-muted);">/mes</small></div>
                    <ul class="plan-features" style="flex-grow:0;margin-bottom:20px;">
                        <li data-i18n="f1a">Monitorización 8/5</li>
                        <li data-i18n="f1b">1 Endpoint protegido</li>
                        <li data-i18n="f1c">Alertas por email</li>
                    </ul>
                    <button class="btn btn-block" style="background:rgba(255,255,255,0.05) !important; color:rgba(255,255,255,0.4) !important; border:1px solid rgba(255,255,255,0.1) !important; cursor:default !important; padding:12px; border-radius:10px; font-weight:700;" disabled data-i18n="btn_current">Plan Activo</button>
                </article>

                <!-- Business Tier (Marketing Focus) -->
                <article class="plan-card" style="border-color:#6d28d9;background:rgba(109,40,217,0.03);position:relative;">
                    <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#6d28d9;color:#fff;font-size:0.65rem;font-weight:800;padding:4px 14px;border-radius:20px;white-space:nowrap;" data-i18n="badge_rec">RECOMENDADO</div>
                    <span class="meta-label" style="color:#a78bfa;">Business Max</span>
                    <div class="price" style="font-size:1.8rem;margin:12px 0;">€1,500<small style="font-size:0.9rem;color:var(--text-muted);">/mes</small></div>
                    <ul class="plan-features" style="flex-grow:0;margin-bottom:20px;">
                        <li data-i18n="f2a">SOC 24/7 Global</li>
                        <li data-i18n="f2b">15 Endpoints</li>
                        <li data-i18n="f2c">IR Retainer incluido</li>
                    </ul>
                    <button onclick="openPayment('Business Max','1500')" class="btn btn-block" style="background:#6d28d9 !important; color:#fff !important; border:none !important; padding:12px; border-radius:10px; font-weight:700; cursor:pointer;" data-i18n="btn_upgrade">Contratar Ahora</button>
                </article>

                <!-- Enterprise Tier -->
                <article class="plan-card" style="position:relative;">
                    <span class="meta-label">Enterprise Elite</span>
                    <div class="price" style="font-size:1.8rem;margin:12px 0;">€4,200<small style="font-size:0.9rem;color:var(--text-muted);">/mes</small></div>
                    <ul class="plan-features" style="flex-grow:0;margin-bottom:20px;">
                        <li data-i18n="f3a">SOC Dedicado 24/7</li>
                        <li data-i18n="f3b">Activos Ilimitados</li>
                        <li data-i18n="f3c">CISO virtual incluido</li>
                    </ul>
                    <button onclick="openPayment('Enterprise Elite','4200')" class="btn btn-block" style="background:#6d28d9 !important; color:#fff !important; border:none !important; padding:12px; border-radius:10px; font-weight:700; cursor:pointer;" data-i18n="btn_contact">Contactar Ventas</button>
                </article>
            </div>
        </section>

        <!-- Secondary Information Layer: Tickets, Docs & Real-time Activity -->
        <section class="planes-grid" style="grid-template-columns:1.5fr 1fr;gap:32px;">
            <div style="display:flex;flex-direction:column;gap:24px;">
                <!-- Support Ticketing System -->
                <article class="panel" style="padding:24px;">
                    <div class="panel-heading" style="margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
                        <div><span class="eyebrow" data-i18n="tickets_eyebrow">Soporte Técnico</span><h2 data-i18n="tickets_title">Mis Tickets</h2></div>
                        <button class="btn btn-primary btn-sm" style="background:#6d28d9 !important; border:none !important;" onclick="openNewTicket()" data-i18n="btn_new_ticket">+ Nuevo Ticket</button>
                    </div>
                    <div id="tickets-list"><div style="padding:20px;text-align:center;color:var(--text-muted);font-size:0.8rem;">Cargando tickets...</div></div>
                </article>

                <!-- Document Repository (Financials) -->
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

            <!-- Real-time Security Activity Stream -->
            <article class="panel" style="padding:24px;">
                <div class="panel-heading" style="margin-bottom:20px;">
                    <div><span class="eyebrow">Live</span><h2 data-i18n="feed_title">Actividad de Seguridad</h2></div>
                    <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;box-shadow:0 0 8px #22c55e;display:inline-block;animation:pulse 2s infinite;"></span>
                </div>
                <div id="live-event-feed" style="display:flex;flex-direction:column;gap:8px;height:400px;overflow-y:auto;"></div>
            </article>
        </section>

        <!-- Diagnostic Troubleshooting Console (Client Self-Service) -->
        <section style="margin-bottom:40px;">
            <article class="panel" style="padding:24px;">
                <div class="panel-heading" style="margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;">
                    <div><span class="eyebrow">Autoservicio</span><h2>Consola de Diagnóstico</h2></div>
                    <button onclick="clearConsole()" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:var(--text-muted);padding:6px 14px;border-radius:8px;cursor:pointer;font-size:0.75rem;">Limpiar</button>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;">
                    <button class="diag-btn" onclick="runDiag('ping')">🏓 Test Conectividad</button>
                    <button class="diag-btn" onclick="runDiag('session')">🔑 Ver Sesión Activa</button>
                    <button class="diag-btn" onclick="runDiag('events')">📡 Últimos Eventos</button>
                    <button class="diag-btn" onclick="runDiag('tickets')">🎫 Estado de Tickets</button>
                    <button class="diag-btn" onclick="runDiag('clear-cache')">🗑️ Limpiar Caché Local</button>
                </div>
                <div id="diag-console" style="background:#0a0e1a;border:1px solid rgba(99,102,241,0.2);border-radius:10px;padding:16px;font-family:monospace;font-size:0.78rem;color:#a5f3fc;height:200px;overflow-y:auto;line-height:1.7;"></div>
            </article>
        </section>
    </section>
</main>

<!-- SOS Emergency Modal -->
<div id="sos-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.9); backdrop-filter:blur(10px); z-index:10000; align-items:center; justify-content:center; padding:20px;">
    <div class="panel" style="width:min(450px, 100%); border:2px solid #ef4444; box-shadow:0 0 30px rgba(239,68,68,0.3);">
        <div style="text-align:center; margin-bottom:24px;">
            <div style="background:rgba(239,68,68,0.1); width:64px; height:64px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; border:1px solid rgba(239,68,68,0.3);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/><path d="m9.05 9 1.41 1.41"/><path d="M12 14v5"/><path d="m14.95 9-1.41 1.41"/></svg>
            </div>
            <h2 style="color:#ef4444; margin:0;">ALERTA DE EMERGENCIA</h2>
            <p style="color:var(--text-muted); font-size:0.9rem; margin-top:8px;">Envía una notificación prioritaria al equipo SOC.</p>
        </div>
        <div style="display:grid; gap:12px;">
            <label style="font-size:0.8rem; font-weight:700; color:var(--text-soft);">Razón de la emergencia:</label>
            <textarea id="sos-reason" style="width:100%; height:100px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:10px; color:#fff; padding:12px; font-family:inherit; font-size:0.9rem; resize:none;" placeholder="Ej: He detectado un acceso no autorizado en el servidor de producción..."></textarea>
            <div style="display:flex; gap:12px; margin-top:12px;">
                <button onclick="closeSOS()" style="flex:1; background:rgba(255,255,255,0.05); color:#fff; border:1px solid rgba(255,255,255,0.1); padding:12px; border-radius:10px; cursor:pointer; font-weight:700;">Cancelar</button>
                <button onclick="sendSOS()" style="flex:2; background:#ef4444; color:#fff; border:none; padding:12px; border-radius:10px; cursor:pointer; font-weight:800; box-shadow:0 4px 12px rgba(239,68,68,0.3);">ENVIAR ALERTA SOC</button>
            </div>
        </div>
    </div>
</div>

<script>
function openSOS() { document.getElementById('sos-modal').style.display = 'flex'; }
function closeSOS() { document.getElementById('sos-modal').style.display = 'none'; }
function sendSOS() {
    const reason = document.getElementById('sos-reason').value.trim();
    if (!reason) return alert('Por favor, indica una razón.');
    
    const user = JSON.parse(localStorage.getItem('sofia_user_v1') || '{}');
    const alertData = {
        id: Date.now(),
        client: user.name || 'Cliente Desconocido',
        reason: reason,
        timestamp: new Date().toISOString()
    };
    
    // Guardamos la alerta en localStorage para que el panel de Admin la detecte
    localStorage.setItem('sofia_sos_request', JSON.stringify(alertData));
    
    closeSOS();
    document.getElementById('sos-reason').value = '';
    
    // Feedback visual
    const t = document.createElement('div');
    t.innerHTML = '<div style="position:fixed;top:24px;left:50%;transform:translateX(-50%);z-index:99999;background:#ef4444;color:#fff;padding:16px 32px;border-radius:14px;font-weight:800;box-shadow:0 10px 40px rgba(0,0,0,0.5);text-align:center;">🚨 ALERTA ENVIADA AL SOC<br><small style="font-weight:400;opacity:0.8;">Un analista contactará contigo de inmediato.</small></div>';
    document.body.appendChild(t);
    setTimeout(()=>t.remove(), 5000);
}
</script>

<!-- Simulation: Payment Processing Modal -->
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
        <button onclick="processPayment()" class="btn btn-primary btn-block" data-i18n="pay_btn">Confirmar Pago Seguro 🔒</button>
        <p style="font-size:0.7rem;color:var(--text-muted);text-align:center;margin-top:12px;" data-i18n="pay_note">Pago procesado mediante cifrado TLS 1.3. Puedes cancelar en cualquier momento.</p>
    </div>
</div>

<style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.3}}</style>

<script>
/**
 * UI INTERACTION LOGIC
 */
function openPayment(plan, amount) {
    const a = parseInt(amount), tax = Math.round(a*0.21), total = a+tax;
    document.getElementById('pay-plan-name').textContent = plan;
    document.getElementById('pay-amount').textContent = '€'+a.toLocaleString()+'/mes';
    document.getElementById('pay-tax').textContent = '€'+tax.toLocaleString();
    document.getElementById('pay-total').textContent = '€'+total.toLocaleString()+'/mes';
    document.getElementById('pay-modal').style.display = 'flex';
}
function closePayment(){ document.getElementById('pay-modal').style.display = 'none'; }

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

/**
 * BACKEND INTEGRATION ENGINE
 */
const API = window.SOFIA_CONFIG?.apiBase || '';
const TOKEN = () => localStorage.getItem('sofia_token_v1');
function authHdr(){ return { Authorization: 'Bearer ' + TOKEN() }; }

/**
 * Initializes the dashboard with live telemetry and data
 */
(async function loadDashboard(){
    const feed = document.getElementById('live-event-feed');
    try {
        const [overviewRes, ticketsRes] = await Promise.all([
            fetch(API + '/api/admin/overview', { headers: authHdr() }),
            fetch(API + '/api/tickets',        { headers: authHdr() })
        ]);
        const overview = await overviewRes.json();
        const ticketsData = await ticketsRes.json();

        // Map KPI data from aggregate responses
        const kpiAssets = document.querySelector('#kpi-assets strong');
        const kpiBlocked = document.querySelector('#kpi-blocked strong');
        const kpiTickets = document.querySelector('#kpi-tickets strong');
        if(kpiAssets) kpiAssets.textContent = overview.secureLogins ?? '—';
        if(kpiBlocked) kpiBlocked.textContent = overview.blockedAttacks ?? '0';
        if(kpiTickets) kpiTickets.textContent = overview.openTickets ?? '0';
        
        // Identity synchronization
        document.getElementById('company-name-display').textContent =
            JSON.parse(localStorage.getItem('sofia_user_v1')||'{}').name || 'Mi Empresa';

        // Render ticket backlog
        const tickets = Array.isArray(ticketsData) ? ticketsData : (ticketsData.tickets || []);
        const list = document.getElementById('tickets-list');
        if(tickets.length === 0){
            list.innerHTML = '<div style="padding:20px;text-align:center;color:var(--text-muted);font-size:0.8rem;">No hay tickets abiertos</div>';
        } else {
            list.innerHTML = tickets.slice(0,5).map(t=>{
                const statusMap = { OPEN:'status-open', IN_PROGRESS:'status-progress', CLOSED:'status-closed' };
                const labelMap  = { OPEN:'Sin Revisar', IN_PROGRESS:'En Proceso', CLOSED:'Resuelto' };
                const ago = t.createdAt ? new Date(t.createdAt).toLocaleDateString('es-ES') : '';
                return `<div class="ticket-row">
                    <div><strong>#${t.id} — ${t.subject||t.title||'Ticket'}</strong>
                    <div style="font-size:0.75rem;color:var(--text-muted);margin-top:3px;">${ago}</div></div>
                    <span class="ticket-status ${statusMap[t.status]||'status-open'}">${labelMap[t.status]||t.status}</span>
                </div>`;
            }).join('');
        }

        // Populate Security Event stream
        if(overview.securityEvents){
            overview.securityEvents.slice(0,10).forEach(ev=>{
                const blocked = ev.action === 'BLOCKED';
                const el = document.createElement('div');
                el.style.cssText = `padding:12px 14px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.04);border-left:3px solid ${blocked?'#ef4444':'#22c55e'};border-radius:8px;`;
                el.innerHTML = `<div style="display:flex;justify-content:space-between;"><strong style="font-size:0.8rem;">${ev.type||ev.eventType||'Evento'}</strong><span style="font-size:0.68rem;color:${blocked?'#ef4444':'#22c55e'}">${blocked?'BLOQUEADO':'OK'}</span></div><div style="font-size:0.72rem;color:var(--text-muted);margin-top:2px;">${ev.endpoint||ev.sourceIp||''}</div>`;
                feed.appendChild(el);
            });
        }
    } catch(e){
        console.warn('Dashboard Telemetry Sync Error', e);
        document.getElementById('company-name-display').textContent =
            JSON.parse(localStorage.getItem('sofia_user_v1')||'{}').name || 'Demo Client';
    }

    checkServerStatus();
})();

/**
 * Monitors backend infrastructure availability and latency
 */
async function checkServerStatus(){
    const dot = document.getElementById('server-status-dot');
    const apiEl = document.getElementById('srv-api');
    const latEl = document.getElementById('srv-latency');
    const dbEl = document.getElementById('srv-db');
    const socEl = document.getElementById('srv-soc');
    try {
        const t0 = Date.now();
        const r = await fetch(API + '/api/admin/overview', { headers: authHdr() });
        const latency = Date.now() - t0;
        if(r.ok){
            apiEl.innerHTML = '<span style="color:#22c55e;">● Online</span>';
            latEl.textContent = 'Latencia: ' + latency + 'ms';
            dbEl.innerHTML = '<span style="color:#22c55e;">● Conectado</span>';
            socEl.innerHTML = '<span style="color:#22c55e;">● Activo</span>';
            dot.innerHTML = '<span style="width:8px;height:8px;border-radius:50%;background:#22c55e;animation:pulse 2s infinite;display:inline-block;"></span> OPERATIVO';
            dot.style.color = '#22c55e';
            document.querySelector('#kpi-uptime strong').textContent = '● Online';
        } else { throw new Error('Status Check Failed'); }
    } catch(e){
        apiEl.innerHTML = '<span style="color:#ef4444;">● Offline</span>';
        latEl.textContent = 'Sin respuesta';
        dbEl.innerHTML = '<span style="color:#f59e0b;">● Desconocido</span>';
        socEl.innerHTML = '<span style="color:#f59e0b;">● Sin datos</span>';
        dot.innerHTML = '⚠ SIN CONEXIÓN';
        dot.style.cssText += 'background:rgba(239,68,68,0.1);color:#ef4444;border-color:rgba(239,68,68,0.2);';
        document.querySelector('#kpi-uptime strong').textContent = '● Offline';
    }
}

/**
 * DIAGNOSTIC CONSOLE ENGINE
 */
const diagLog = (msg, color='#a5f3fc') => {
    const c = document.getElementById('diag-console');
    const line = document.createElement('div');
    const ts = new Date().toLocaleTimeString('es-ES');
    line.innerHTML = `<span style="color:#475569">[${ts}]</span> <span style="color:${color}">${msg}</span>`;
    c.appendChild(line);
    c.scrollTop = c.scrollHeight;
};

function clearConsole(){ document.getElementById('diag-console').innerHTML = ''; }

async function runDiag(cmd){
    diagLog('> ' + cmd, '#818cf8');
    if(cmd === 'ping'){
        try{
            const t0 = Date.now();
            await fetch(API + '/api/admin/overview', { headers: authHdr() });
            diagLog('✓ Conexión OK. Latencia: ' + (Date.now()-t0) + 'ms', '#22c55e');
        } catch(e){ diagLog('✗ Sin respuesta del servidor', '#ef4444'); }
    } else if(cmd === 'session'){
        const u = JSON.parse(localStorage.getItem('sofia_user_v1')||'{}');
        const t = TOKEN();
        diagLog('Usuario: ' + (u.email||'—'));
        diagLog('Rol: ' + (u.role||'—'));
        diagLog('Token: ' + (t ? t.slice(0,20)+'...' : 'NO ENCONTRADO'), t?'#22c55e':'#ef4444');
    } else if(cmd === 'events'){
        try{
            const r = await fetch(API + '/api/admin/overview', { headers: authHdr() });
            const d = await r.json();
            diagLog('Eventos recientes: ' + (d.securityEvents?.length||0));
            diagLog('Ataques bloqueados: ' + (d.blockedAttacks||0), '#f59e0b');
            diagLog('Tickets abiertos: ' + (d.openTickets||0));
        } catch(e){ diagLog('Error al obtener eventos: ' + e.message, '#ef4444'); }
    } else if(cmd === 'tickets'){
        try{
            const r = await fetch(API + '/api/tickets', { headers: authHdr() });
            const d = await r.json();
            const arr = Array.isArray(d) ? d : (d.tickets||[]);
            diagLog('Total de tickets: ' + arr.length);
            arr.slice(0,3).forEach(t => diagLog(' - #'+t.id+' ['+t.status+'] '+( t.subject||'Sin título')));
        } catch(e){ diagLog('Error: ' + e.message, '#ef4444'); }
    } else if(cmd === 'clear-cache'){
        const user = localStorage.getItem('sofia_user_v1');
        const token = localStorage.getItem('sofia_token_v1');
        localStorage.clear();
        if(user) localStorage.setItem('sofia_user_v1', user);
        if(token) localStorage.setItem('sofia_token_v1', token);
        diagLog('✓ Caché limpiado (sesión conservada)', '#22c55e');
    }
}

/**
 * INTERNATIONALIZATION (i18n)
 */
const i18n = {
    es: {
        eyebrow: "Postura de Seguridad Corporativa",
        title: "Panel de Gestión de Ciberseguridad",
        score_label: "Security Score",
        k1: "Activos Protegidos",
        k2: "Estado Servidor",
        k3: "Ataques Bloqueados",
        k4: "Tickets Abiertos",
        plans_eyebrow: "Suscripción",
        plans_title: "Planes de Cobertura",
        badge_current: "PLAN ACTUAL",
        badge_rec: "RECOMENDADO",
        f1a: "Monitorización 8/5",
        f1b: "1 Endpoint protegido",
        f1c: "Alertas por email",
        f2a: "SOC 24/7 Global",
        f2b: "15 Endpoints",
        f2c: "IR Retainer incluido",
        f3a: "SOC Dedicado 24/7",
        f3b: "Activos ilimitados",
        f3c: "CISO virtual incluido",
        btn_current: "Plan Activo",
        btn_upgrade: "Contratar Ahora",
        btn_contact: "Contactar Ventas",
        tickets_eyebrow: "Soporte Técnico",
        tickets_title: "Mis Tickets",
        btn_new_ticket: "+ Nuevo Ticket",
        docs_eyebrow: "Finanzas",
        docs_title: "Facturas e Informes",
        inv1: "Factura Abril 2026",
        inv2: "Informe Mensual Mar 2026",
        btn_dl: "Descargar",
        feed_title: "Actividad de Seguridad",
        pay_subtotal: "Subtotal mensual",
        pay_tax: "IVA (21%)",
        pay_total: "Total",
        pay_card: "Número de tarjeta",
        pay_exp: "Caducidad",
        pay_btn: "Confirmar Pago Seguro 🔒",
        pay_note: "Pago procesado mediante cifrado TLS 1.3."
    },
    en: {
        eyebrow: "Corporate Security Posture",
        title: "Cybersecurity Management Panel",
        score_label: "Security Score",
        k1: "Protected Assets",
        k2: "Server Status",
        k3: "Blocked Attacks",
        k4: "Open Tickets",
        plans_eyebrow: "Subscription",
        plans_title: "Coverage Plans",
        badge_current: "CURRENT PLAN",
        badge_rec: "RECOMMENDED",
        f1a: "8/5 Monitoring",
        f1b: "1 Protected Endpoint",
        f1c: "Email Alerts",
        f2a: "Global 24/7 SOC",
        f2b: "15 Endpoints",
        f2c: "IR Retainer included",
        f3a: "Dedicated 24/7 SOC",
        f3b: "Unlimited assets",
        f3c: "Virtual CISO included",
        btn_current: "Active Plan",
        btn_upgrade: "Subscribe Now",
        btn_contact: "Contact Sales",
        tickets_eyebrow: "Technical Support",
        tickets_title: "My Tickets",
        btn_new_ticket: "+ New Ticket",
        docs_eyebrow: "Finance",
        docs_title: "Invoices & Reports",
        inv1: "April 2026 Invoice",
        inv2: "Monthly Report Mar 2026",
        btn_dl: "Download",
        feed_title: "Security Activity",
        pay_subtotal: "Monthly subtotal",
        pay_tax: "VAT (21%)",
        pay_total: "Total",
        pay_card: "Card number",
        pay_exp: "Expiry",
        pay_btn: "Confirm Secure Payment 🔒",
        pay_note: "Payment secured with TLS 1.3 encryption."
    }
};

function setLang(l){
    localStorage.setItem('sofia_lang',l);
    document.querySelectorAll('[data-i18n]').forEach(el=>{
        const k=el.getAttribute('data-i18n');
        if(i18n[l]&&i18n[l][k]) el.textContent = i18n[l][k];
    });
    document.getElementById('btn-es').style.background = l==='es' ? 'rgba(139,92,246,0.15)' : 'transparent';
    document.getElementById('btn-en').style.background = l==='en' ? 'rgba(139,92,246,0.15)' : 'transparent';
}

(function(){ setLang(localStorage.getItem('sofia_lang')||'es'); })();
</script>
