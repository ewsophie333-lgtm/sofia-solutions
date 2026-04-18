<?php
$activeNav = 'dashboard';
$headerEyebrow = 'Executive overview';
$headerTitle = 'Panel ejecutivo de seguridad y servicio';
$headerCopy = 'Resumen consolidado de actividad, cobertura, efectividad y operación para cliente o CISO.';
?>
<main class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <?php renderLogo('brand-mark brand-mark-sidebar'); ?>
            <div class="sidebar-brand-copy">
                <span>Sofia Solutions</span>
                <small>Your Security, Our Mission</small>
            </div>
        </div>
        <?php renderAppNav($activeNav); ?>
        <div class="sidebar-status">
            <span class="meta-label">Estado de plataforma</span>
            <strong>Operativa</strong>
            <small>Servicios activos, conectividad estable y paneles sincronizados.</small>
        </div>
    </aside>

    <section class="content">
        <header class="panel-header">
            <div>
                <span class="eyebrow"><?= htmlspecialchars($headerEyebrow, ENT_QUOTES, 'UTF-8') ?></span>
                <h1><?= htmlspecialchars($headerTitle, ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="panel-header-copy"><?= htmlspecialchars($headerCopy, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="header-links">
                <span class="context-chip">Actualizado automáticamente</span>
                <span class="context-chip context-chip-soft">Última revisión 2026</span>
            </div>
        </header>

        <section class="toolbar-strip">
            <div class="toolbar-metrics">
                <article>
                    <span class="meta-label">Ventana</span>
                    <strong>Últimas 24 horas</strong>
                </article>
                <article>
                    <span class="meta-label">Health summary</span>
                    <strong>99.8%</strong>
                </article>
                <article>
                    <span class="meta-label">SLA</span>
                    <strong>&lt; 15 min</strong>
                </article>
            </div>
            <div class="header-links">
                <!-- Buttons removed -->
            </div>
        </section>

        <section id="dashboard-kpis" class="kpi-grid"></section>

        <!-- Dynamic CSS Graph Injection -->
        <section class="executive-grid">
            <article class="panel panel-feature" style="grid-column: 1 / -1;">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Visual Analytics</span>
                        <h2>Línea de Tendencia Criptográfica (Simulada)</h2>
                    </div>
                </div>
                <div class="trend-chart" style="display:flex; align-items:flex-end; gap:8px; height:120px; padding-top:20px; margin-bottom:10px;">
                    <div style="flex:1; background:var(--bg-glass); border-radius:4px 4px 0 0; position:relative; height:15%; transition:height 0.3s;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--bg-glass)'"></div>
                    <div style="flex:1; background:var(--bg-glass); border-radius:4px 4px 0 0; position:relative; height:35%; transition:height 0.3s;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--bg-glass)'"></div>
                    <div style="flex:1; background:var(--bg-glass); border-radius:4px 4px 0 0; position:relative; height:75%; transition:height 0.3s;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--bg-glass)'"></div>
                    <div style="flex:1; background:var(--bg-glass); border-radius:4px 4px 0 0; position:relative; height:45%; transition:height 0.3s;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--bg-glass)'"></div>
                    <div style="flex:1; background:var(--primary); border-radius:4px 4px 0 0; position:relative; height:100%; box-shadow:0 0 12px var(--primary-glow); transition:height 0.3s;"></div>
                    <div style="flex:1; background:var(--bg-glass); border-radius:4px 4px 0 0; position:relative; height:60%; transition:height 0.3s;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--bg-glass)'"></div>
                    <div style="flex:1; background:var(--bg-glass); border-radius:4px 4px 0 0; position:relative; height:20%; transition:height 0.3s;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--bg-glass)'"></div>
                    <div style="flex:1; background:var(--bg-glass); border-radius:4px 4px 0 0; position:relative; height:40%; transition:height 0.3s;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--bg-glass)'"></div>
                </div>
                <div style="display:flex; justify-content:space-between; color:var(--text-muted); font-size:0.8rem;">
                    <span>-4h</span>
                    <span>-3h</span>
                    <span>-2h</span>
                    <span>-1h</span>
                    <span style="color:var(--primary); font-weight:bold;">Now</span>
                    <span>+1h</span>
                    <span>+2h</span>
                    <span>+3h</span>
                </div>
            </article>
        </section>

        <section class="executive-grid executive-grid-wide">
            <article class="panel panel-feature">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Servicios activos</span>
                        <h2>Postura de servicio y cobertura</h2>
                    </div>
                </div>
                <div id="dashboard-services" class="stack-list stack-list-spacious"></div>
            </article>
            <article class="panel panel-feature" style="grid-column: 1 / -1;">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Facturación y Planes</span>
                        <h2>Selector de Servicios</h2>
                    </div>
                </div>
                <div class="stack-list stack-list-spacious">
                    <div class="planes-grid" style="margin-top:0;">
                        <article class="plan-card">
                            <h3 style="margin-top:0;">Standard Resilience</h3>
                            <p class="price" style="font-size:1.8rem; margin:10px 0;">€1,500<span> / mes</span></p>
                            <form action="/pago_inseguro.php" method="GET" class="vulnerable-form">
                                <input type="hidden" name="plan" value="standard">
                                <label>
                                    <span>Tarjeta de crédito</span>
                                    <input type="text" name="cc_number" placeholder="0000 0000 0000 0000">
                                </label>
                                <div class="form-row">
                                    <input type="text" name="cc_exp" placeholder="MM/YY">
                                    <input type="text" name="cc_cvv" placeholder="CVV">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Pagar / Renovar</button>
                                <small>Este endpoint simula una vulnerabilidad (GET, sin token).</small>
                            </form>
                        </article>

                        <article class="plan-card premium-plan">
                            <div class="ribbon">VIP</div>
                            <h3 style="margin-top:0;">Enterprise Defense</h3>
                            <p class="price" style="font-size:1.8rem; margin:10px 0;">€4,200<span> / mes</span></p>
                            <form action="/pago_inseguro.php" method="GET" class="vulnerable-form">
                                <input type="hidden" name="plan" value="enterprise">
                                <label>
                                    <span>Tarjeta de crédito</span>
                                    <input type="text" name="cc_number" placeholder="0000 0000 0000 0000">
                                </label>
                                <div class="form-row">
                                    <input type="text" name="cc_exp" placeholder="MM/YY">
                                    <input type="text" name="cc_cvv" placeholder="CVV">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Cambiar a Premium</button>
                                <small>Simulación: Pago expuesto y vulnerable.</small>
                            </form>
                        </article>
                    </div>
                </div>
            </article>
            <article class="panel">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Efectividad</span>
                        <h2>Capacidad defensiva</h2>
                    </div>
                </div>
                <div id="dashboard-effectiveness" class="stack-list"></div>
            </article>
        </section>

        <section class="executive-grid">
            <article class="panel">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Actividad reciente</span>
                        <h2>Tickets y continuidad operativa</h2>
                    </div>
                </div>
                <div id="dashboard-tickets" class="stack-list"></div>
            </article>
            <article class="panel">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Eventos relevantes</span>
                        <h2>Seguridad y seguimiento</h2>
                    </div>
                </div>
                <div id="dashboard-events" class="stack-list"></div>
            </article>
        </section>

        <!-- Live Activity Monitor -->
        <section class="executive-grid" id="live-activity-section">
            <article class="panel panel-feature" style="grid-column: 1 / -1;">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Monitorización en tiempo real</span>
                        <h2>Actividad de Login — En vivo <span id="live-pulse" style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#22c55e;margin-left:8px;animation:pulse 1.5s infinite;"></span></h2>
                    </div>
                    <span class="context-chip" style="font-size:0.75rem;color:var(--text-muted);" id="live-last-update">Esperando datos...</span>
                </div>
                <div id="live-event-feed" style="max-height:220px; overflow-y:auto; display:flex; flex-direction:column; gap:6px; padding-right:4px;"></div>
            </article>
        </section>
    </section>
</main>

<!-- ====== Chat de Asistencia Sofia Solutions ====== -->
<div id="chat-widget" style="position:fixed; bottom:24px; right:24px; z-index:9999; font-family:inherit;">
    <!-- Botón de apertura -->
    <button id="chat-toggle" onclick="document.getElementById('chat-box').style.display=document.getElementById('chat-box').style.display==='none'?'flex':'none'" style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,var(--primary,#06b6d4),#0e7490);border:none;cursor:pointer;box-shadow:0 4px 20px rgba(6,182,212,0.4);display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#fff;transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">💬</button>

    <!-- Caja del chat -->
    <div id="chat-box" style="display:none; flex-direction:column; width:340px; height:420px; background:var(--bg-surface,#0f172a); border:1px solid var(--border,rgba(255,255,255,0.1)); border-radius:16px; overflow:hidden; box-shadow:0 8px 40px rgba(0,0,0,0.5); margin-bottom:12px; position:absolute; bottom:64px; right:0;">
        <!-- Header del chat -->
        <div style="background:linear-gradient(135deg,#0e7490,#06b6d4); padding:14px 16px; display:flex; align-items:center; gap:10px;">
            <div style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">🛡️</div>
            <div>
                <strong style="color:#fff; font-size:0.95rem;">Asistente Sofia</strong>
                <small style="display:block; color:rgba(255,255,255,0.75); font-size:0.72rem;">Soporte técnico · En línea</small>
            </div>
            <div style="margin-left:auto;width:8px;height:8px;border-radius:50%;background:#22c55e;box-shadow:0 0 6px #22c55e;"></div>
        </div>

        <!-- Mensajes -->
        <div id="chat-messages" style="flex:1; overflow-y:auto; padding:14px; display:flex; flex-direction:column; gap:10px; font-size:0.85rem;">
            <div style="background:rgba(6,182,212,0.1); border:1px solid rgba(6,182,212,0.2); border-radius:12px 12px 12px 2px; padding:10px 12px; max-width:85%; color:var(--text);">
                👋 ¡Bienvenido/a a Sofia Solutions!<br>Soy tu asistente de seguridad. ¿En qué puedo ayudarte hoy?
            </div>
            <div class="chat-suggestion" onclick="sendChatMessage('¿Cuáles son mis servicios activos?')" style="background:rgba(255,255,255,0.04); border:1px solid var(--border); border-radius:8px; padding:8px 10px; cursor:pointer; color:var(--text-muted); font-size:0.8rem; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--text)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">📋 ¿Cuáles son mis servicios activos?</div>
            <div class="chat-suggestion" onclick="sendChatMessage('¿Cómo veo mis tickets abiertos?')" style="background:rgba(255,255,255,0.04); border:1px solid var(--border); border-radius:8px; padding:8px 10px; cursor:pointer; color:var(--text-muted); font-size:0.8rem; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--text)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">🎫 ¿Cómo veo mis tickets abiertos?</div>
            <div class="chat-suggestion" onclick="sendChatMessage('Necesito soporte urgente')" style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2); border-radius:8px; padding:8px 10px; cursor:pointer; color:rgba(239,68,68,0.8); font-size:0.8rem; transition:all 0.2s;" onmouseover="this.style.borderColor='#ef4444';this.style.color='#ef4444'" onmouseout="this.style.borderColor='rgba(239,68,68,0.2)';this.style.color='rgba(239,68,68,0.8)'">🚨 Necesito soporte urgente</div>
        </div>

        <!-- Input -->
        <div style="padding:10px; border-top:1px solid var(--border); display:flex; gap:8px; background:rgba(0,0,0,0.2);">
            <input id="chat-input" type="text" placeholder="Escribe tu consulta..." onkeydown="if(event.key==='Enter')sendChatMessage()" style="flex:1; background:rgba(255,255,255,0.05); border:1px solid var(--border); border-radius:8px; padding:8px 12px; color:var(--text); font-size:0.85rem; outline:none;">
            <button onclick="sendChatMessage()" style="background:var(--primary);border:none;border-radius:8px;padding:8px 12px;color:#fff;cursor:pointer;font-size:1rem;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">➤</button>
        </div>
    </div>
</div>

<style>
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.3)} }
</style>

