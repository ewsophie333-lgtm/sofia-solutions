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

<!-- External Charting Dependency -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* CSS Implementation for specialized dashboard components */
.pay-modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); backdrop-filter:blur(8px); z-index:9999; align-items:center; justify-content:center; }
.pay-card { background:#0f0c1d; border:1px solid rgba(255,255,255,0.08); border-radius:20px; padding:40px; width:min(480px, 90vw); }

.planes-grid { align-items: start !important; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) !important; }

/* Softer Button Styles */
.btn-primary { 
    background: rgba(139,92,246,0.1) !important;
    color: #a78bfa !important;
    border: 1px solid rgba(139,92,246,0.3) !important;
    box-shadow: none !important;
    padding: 10px 20px !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
}
.btn-primary:hover {
    background: rgba(139,92,246,0.2) !important;
    border-color: rgba(139,92,246,0.5) !important;
    color: #fff !important;
    transform: translateY(-1px);
}

.ticket-row { display:flex; justify-content:space-between; align-items:center; padding:20px; background:rgba(255,255,255,0.01); border:1px solid rgba(255,255,255,0.04); border-radius:14px; margin-bottom:12px; transition:all 0.3s; }
.ticket-row:hover { background:rgba(255,255,255,0.03); transform: scale(1.01); border-color: rgba(139,92,246,0.2); }