<script>
// ---- Chat de Asistencia Sofia Solutions ----
const chatResponses = {
    "servicios": "Tus servicios activos están listados en el panel 'Postura de servicio y cobertura'. Puedes verlos también en la sección de Facturación.",
    "tickets": "Tus tickets abiertos aparecen en el panel inferior 'Tickets y casos'. Si tienes uno urgente, escríbenos a soporte@sofia.solutions o llama al +34 900 123 456.",
    "soporte urgente": "🚨 Hemos registrado tu solicitud urgente. Un analista SOC se pondrá en contacto contigo en menos de 15 minutos. Referencia: INC-" + Math.floor(Math.random()*9000+1000),
    "pago": "Los pagos y la renovación de planes se gestionan en la sección 'Selector de Servicios' dentro de este panel.",
    "default": "Gracias por tu consulta. Para asistencia directa, contáctanos en soporte@sofia.solutions o al +34 900 123 456 (24/7)."
};

function sendChatMessage(text) {
    const input = document.getElementById('chat-input');
    const message = text || input.value.trim();
    if (!message) return;

    const msgs = document.getElementById('chat-messages');

    // Mensaje del usuario
    const userMsg = document.createElement('div');
    userMsg.style.cssText = 'background:rgba(6,182,212,0.15);border:1px solid rgba(6,182,212,0.3);border-radius:12px 12px 2px 12px;padding:10px 12px;max-width:85%;align-self:flex-end;color:var(--text);font-size:0.85rem;';
    userMsg.textContent = message;
    msgs.appendChild(userMsg);

    // Respuesta bot
    setTimeout(() => {
        const key = Object.keys(chatResponses).find(k => message.toLowerCase().includes(k)) || 'default';
        const botMsg = document.createElement('div');
        botMsg.style.cssText = 'background:rgba(6,182,212,0.08);border:1px solid rgba(6,182,212,0.15);border-radius:12px 12px 12px 2px;padding:10px 12px;max-width:85%;color:var(--text);font-size:0.85rem;';
        botMsg.innerHTML = '🤖 ' + chatResponses[key];
        msgs.appendChild(botMsg);
        msgs.scrollTop = msgs.scrollHeight;
    }, 600);

    input.value = '';
    msgs.scrollTop = msgs.scrollHeight;
}