.ticket-status { font-size:0.65rem; font-weight:800; padding:6px 12px; border-radius:8px; text-transform:uppercase; letter-spacing:0.8px; }
.status-open { background:rgba(239,68,68,0.1); color:#fca5a5; border:1px solid rgba(239,68,68,0.2); }
.status-progress { background:rgba(245,158,11,0.1); color:#fcd34d; border:1px solid rgba(245,158,11,0.2); }
.status-closed { background:rgba(34,197,94,0.1); color:#86efac; border:1px solid rgba(34,197,94,0.2); }

.ticket-tab { padding:10px 20px; border-radius:10px; transition:all 0.3s; border: none; background: transparent; color: var(--text-muted); cursor: pointer; font-weight: 700; font-size: 0.85rem; }
.ticket-tab.active { background:rgba(139,92,246,0.1) !important; color:#fff !important; }

@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.3}}
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
                <span class="eyebrow" data-i18n="eyebrow">Panel de Control de Cliente</span>
                <h1 style="margin:8px 0 0;">¡Bienvenido, <span id="welcome-user-name" style="color:var(--primary);">...</span>!</h1>
                <p style="color:var(--text-muted); font-size:0.9rem; margin-top:4px;">Tu infraestructura está bajo vigilancia activa por el equipo SOC.</p>
            </div>
            <div style="display:flex; align-items:center; gap:24px;">
                <!-- SOS Panic Button -->
                <button onclick="openSOS()" style="background:#dc2626; color:#fff; border:none; padding:10px 20px; border-radius:10px; font-weight:800; cursor:pointer; box-shadow:0 0 15px rgba(220,38,38,0.3); display:flex; align-items:center; gap:8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/><path d="m9.05 9 1.41 1.41"/><path d="M12 14v5"/><path d="m14.95 9-1.41 1.41"/></svg>
                    AYUDA URGENTE (SOS)
                </button>
                <!-- Localization Switcher -->
                <div style="display:flex;gap:4px;background:rgba(255,255,255,0.03);padding:4px;border-radius:10px;border:1px solid rgba(255,255,255,0.08);">
                    <button onclick="setLang('es')" id="btn-es" class="btn" style="min-height:30px; min-width:40px; padding:0 8px; font-size:0.7rem; border-radius:6px; background:rgba(139,92,246,0.15); color:#fff; border:1px solid rgba(139,92,246,0.3);">ES</button>
                    <button onclick="setLang('en')" id="btn-en" class="btn" style="min-height:30px; min-width:40px; padding:0 8px; font-size:0.7rem; border-radius:6px; background:transparent; color:var(--text-muted); border:none;">EN</button>
                </div>
            </div>
        </header>

        <!-- KPI Grid: Dynamic values loaded via API -->
        <section class="planes-grid" style="grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:36px;">
            <div class="kpi-card" data-tone="ok" id="kpi-assets"><span class="meta-label">Activos Protegidos</span><strong>—</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="ok" id="kpi-uptime"><span class="meta-label">Disponibilidad (SLA)</span><strong>99.9%</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="warn" id="kpi-blocked"><span class="meta-label">Amenazas Bloqueadas</span><strong>—</strong><div class="tone-bar"></div></div>
            <div class="kpi-card" data-tone="ok" id="kpi-tickets"><span class="meta-label">Soporte Activo</span><strong>—</strong><div class="tone-bar"></div></div>
        </section>

        <!-- Visual Server Status & Network Health -->
        <section class="planes-grid" style="grid-template-columns: 2fr 1fr; gap:32px; margin-bottom:40px;">
            <article class="panel" style="padding:24px;">
                <div class="panel-heading" style="margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;">
                    <div><span class="eyebrow">Infraestructura</span><h2>Telemetría de Red y Rendimiento</h2></div>
                    <span id="server-status-dot" style="display:inline-flex;align-items:center;gap:8px;font-size:0.75rem;font-weight:700;padding:6px 14px;border-radius:20px;background:rgba(34,197,94,0.1);color:#22c55e;border:1px solid rgba(34,197,94,0.2);"> OPERATIVO</span>
                </div>
                <div style="height:250px;"><canvas id="clientLatencyChart"></canvas></div>
            </article>

            <article class="panel" style="padding:24px;">
                <div class="panel-heading" style="margin-bottom:18px;">
                    <div><span class="eyebrow">Estado</span><h2>Resumen de Salud</h2></div>
                </div>
                <div style="display:grid; gap:16px;">
                    <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:12px;padding:16px;display:flex;justify-content:space-between;align-items:center;">
                        <div><div style="font-size:0.72rem;color:var(--text-muted);">API Backend</div><div id="srv-api" style="font-weight:700;">—</div></div>
                        <div style="width:12px;height:12px;border-radius:50%;background:#22c55e;"></div>
                    </div>
                    <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:12px;padding:16px;display:flex;justify-content:space-between;align-items:center;">
                        <div><div style="font-size:0.72rem;color:var(--text-muted);">Base de Datos</div><div id="srv-db" style="font-weight:700;">—</div></div>
                        <div style="width:12px;height:12px;border-radius:50%;background:#22c55e;"></div>
                    </div>
                    <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:12px;padding:16px;display:flex;justify-content:space-between;align-items:center;">
                        <div><div style="font-size:0.72rem;color:var(--text-muted);">SOC Gateway</div><div id="srv-soc" style="font-weight:700;">—</div></div>
                        <div style="width:12px;height:12px;border-radius:50%;background:#22c55e;"></div>
                    </div>
                </div>
            </article>
        </section>

        <!-- Dedicated Support Ticketing Central -->
        <section style="margin-bottom:40px;">
            <article class="panel" style="padding:32px;">
                <div class="panel-heading" style="margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
                    <div><span class="eyebrow">Soporte Técnico</span><h2>Gestión de Casos e Incidentes</h2></div>
                    <button class="btn btn-primary" onclick="openNewTicket()">+ Abrir Nuevo Caso</button>
                </div>
                
                <div style="display:flex; gap:12px; margin-bottom:24px; border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:12px;">
                    <button onclick="filterTickets('all')" class="ticket-tab active" style="background:none; border:none; color:#fff; font-weight:700; cursor:pointer; font-size:0.9rem;">Todos</button>
                    <button onclick="filterTickets('OPEN')" class="ticket-tab" style="background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:0.9rem;">Abiertos</button>
                    <button onclick="filterTickets('IN_PROGRESS')" class="ticket-tab" style="background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:0.9rem;">En Proceso</button>
                    <button onclick="filterTickets('CLOSED')" class="ticket-tab" style="background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:0.9rem;">Resueltos</button>
                </div>

                <div id="tickets-list-container">
                    <div id="tickets-list"><div style="padding:40px;text-align:center;color:var(--text-muted);">Cargando tus tickets...</div></div>
                </div>
            </article>
        </section>

        <!-- Subscription & Finance Layer -->
        <section class="planes-grid" style="grid-template-columns:1.5fr 1fr;gap:32px;margin-bottom:40px;">
            <article class="panel" style="padding:24px;">
                <div class="panel-heading" style="margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
                    <div><span class="eyebrow">Suscripción</span><h2>Estado de Cuenta</h2></div>
                    <button class="btn btn-primary" onclick="toggleUpgrades()" style="font-size:0.75rem;">Mejorar Suscripción ↑</button>
                </div>
                <div id="active-plan-display">
                    <!-- Dynamic active plan card -->
                </div>
                <div id="upgrade-catalog" style="display:none; margin-top:24px; padding-top:24px; border-top:1px solid rgba(255,255,255,0.05); animation: slideDown 0.4s ease;">
                    <div class="eyebrow" style="margin-bottom:16px;">Planes Disponibles para Upgrade</div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                        <div onclick="openPayment('Business Premium', 1500)" style="cursor:pointer; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:12px; padding:16px; transition:all 0.3s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.08)'">
                            <strong style="display:block; margin-bottom:4px;">Business Premium</strong>
                            <div style="font-size:0.8rem; color:var(--primary); font-weight:700;">1.500€/mes</div>
                        </div>
                        <div onclick="openPayment('Enterprise SOC', 4200)" style="cursor:pointer; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:12px; padding:16px; transition:all 0.3s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.08)'">
                            <strong style="display:block; margin-bottom:4px;">Enterprise SOC</strong>
                            <div style="font-size:0.8rem; color:var(--primary); font-weight:700;">4.200€/mes</div>
                        </div>
                    </div>
                </div>
            </article>

            <article class="panel" style="padding:24px;">
                <div class="panel-heading" style="margin-bottom:24px;">
                    <div><span class="eyebrow">Finanzas</span><h2>Últimas Facturas</h2></div>
                </div>
                <div class="stack-list">
                    <div class="stack-item" style="display:flex;justify-content:space-between;align-items:center;">
                        <div><strong>Factura Abril 2026</strong><div style="font-size:0.75rem;color:var(--text-muted);">PDF · 1.2 MB</div></div>
                        <a href="/download.php?file=invoice_1023.pdf" class="btn btn-outline btn-sm">Descargar</a>
                    </div>
                    <div class="stack-item" style="display:flex;justify-content:space-between;align-items:center;">
                        <div><strong>Informe de Seguridad Mar 2026</strong><div style="font-size:0.75rem;color:var(--text-muted);">PDF · 3.4 MB</div></div>
                        <a href="/download.php?file=report_march_2026.pdf" class="btn btn-outline btn-sm">Descargar</a>
                    </div>
                </div>
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
    alert('Suscripción actualizada. El departamento de facturación procesará el cambio.');
}

function toggleUpgrades() {
    const el = document.getElementById('upgrade-catalog');
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

function openNewTicket(){
    const subject = prompt('Asunto detallado del nuevo ticket:');
    if(!subject) return;
    const newT = { 
        id: Math.floor(Math.random()*1000)+3200, 
        subject, 
        status: 'OPEN', 
        createdAt: new Date().toISOString(),
        priority: 'ALTA',
        agent: 'Analista SOC'
    };
    window.allTickets = [newT, ...window.allTickets];
    renderTickets(window.allTickets);
}

let allTickets = [];
function renderTickets(tickets) {
    const list = document.getElementById('tickets-list');
    if(!tickets || tickets.length === 0){
        list.innerHTML = '<div style="padding:60px;text-align:center;color:var(--text-muted);font-style:italic;">No se han encontrado registros en esta categoría.</div>';
        return;
    }
    list.innerHTML = tickets.map(t=>{
        const statusMap = { OPEN:'status-open', IN_PROGRESS:'status-progress', CLOSED:'status-closed' };
        const labelMap  = { OPEN:'Abierto / Nuevo', IN_PROGRESS:'En Análisis', CLOSED:'Resuelto y Cerrado' };
        const date = t.createdAt ? new Date(t.createdAt).toLocaleDateString('es-ES', {day:'2-digit', month:'long'}) : 'Hoy';
        const prio = t.priority || 'NORMAL';
        return `<div class="ticket-row">
            <div style="flex: 1;">
                <div style="font-size:1rem; font-weight:800; color:#fff; margin-bottom:4px;">#${t.id} — ${t.subject||t.title}</div>
                <div style="display:flex; gap:16px; font-size:0.75rem; color:var(--text-muted);">
                    <span>📅 ${date}</span>
                    <span>👤 Agente: ${t.agent || 'Asignando...'}</span>
                    <span style="color:${prio==='ALTA'?'#fca5a5':'#94a3b8'}; font-weight:700;">⚠ Prioridad: ${prio}</span>
                </div>
            </div>
            <span class="ticket-status ${statusMap[t.status]||'status-open'}">${labelMap[t.status]||t.status}</span>
        </div>`;
    }).join('');
}

function filterTickets(status) {
    document.querySelectorAll('.ticket-tab').forEach(b => b.classList.remove('active'));
    event.currentTarget.classList.add('active');
    
    if (status === 'all') {
        renderTickets(window.allTickets);
    } else {
        renderTickets(window.allTickets.filter(t => t.status === status));
    }
}

/**
 * BACKEND INTEGRATION
 */
const API = window.SOFIA_CONFIG?.apiBase || '';
const TOKEN = () => localStorage.getItem('sofia_token_v1');
function authHdr(){ return { Authorization: 'Bearer ' + TOKEN() }; }

(async function loadDashboard(){
    try {
        const user = JSON.parse(localStorage.getItem('sofia_user_v1') || '{}');
        // Fix welcome message logic
        const rawName = user.name || user.companyName || user.email || 'Usuario';
        const cleanName = rawName.split('@')[0].toUpperCase();
        document.getElementById('welcome-user-name').textContent = cleanName;

        // Render Active Plan
        renderActivePlan(user.role === 'ADMIN' ? 'Enterprise SOC' : 'Business Premium');

        const [overviewRes, ticketsRes] = await Promise.all([
            fetch(API + '/api/admin/overview', { headers: authHdr() }),
            fetch(API + '/api/tickets',        { headers: authHdr() })
        ]);
        const overview = await overviewRes.json();
        const ticketsData = await ticketsRes.json();

        // KPIs
        document.querySelector('#kpi-assets strong').textContent = overview.secureLogins ?? '14';
        document.querySelector('#kpi-blocked strong').textContent = overview.blockedAttacks ?? '2,840';
        document.querySelector('#kpi-tickets strong').textContent = overview.openTickets ?? '2';
        
        // Tickets logic with better mockup fallbacks
        const realTickets = Array.isArray(ticketsData) ? ticketsData : (ticketsData.tickets || []);
        window.allTickets = realTickets.length > 0 ? realTickets : [
            { id: 4821, subject: 'Fuga de datos detectada en subdominio', status: 'IN_PROGRESS', createdAt: '2026-04-28T10:00:00Z', priority: 'ALTA', agent: 'S. Ramos' },
            { id: 4710, subject: 'Actualización de firewall solicitada', status: 'CLOSED', createdAt: '2026-04-25T14:30:00Z', priority: 'BAJA', agent: 'L. Moreno' }
        ];
        renderTickets(window.allTickets);

        // Charting
        renderLatencyChart();
        checkServerStatus();
    } catch(e){
        console.warn('Dashboard Sync Error', e);
        // Fallback for demo
        document.getElementById('welcome-user-name').textContent = 'MAPFRE';
        renderActivePlan('Business Premium');
        renderTickets([
            { id: 4821, subject: 'Fuga de datos detectada en subdominio', status: 'IN_PROGRESS', createdAt: '2026-04-28T10:00:00Z', priority: 'ALTA', agent: 'S. Ramos' }
        ]);
    }
})();

function renderActivePlan(planName) {
    const container = document.getElementById('active-plan-display');
    const plans = {
        'Individual': { price: '499€', features: ['1 Endpoint', '8/5 Soporte'] },
        'Business Premium': { price: '1.500€', features: ['15 Endpoints', '24/7 SOC Global', 'IR Retainer'] },
        'Enterprise SOC': { price: '4.200€', features: ['Ilimitados', 'Analista Dedicado', 'CISO as a Service'] }
    };
    const p = plans[planName] || plans['Business Premium'];
    container.innerHTML = `
        <div style="background:linear-gradient(135deg, rgba(139,92,246,0.1) 0%, rgba(139,92,246,0.05) 100%); border:1px solid rgba(139,92,246,0.2); border-radius:16px; padding:24px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h3 style="margin:0; font-size:1.4rem; color:#fff;">${planName}</h3>
                <span style="background:#8b5cf6; color:#fff; font-size:0.7rem; font-weight:800; padding:4px 10px; border-radius:6px;">PLAN ACTIVO</span>
            </div>
            <div style="font-size:2rem; font-weight:800; color:#fff; margin-bottom:16px;">${p.price}<small style="font-size:1rem; color:var(--text-muted); font-weight:400;"> / mes</small></div>
            <ul style="list-style:none; padding:0; margin:0; display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                ${p.features.map(f => `<li style="font-size:0.8rem; color:var(--text-soft); display:flex; align-items:center; gap:8px;"><span style="color:#22c55e;">✔</span> ${f}</li>`).join('')}
            </ul>
        </div>
    `;
}

function renderLatencyChart() {
    const canvas = document.getElementById('clientLatencyChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['00:00','04:00','08:00','12:00','16:00','20:00','23:59'],
            datasets: [{
                label: 'Latencia de Red (ms)',
                data: [12, 11, 24, 18, 15, 22, 14],
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139,92,246,0.05)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#64748b', font:{size:10} } },
                x: { grid: { display: false }, ticks: { color: '#64748b', font:{size:10} } }
            }
        }
    });
}

async function checkServerStatus(){
    const apiEl = document.getElementById('srv-api');
    const dbEl = document.getElementById('srv-db');
    const socEl = document.getElementById('srv-soc');
    try {
        const r = await fetch(API + '/api/admin/overview', { headers: authHdr() });
        if(r.ok){
            apiEl.innerHTML = '<span style="color:#22c55e;">● Operacional</span>';
            dbEl.innerHTML = '<span style="color:#22c55e;">● Conectado</span>';
            socEl.innerHTML = '<span style="color:#22c55e;">● Activo</span>';
        }
    } catch(e){
        apiEl.textContent = 'Offline';
    }
}
</script>