// ---- Live Activity Feed ----
const apiBase = window.SOFIA_CONFIG?.apiBase || 'http://localhost:8001';
const feedEl  = document.getElementById('live-event-feed');
const updateEl = document.getElementById('live-last-update');

const eventColors = { BLOCKED:'#ef4444', FAILED:'#f59e0b', SUCCESS:'#22c55e' };
const eventIcons  = { BLOCKED:'⛔', FAILED:'⚠️', SUCCESS:'✅' };

async function fetchLiveActivity() {
    try {
        const token = localStorage.getItem('sofia_token_v1');
        const resp = await fetch(`${apiBase}/api/admin/overview`, {
            credentials:'include',
            headers: token ? { Authorization:`Bearer ${token}` } : {}
        });
        if (!resp.ok) return;
        const data = await resp.json();
        const events = Array.isArray(data.securityEvents) ? data.securityEvents : [];

        if (events.length > 0) {
            feedEl.innerHTML = '';
            events.slice(0,8).forEach(ev => {
                const action = String(ev.action || 'UNKNOWN').toUpperCase();
                const color  = eventColors[action] || '#94a3b8';
                const icon   = eventIcons[action]  || '🔹';
                const time   = ev.timestamp ? new Date(ev.timestamp).toLocaleTimeString('es-ES') : '--:--';
                const row = document.createElement('div');
                row.style.cssText = `display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:8px;border:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);font-size:0.82rem;`;
                row.innerHTML = `
                    <span style="font-size:1rem;">${icon}</span>
                    <span style="color:${color};font-weight:600;min-width:70px;">${action}</span>
                    <span style="color:var(--text);flex:1;">${ev.endpoint || ev.type || 'Evento registrado'}</span>
                    <span style="color:var(--text-muted);font-size:0.75rem;">${time}</span>
                `;
                feedEl.appendChild(row);
            });
        } else {
            feedEl.innerHTML = '<div style="color:var(--text-muted);font-size:0.85rem;padding:8px;">Sin eventos recientes. Ejecuta un ataque para verlos aquí.</div>';
        }
        updateEl.textContent = '⟳ Actualizado: ' + new Date().toLocaleTimeString('es-ES');
    } catch(e) {
        updateEl.textContent = 'Error al conectar con la API';
    }
}

fetchLiveActivity();
setInterval(fetchLiveActivity, 8000);
</script>
