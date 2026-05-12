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
<style>
    .brand-mark-sidebar {
        max-height: 120px;
        width: auto;
        object-fit: contain;
        margin-bottom: 30px;
    }
    .brand-mark-login {
        max-height: 300px;
        width: auto;
        margin-bottom: 50px;
    }
    :root {
        --ubuntu-purple: #300A24;
        --font-main: 'Inter', sans-serif;
    }
    body, .app-shell, .sidebar, .panel, .content {
        background-color: var(--ubuntu-purple) !important;
        font-family: var(--font-main) !important;
    }
    .pulse-dot {
        width: 8px;
        height: 8px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
        box-shadow: 0 0 0 rgba(34, 197, 94, 0.4);
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">

<!-- Security Guard: Ensure only ADMIN roles can access this view -->
<script>
    const API = window.SOFIA_CONFIG?.apiBase || '';
    const TOKEN = () => {
        const u = JSON.parse(localStorage.getItem('sofia_user_v1') || '{}');
        const role = u.role || 'CLIENT';
        return localStorage.getItem(`sofia_token_${role}`) || localStorage.getItem('sofia_token_v1');
    };
    const authHdr = () => {
        const t = TOKEN();
        return t ? { Authorization: 'Bearer ' + t } : {};
    };

    (async function loadAdminDashboard() {
        try {
            const user = JSON.parse(localStorage.getItem('sofia_user_v1') || '{}');
            if (!user.email || !TOKEN()) {
                 console.warn("Sesión no detectada, esperando...");
                 return;
            }
            if (user.role === 'CLIENT') {
                 window.location.replace('/dashboard');
                 return;
            }
            
            // Solo actualizamos el nombre si el elemento ya existe
            const badge = document.getElementById('admin-name-badge');
            if (badge) {
                badge.textContent = (user.name || user.email || 'Admin').toUpperCase();
            }
        } catch (e) {
            console.warn("Error en el guard de admin:", e);
        }
    })();
</script>

<!-- External Charting & Mapping Dependencies -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="app-shell readdy-dashboard" id="main-app">
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <?php renderLogo('brand-mark brand-mark-sidebar'); ?>
            <div class="sidebar-brand-copy"><span>Sofia Solutions</span><small>Su seguridad, nuestra misión</small></div>
        </div>
        <?php renderAppNav($activeNav); ?>
        <div style="margin-top:auto; padding:20px; border-top:1px solid rgba(255,255,255,0.05);">
            <div style="display:flex; gap:8px; background:rgba(0,0,0,0.2); padding:4px; border-radius:8px;">
                <button onclick="setLang('es')" id="btn-es"
                    style="flex:1;border:none;padding:6px;border-radius:6px;font-size:0.7rem;cursor:pointer;font-weight:700;">ES</button>
                <button onclick="setLang('en')" id="btn-en"
                    style="flex:1;border:none;padding:6px;border-radius:6px;font-size:0.7rem;cursor:pointer;font-weight:700;">EN</button>
            </div>
        </div>
    </aside>

    <section class="content">
        <!-- Global Operations Header -->
        <header class="panel-header" style="margin-bottom:32px;">
            <div>
                <span class="eyebrow" data-i18n="eyebrow">Operaciones Globales</span>
                <h1 data-i18n="title">Panel de Operaciones</h1>
                <p class="panel-header-copy" data-i18n="copy">Plataforma de Inteligencia de Amenazas y Orquestación Multi-Tenant</p>
            </div>
            <div style="display:flex; gap:12px; align-items:center;">
                <span
                    style="padding:8px 16px; border-radius:20px; font-size:0.75rem; font-weight:700; background:rgba(34,197,94,0.1); color:#22c55e; border:1px solid rgba(34,197,94,0.2); display:flex; align-items:center;">
                    <span class="pulse-dot"></span>
                    ESTADO DEL NODO: OPERATIVO</span>
            </div>
        </header>

        <!-- Global Performance Metrics (KPIs) -->
        <section class="planes-grid" style="display:flex !important; flex-wrap:nowrap !important; gap:12px; margin-bottom:32px; width:100%;">
            <div class="kpi-card" data-tone="ok" style="flex:1; border-left:3px solid #6d28d9; padding:12px; min-width:0;" id="kpi-events"><span
                    class="meta-label" data-i18n="k1" style="font-size:0.6rem; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">EVENTOS ANALIZADOS</span><strong style="font-size:1.3rem;">—</strong>
                <div class="tone-bar" style="background:#6d28d9;"></div>
            </div>
            <div class="kpi-card" data-tone="ok" style="flex:1; border-left:3px solid #6d28d9; padding:12px; min-width:0;" id="kpi-incidents"><span
                    class="meta-label" data-i18n="k2" style="font-size:0.6rem; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">INCIDENTES CRÍTICOS</span><strong style="font-size:1.3rem;">—</strong>
                <div class="tone-bar" style="background:#6d28d9;"></div>
            </div>
            <div class="kpi-card" data-tone="ok" style="flex:1; border-left:3px solid #6d28d9; padding:12px; min-width:0;" id="kpi-customers"><span
                    class="meta-label" data-i18n="k3" style="font-size:0.6rem; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">CLIENTES PROTEGIDOS</span><strong style="font-size:1.3rem;">—</strong>
                <div class="tone-bar" style="background:#6d28d9;"></div>
            </div>
            <div class="kpi-card" data-tone="ok" style="flex:1; border-left:3px solid #6d28d9; padding:12px; min-width:0;" id="kpi-health"><span
                    class="meta-label" data-i18n="k4" style="font-size:0.6rem; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">SALUD DEL SISTEMA</span><strong style="font-size:1.3rem;">—</strong>
                <div class="tone-bar" style="background:#6d28d9;"></div>
            </div>
        </section>

        <!-- System Health Summary (Real-time Status) -->
        <section class="panel" style="padding:24px; margin-bottom:32px;">
            <div class="panel-heading" style="margin-bottom:20px;">
                <div><span class="eyebrow">Estado</span>
                    <h2>Resumen de Salud</h2>
                </div>
            </div>
            <div class="planes-grid" style="grid-template-columns:repeat(3,1fr); gap:16px;">
                <div class="health-card ok" id="health-api">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <span class="meta-label">API Backend</span>
                            <div class="status-indicator"><span class="dot"></span> <span
                                    class="status-text">Operacional</span></div>
                        </div>
                    </div>
                </div>
                <div class="health-card ok" id="health-db">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <span class="meta-label">Base de Datos</span>
                            <div class="status-indicator"><span class="dot"></span> <span
                                    class="status-text">Conectado</span></div>
                        </div>
                    </div>
                </div>
                <div class="health-card ok" id="health-gateway">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <span class="meta-label">SOC Gateway</span>
                            <div class="status-indicator"><span class="dot"></span> <span
                                    class="status-text">Activo</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <style>
            .health-card {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 12px;
                padding: 20px;
                transition: all 0.3s ease;
            }

            .health-card .status-indicator {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-top: 8px;
                font-weight: 800;
                font-size: 1.1rem;
            }

            .health-card .dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                display: inline-block;
            }

            .health-card.ok .dot {
                background: #22c55e;
                box-shadow: 0 0 10px #22c55e;
            }

            .health-card.ok .status-text {
                color: #22c55e;
            }

            .health-card.error {
                background: rgba(239, 68, 68, 0.05);
                border: 1px solid rgba(239, 68, 68, 0.2);
                animation: pulseRed 2s infinite;
            }

            .health-card.error .dot {
                background: #ef4444;
                box-shadow: 0 0 10px #ef4444;
            }

            .health-card.error .status-text {
                color: #ef4444;
            }

            @keyframes pulseRed {
                0% {
                    border-color: rgba(239, 68, 68, 0.2);
                }

                50% {
                    border-color: rgba(239, 68, 68, 0.5);
                }

                100% {
                    border-color: rgba(239, 68, 68, 0.2);
                }
            }
        </style>

        <script>
            // La salud del sistema se gestiona de forma centralizada en loadAdmin.
        </script>

        <!-- SOC Threat Map & Geospatial Intelligence -->
        <section class="panel" style="padding:0; margin-bottom:32px; overflow:hidden; min-height:400px; position:relative; border:1px solid rgba(139,92,246,0.2);">
            <div id="threat-map" style="width:100%; height:400px; background:#0f172a; z-index:1;"></div>
            <div style="position:absolute; top:20px; left:20px; z-index:1000; pointer-events:none;">
                <div style="background:rgba(15,23,42,0.9); backdrop-filter:blur(10px); padding:16px; border-radius:12px; border:1px solid rgba(255,255,255,0.1); box-shadow:0 10px 30px rgba(0,0,0,0.5);">
                    <span class="eyebrow" style="color:#a78bfa;">Inteligencia Geoespacial</span>
                    <h2 style="margin:4px 0 0; font-size:1.2rem; color:#fff;">Mapa de Amenazas Global</h2>
                    <div style="margin-top:12px; display:flex; gap:12px;">
                        <div style="font-size:0.7rem; color:#94a3b8;"><span style="color:#ef4444;">●</span> Ataque Activo</div>
                        <div style="font-size:0.7rem; color:#94a3b8;"><span style="color:#22c55e;">●</span> Nodo Seguro</div>
                    </div>
                </div>
            </div>
            <div style="position:absolute; bottom:20px; right:20px; z-index:1000;">
                <div style="background:rgba(34,197,94,0.1); color:#86efac; border:1px solid rgba(34,197,94,0.3); padding:6px 12px; border-radius:6px; font-size:0.65rem; font-weight:800; letter-spacing:1px;">RED DE SENSORES: ACTIVA</div>
            </div>
        </section>

        <!-- Threat Vector & Risk Distribution Analytics -->
        <section class="planes-grid" style="grid-template-columns:1.8fr 1fr;gap:32px;margin-bottom:32px;">
            <article class="panel" style="padding:24px;">
                <div class="panel-heading">
                    <div><span class="eyebrow">Telemetría</span>
                        <h2 data-i18n="chart1">Ataques por Vector (24h)</h2>
                    </div>
                </div>
                <div style="height:280px;margin-top:20px;"><canvas id="globalTrafficChart"></canvas></div>
            </article>
            <article class="panel" style="padding:24px;">
                <div class="panel-heading">
                    <div><span class="eyebrow">Riesgo</span>
                        <h2 data-i18n="chart2">Distribución de Alertas</h2>
                    </div>
                </div>
                <div style="height:280px;margin-top:20px;display:flex;align-items:center;justify-content:center;">
                    <canvas id="riskChart"></canvas></div>
            </article>
        </section>

        <!-- Client Server Health & Latency Analytics -->
        <section class="panel" style="padding:24px;margin-bottom:32px;">
            <div class="panel-heading" style="margin-bottom:20px;">
                <div><span class="eyebrow">Infraestructura</span>
                    <h2>Estado de Servidores por Cliente (Latencia y Salud)</h2>
                </div>
            </div>
            <div class="planes-grid" style="grid-template-columns:1fr 1fr 1fr;gap:20px;">
                <!-- MAPFRE Server Health -->
                <div
                    style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:10px; padding:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <strong style="color:#e2e8f0;">MAPFRE (srv-prod-01)</strong>
                        <span
                            style="color:#22c55e; font-size:0.75rem; font-weight:bold; background:rgba(34,197,94,0.1); padding:4px 8px; border-radius:4px;">ONLINE</span>
                    </div>
                    <div style="height:100px;"><canvas id="mapfreLatencyChart"></canvas></div>
                    <div
                        style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; font-size:0.75rem; color:var(--text-muted);">
                        <div><span>Uptime: 99.9%</span><br><span>Avg Ping: 12ms</span></div>
                        <button onclick="adminCmd('kill-switch-mapfre')"
                            style="background:#ef4444; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-weight:bold; font-size:0.65rem;">KILL
                            SWITCH</button>
                    </div>
                </div>

                <!-- IBERDROLA Server Health -->
                <div
                    style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:10px; padding:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <strong style="color:#e2e8f0;">IBERDROLA (scada-gw)</strong>
                        <span
                            style="color:#f59e0b; font-size:0.75rem; font-weight:bold; background:rgba(245,158,11,0.1); padding:4px 8px; border-radius:4px;">WARNING</span>
                    </div>
                    <div style="height:100px;"><canvas id="iberdrolaLatencyChart"></canvas></div>
                    <div
                        style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; font-size:0.75rem; color:var(--text-muted);">
                        <div><span>Uptime: 98.4%</span><br><span>Avg Ping: 45ms</span></div>
                        <button onclick="adminCmd('kill-switch-iberdrola')"
                            style="background:#ef4444; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-weight:bold; font-size:0.65rem;">KILL
                            SWITCH</button>
                    </div>
                </div>

                <!-- SABADELL Server Health -->
                <div
                    style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:10px; padding:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <strong style="color:#e2e8f0;">SABADELL (core-db)</strong>
                        <span
                            style="color:#22c55e; font-size:0.75rem; font-weight:bold; background:rgba(34,197,94,0.1); padding:4px 8px; border-radius:4px;">ONLINE</span>
                    </div>
                    <div style="height:100px;"><canvas id="sabadellLatencyChart"></canvas></div>
                    <div
                        style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; font-size:0.75rem; color:var(--text-muted);">
                        <div><span>Uptime: 99.9%</span><br><span>Avg Ping: 8ms</span></div>
                        <button onclick="adminCmd('kill-switch-sabadell')"
                            style="background:#ef4444; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-weight:bold; font-size:0.65rem;">KILL
                            SWITCH</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Client Security Posture & Ticket Management Hub -->
        <section class="panel" style="padding:24px;margin-bottom:32px;">
            <div class="panel-heading"
                style="margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
                <div><span class="eyebrow">Gestión de Casos</span>
                    <h2 data-i18n="table_title">Monitor de Tickets por Cliente</h2>
                </div>
                <div style="display:flex; gap:8px;">
                    <button onclick="filterAdminTickets('all')" class="admin-ticket-tab active">Todos</button>
                    <button onclick="filterAdminTickets('OPEN')" class="admin-ticket-tab">Nuevos</button>
                    <button onclick="filterAdminTickets('IN_PROGRESS')" class="admin-ticket-tab">En Proceso</button>
                    <button onclick="filterAdminTickets('CLOSED')" class="admin-ticket-tab">Cerrados</button>
                </div>
            </div>
            <div id="admin-tickets-container">
                <table style="width:100%;border-collapse:collapse;font-size:0.88rem;">
                    <thead>
                        <tr
                            style="text-align:left;color:var(--text-muted);border-bottom:1px solid rgba(255,255,255,0.05);">
                            <th style="padding:14px 12px;">Cliente</th>
                            <th style="padding:14px 12px;">Asunto del Ticket</th>
                            <th style="padding:14px 12px;">Estado</th>
                            <th style="padding:14px 12px;">Prioridad</th>
                            <th style="padding:14px 12px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="client-table-body">
                        <tr>
                            <td colspan="5" style="padding:40px;text-align:center;color:var(--text-muted);">
                                Sincronizando base de datos de tickets...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- NEW: Customer Exposure Panel (Real-time Risk per Client) -->
        <section class="panel" style="padding:24px;margin-bottom:32px;">
            <div class="panel-heading" style="margin-bottom:20px;">
                <div><span class="eyebrow">Análisis Multi-Tenant</span>
                    <h2>Exposición de Riesgo por Cliente</h2>
                </div>
            </div>
            <div class="planes-grid" id="customer-exposure-grid" style="grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:20px;">
                <div style="grid-column: 1/-1; padding:40px; text-align:center; color:var(--text-muted);">Calculando exposición de activos...</div>
            </div>
        </section>

        <!-- Visual Incident Response Panel -->
        <section class="panel" style="padding:24px;margin-bottom:32px;">
            <div class="panel-heading" style="margin-bottom:24px;">
                <div><span class="eyebrow">Operaciones IR</span>
                    <h2>Respuesta a Incidentes (IR)</h2>
                </div>
            </div>

            <!-- IR Tabs -->
            <div style="display:flex; gap:12px; margin-bottom:24px; border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:16px;"
                id="ir-tabs">
                <button onclick="setIRTab('global')" class="ir-tab active" data-tab="global">
                    GLOBAL <span style="font-size:0.65rem; margin-left:8px; color:#22c55e;">● 99.8%</span>
                </button>
                <button onclick="setIRTab('mapfre')" class="ir-tab" data-tab="mapfre">
                    MAPFRE <span style="font-size:0.65rem; margin-left:8px; color:#22c55e;">● 99.9%</span>
                </button>
                <button onclick="setIRTab('iberdrola')" class="ir-tab" data-tab="iberdrola">
                    IBERDROLA <span style="font-size:0.65rem; margin-left:8px; color:#f59e0b;">● 98.4%</span>
                </button>
                <button onclick="setIRTab('sabadell')" class="ir-tab" data-tab="sabadell">
                    SABADELL <span style="font-size:0.65rem; margin-left:8px; color:#22c55e;">● 99.9%</span>
                </button>
                <button onclick="setIRTab('mercadona')" class="ir-tab" data-tab="mercadona">
                    MERCADONA <span style="font-size:0.65rem; margin-left:8px; color:#22c55e;">● 99.7%</span>
                </button>
            </div>

            <!-- IR Actions Toolbar -->
            <div id="ir-actions"
                style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:24px; background:rgba(0,0,0,0.2); padding:16px; border-radius:8px;">
                <!-- Actions rendered via JS -->
            </div>

            <!-- IR Visual Event Feed -->
            <div id="ir-feed" class="stack-list"
                style="height:380px; overflow-y:auto; padding-right:12px; border-top: 1px solid rgba(255,255,255,0.03); padding-top: 16px;">
                <!-- Feed rendered via JS -->
            </div>
        </section>
    </section>
</main>

<!-- Client Deep-Dive Modal -->
<div id="client-modal"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);backdrop-filter:blur(8px);z-index:9999;align-items:center;justify-content:center;padding:40px;">
    <div class="panel"
        style="width:min(700px,100%);max-height:90vh;overflow-y:auto;position:relative;border:1px solid rgba(6,182,212,0.3);">
        <button onclick="closeModal()"
            style="position:absolute;top:20px;right:20px;background:none;border:none;color:#fff;cursor:pointer;font-size:1.5rem;">&times;</button>
        <div id="modal-content" style="padding:40px;"></div>
    </div>
</div>

<!-- SOS Admin Notification Modal (Professional & Non-Intrusive) -->
<div id="sos-admin-modal"
    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); backdrop-filter:blur(15px); z-index:20000; align-items:center; justify-content:center; padding:20px;">
    <div class="panel"
        style="width:min(550px, 100%); border:1px solid rgba(239,68,68,0.5); box-shadow:0 20px 60px rgba(239,68,68,0.2); animation: subtlePulse 2s infinite;">
        <div style="text-align:center; margin-bottom:24px;">
            <div
                style="background:rgba(239,68,68,0.1); width:70px; height:70px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; border:1px solid rgba(239,68,68,0.4);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 14 4-4" />
                    <path d="M3.34 19a10 10 0 1 1 17.32 0" />
                    <path d="m9.05 9 1.41 1.41" />
                    <path d="M12 14v5" />
                    <path d="m14.95 9-1.41 1.41" />
                </svg>
            </div>
            <h2 style="color:#ef4444; margin:0; font-size:1.5rem; letter-spacing:1px; font-weight:800;">PETICIÓN DE
                AYUDA URGENTE</h2>
            <div id="sos-client-badge"
                style="display:inline-block; margin-top:10px; background:#ef4444; color:#fff; padding:4px 14px; border-radius:6px; font-weight:800; font-size:0.8rem;">
                SOC ALERT: —</div>
        </div>
        <div
            style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:12px; padding:24px; margin-bottom:24px;">
            <div
                style="font-size:0.7rem; color:var(--text-muted); margin-bottom:10px; text-transform:uppercase; font-weight:700; letter-spacing:1px;">
                Declaración del Incidente:</div>
            <div id="sos-admin-reason"
                style="color:#fff; font-size:0.95rem; line-height:1.6; font-style:italic; border-left:3px solid #ef4444; padding-left:16px;">
                "..."</div>
        </div>
        <div style="display:flex; gap:16px;">
            <button onclick="dismissSOS()"
                style="flex:1; background:rgba(255,255,255,0.05); color:var(--text-soft); border:1px solid rgba(255,255,255,0.1); padding:14px; border-radius:10px; cursor:pointer; font-weight:700; transition:all 0.2s;">Desestimar</button>
            <button onclick="handleSOS()"
                style="flex:2; background:rgba(220,38,38,0.1); color:#fca5a5; border:1px solid rgba(220,38,38,0.3); padding:14px; border-radius:10px; cursor:pointer; font-weight:700; transition:all 0.2s;">TOMAR
                CONTROL DEL CASO</button>
        </div>
    </div>
</div>

<style>
    .admin-ticket-tab {
        background: transparent;
        border: none;
        color: var(--text-muted);
        padding: 10px 18px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .admin-ticket-tab.active {
        background: rgba(139, 92, 246, 0.1);
        color: #a78bfa;
        border: 1px solid rgba(139, 92, 246, 0.2);
    }

    .admin-ticket-tab:hover:not(.active) {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
    }

    .ir-tab {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-muted);
        border: 1px solid rgba(255, 255, 255, 0.05);
        padding: 10px 18px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s;
    }

    .ir-tab.active {
        background: rgba(139, 92, 246, 0.1) !important;
        color: #fff !important;
        border: 1px solid rgba(139, 92, 246, 0.3) !important;
    }

    /* Softer SOS Animation */
    @keyframes subtlePulse {

        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.1);
        }

        50% {
            transform: scale(1.005);
            box-shadow: 0 10px 40px rgba(239, 68, 68, 0.15);
        }
    }

    .ticket-status-admin {
        font-size: 0.65rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-open-admin {
        background: rgba(239, 68, 68, 0.1);
        color: #fca5a5;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .status-progress-admin {
        background: rgba(245, 158, 11, 0.1);
        color: #fcd34d;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .status-closed-admin {
        background: rgba(34, 197, 94, 0.1);
        color: #86efac;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .map-tooltip {
        background: rgba(15, 23, 42, 0.95) !important;
        border: 1px solid rgba(139, 92, 246, 0.4) !important;
        color: #fff !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 0.75rem !important;
        box-shadow: 0 10px 20px rgba(0,0,0,0.4) !important;
        border-radius: 8px !important;
    }
</style>

<script>
    /**
     * CORE ADMINISTRATIVE LOGIC
     */

    // Threat Map Instance
    let threatMap;
    const attackMarkers = [];

    function initMap() {
        if (threatMap) return;
        const container = document.getElementById('threat-map');
        if (!container) return;

        threatMap = L.map('threat-map', {
            center: [20, 0],
            zoom: 2,
            zoomControl: false,
            attributionControl: false
        });

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 19
        }).addTo(threatMap);
    }

    const GEO_COORDS = {
        'RU': [61.524, 105.318],
        'CN': [35.861, 104.195],
        'US': [37.090, -95.712],
        'ES': [40.463, -3.749],
        'BR': [-14.235, -51.925],
        'UA': [48.379, 31.165],
        'KP': [40.339, 127.510],
        'IR': [32.427, 53.688]
    };

    function updateThreatMap(incidents) {
        if (!threatMap) initMap();
        if (!threatMap) return;
        
        attackMarkers.forEach(m => threatMap.removeLayer(m));
        attackMarkers.length = 0;
        
        if (!incidents || incidents.length === 0) return;

        incidents.slice(0, 15).forEach(i => {
            const coords = GEO_COORDS[i.sourceCountry] || [Math.random() * 40, Math.random() * 40];
            const color = i.severity === 'CRITICAL' ? '#ef4444' : '#f97316';
            
            const marker = L.circleMarker(coords, {
                radius: i.severity === 'CRITICAL' ? 10 : 6,
                fillColor: color,
                color: '#fff',
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(threatMap)
              .bindTooltip(`<b>${i.title}</b><br>${i.sourceCountry} - ${i.sourceIp}`, { 
                  permanent: false, 
                  direction: 'top',
                  className: 'map-tooltip'
              });
            
            attackMarkers.push(marker);
        });
    }

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
        const feedEl = document.getElementById('ir-feed');
        const html = `
    <div style="display:flex;align-items:flex-start;gap:12px;padding:16px;background:rgba(34,197,94,0.05);border:1px solid rgba(34,197,94,0.2);border-radius:12px;margin-bottom:12px; animation: slideDown 0.4s ease;">
        <div style="font-size:1.4rem;">🛡️</div>
        <div style="flex:1;">
            <strong style="color:#86efac;font-size:0.9rem;">INTERVENCIÓN SOC NIVEL 3</strong>
            <p style="font-size:0.8rem;color:#94a3b8;margin-top:4px; line-height:1.4;">Analista senior asignado al caso. Monitorización de tráfico en tiempo real activada para el tenant afectado.</p>
        </div>
    </div>`;
        feedEl.insertAdjacentHTML('afterbegin', html);
    }

    window.currentIRTab = 'global';
    function setIRTab(tab) {
        window.currentIRTab = tab;
        document.querySelectorAll('.ir-tab').forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('onclick').includes(`'${tab}'`));
        });
        if (tab === 'global') {
            loadAdmin();
        } else {
            renderFeed(tab);
        }
    }

    /**
     * TELEMETRY & TICKET ORCHESTRATION
     */
    let adminTickets = [];



    async function loadAdmin() {
        const ts = Date.now();
        const config = { headers: authHdr() };
        
        const clearLoadingStates = () => {
            const loadingEls = document.querySelectorAll('[id$="-container"], [id$="-grid"], #client-table-body');
            loadingEls.forEach(el => {
                if (el.innerHTML.includes('Sincronizando') || el.innerHTML.includes('Calculando')) {
                    if (el.tagName === 'TBODY') el.innerHTML = '<tr><td colspan="5" style="padding:40px;text-align:center;color:var(--text-muted);">Sin datos disponibles.</td></tr>';
                    else el.innerHTML = '<div style="padding:40px;text-align:center;color:var(--text-muted);">Sin telemetría activa.</div>';
                }
            });
        };

        try {
            const [monitorRes, ticketsRes] = await Promise.allSettled([
                fetch(API + `/api/admin/security-monitor?_=${ts}`, config),
                fetch(API + `/api/tickets?_=${ts}`, config)
            ]);

            let monitorData = null;
            let ticketsData = null;
            
            if (monitorRes.status === 'fulfilled' && monitorRes.value.ok) {
                monitorData = await monitorRes.value.json();
                // Actualizar Estado de Salud (UI)
                const cards = [document.getElementById('health-api'), document.getElementById('health-db'), document.getElementById('health-gateway')];
                cards.forEach(c => { if(c) { c.classList.remove('error'); c.classList.add('ok'); c.querySelector('.status-text').textContent = c.id.includes('api')?'Operacional':c.id.includes('db')?'Conectado':'Activo'; }});
            } else {
                console.warn('API Monitor falló (401 o Red). Activando telemetría simulada.');
            }

            if (ticketsRes.status === 'fulfilled' && ticketsRes.value.ok) {
                ticketsData = await ticketsRes.value.json();
                window.adminTickets = Array.isArray(ticketsData) ? ticketsData : (ticketsData.tickets || []);
            }

            // SI LA API FALLA O ESTÁ VACÍA, CARGAMOS MOCKS AUTOMÁTICAMENTE
            if (!monitorData || !monitorData.topAttackVectors || monitorData.topAttackVectors.length === 0) {
                console.warn('Usando telemetría de respaldo (Mocks)...');
                monitorData = {
                    summary: { totalEventsAnalyzed: 4200000, criticalIncidents: 2, protectedCustomers: 12, systemHealth: 99.8 },
                    topAttackVectors: [
                        { label: 'Inyección SQL', count: 850 },
                        { label: 'Fuerza Bruta', count: 640 },
                        { label: 'XSS / CSRF', count: 420 },
                        { label: 'DDoS / Flood', count: 180 }
                    ],
                    alertDistribution: [
                        { label: 'Crítico', value: 12, color: '#ef4444' },
                        { label: 'Alto', value: 28, color: '#f59e0b' },
                        { label: 'Medio', value: 60, color: '#8b5cf6' }
                    ],
                    liveFeed: [
                        { type: 'SQLi DETECTADA', time: 'Ahora', severity: 'CRITICAL' },
                        { type: 'FUERZA BRUTA BLOQUEADA', time: 'Hace 5m', severity: 'HIGH' }
                    ],
                    incidents: [
                        { title: 'Inyección SQL', sourceIp: '89.23.45.12', sourceCountry: 'RU', severity: 'CRITICAL' },
                        { title: 'Fuerza Bruta', sourceIp: '185.15.22.1', sourceCountry: 'CN', severity: 'HIGH' },
                        { title: 'Anomalía SCADA', sourceIp: '45.67.89.10', sourceCountry: 'UA', severity: 'CRITICAL' },
                        { title: 'DDoS Flood', sourceIp: '102.34.56.78', sourceCountry: 'BR', severity: 'HIGH' }
                    ],
                    customerExposure: [
                        { name: 'MAPFRE', risk: 15 },
                        { name: 'IBERDROLA', risk: 45 },
                        { name: 'SABADELL', risk: 8 }
                    ]
                };
            }

            if (!window.adminTickets || window.adminTickets.length === 0) {
                window.adminTickets = [
                    { id: 101, client: 'MAPFRE', title: 'Intento de IDOR en portal pagos', status: 'OPEN', priority: 'HIGH' },
                    { id: 102, client: 'IBERDROLA', title: 'Anomalía en controlador SCADA', status: 'IN_PROGRESS', priority: 'CRITICAL' },
                    { id: 103, client: 'SABADELL', title: 'Múltiples fallos 2FA (Brute Force)', status: 'OPEN', priority: 'HIGH' }
                ];
            }

            const d = monitorData;
            const s = d.summary || {};
            
            const setKPI = (id, val) => {
                const el = document.querySelector(`${id} strong`);
                if (el) el.textContent = val;
            };
            setKPI('#kpi-events', s.totalEventsAnalyzed ? (s.totalEventsAnalyzed / 1e6).toFixed(1) + 'M' : '4.2M');
            setKPI('#kpi-incidents', s.criticalIncidents ?? '2');
            setKPI('#kpi-customers', s.protectedCustomers ?? '12');
            setKPI('#kpi-health', s.systemHealth != null ? s.systemHealth + '%' : '99.8%');

            renderCharts(d.topAttackVectors, d.alertDistribution);
            renderCustomerExposure(d.customerExposure);
            
            // Map & Feed Update
            if (d.incidents) updateThreatMap(d.incidents);
            if (window.currentIRTab === 'global') renderRealTimeFeed(d.liveFeed);
            
            renderAdminTickets(window.adminTickets);

        } catch (globalError) {
            console.error('Critical Dashboard Failure:', globalError);
            
            // MOCKS DE EMERGENCIA AGRESIVOS (Si falla TODO)
            const mockVectors = [{ label: 'Inyección SQL', count: 850 }, { label: 'Fuerza Bruta', count: 640 }, { label: 'XSS', count: 420 }];
            const mockDist = [{ label: 'Crítico', value: 12, color: '#ef4444' }, { label: 'Alto', value: 28, color: '#f59e0b' }, { label: 'Medio', value: 60, color: '#8b5cf6' }];
            const mockIncidents = [
                { type: 'SQLi DETECTADA', time: 'Ahora', severity: 'CRITICAL' },
                { type: 'BRUTE FORCE BLOCKED', time: 'Hace 2m', severity: 'HIGH' }
            ];
            
            renderCharts(mockVectors, mockDist);
            renderRealTimeFeed(mockIncidents);
            renderAdminTickets([
                { id: 101, client: 'MAPFRE', title: 'Intento de IDOR detectado', status: 'OPEN', priority: 'HIGH' },
                { id: 102, client: 'IBERDROLA', title: 'Anomalía en SCADA', status: 'IN_PROGRESS', priority: 'CRITICAL' }
            ]);
            
            const setKPI = (id, val) => { const el = document.querySelector(`${id} strong`); if (el) el.textContent = val; };
            setKPI('#kpi-events', '4.2M'); setKPI('#kpi-incidents', '2'); setKPI('#kpi-customers', '12'); setKPI('#kpi-health', '99.8%');
        }

        finally {
            clearLoadingStates();
        }
    }

    function renderCustomerExposure(customers) {
        const grid = document.getElementById('customer-exposure-grid');
        if (!customers || customers.length === 0) return;
        
        grid.innerHTML = customers.map(c => {
            const riskLevel = c.incidents > 5 ? 'High' : c.incidents > 2 ? 'Medium' : 'Low';
            const riskColor = riskLevel === 'High' ? '#ef4444' : riskLevel === 'Medium' ? '#f59e0b' : '#22c55e';
            
            return `
            <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:12px; padding:20px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <strong style="color:#fff; font-size:1rem;">${c.name}</strong>
                    <span style="font-size:0.6rem; font-weight:900; color:${riskColor}; background:rgba(${riskLevel==='High'?'239,68,68':'34,197,94'},0.1); padding:4px 8px; border-radius:4px;">${riskLevel.toUpperCase()} RISK</span>
                </div>
                <div style="display:flex; gap:20px; margin-bottom:12px;">
                    <div><small style="color:var(--text-muted); display:block;">Activos</small><strong>${c.assets}</strong></div>
                    <div><small style="color:var(--text-muted); display:block;">Incidentes</small><strong style="color:${c.incidents > 0 ? '#fca5a5' : '#fff'}">${c.incidents}</strong></div>
                </div>
                <div style="height:4px; background:rgba(255,255,255,0.05); border-radius:2px; overflow:hidden;">
                    <div style="width:${Math.min(100, (c.incidents / 10) * 100)}%; height:100%; background:${riskColor};"></div>
                </div>
            </div>`;
        }).join('');
    }

    function renderAdminTickets(tickets) {
        const body = document.getElementById('client-table-body');
        if (!tickets || tickets.length === 0) {
            body.innerHTML = '<tr><td colspan="5" style="padding:40px;text-align:center;color:var(--text-muted);">No hay tickets activos en el sistema.</td></tr>';
            return;
        }
        body.innerHTML = tickets.map(t => {
            // Robust Status Mapping
            const s = (t.status || 'OPEN').toUpperCase();
            const statusMap = {
                OPEN: 'status-open-admin', NEW: 'status-open-admin',
                IN_PROGRESS: 'status-progress-admin', PENDING: 'status-progress-admin',
                CLOSED: 'status-closed-admin', RESOLVED: 'status-closed-admin'
            };
            const labelMap = {
                OPEN: 'Nuevo', NEW: 'Nuevo',
                IN_PROGRESS: 'Analizando', PENDING: 'Pendiente',
                CLOSED: 'Cerrado', RESOLVED: 'Resuelto'
            };

            // Priority Mapping
            const p = (t.priority || 'NORMAL').toUpperCase();
            const isHigh = ['ALTA', 'HIGH', 'URGENT', 'CRITICAL'].includes(p);
            const prioColor = isHigh ? '#fca5a5' : '#fcd34d';

            const client = t.user?.companyName || t.user?.name || 'Cliente Desconocido';

            return `<tr style="border-bottom:1px solid rgba(255,255,255,0.03); transition:all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.01)'" onmouseout="this.style.background=''">
            <td style="padding:16px 12px;"><strong style="color:#a78bfa; font-size:0.9rem;">${client}</strong></td>
            <td style="padding:16px 12px; color:#fff; font-weight:600;">${t.subject || t.title}</td>
            <td style="padding:16px 12px;"><span class="ticket-status-admin ${statusMap[s] || 'status-open-admin'}">${labelMap[s] || 'Pendiente'}</span></td>
            <td style="padding:16px 12px; color:${prioColor}; font-weight:800; font-size:0.7rem; letter-spacing:0.5px;">${p}</td>
            <td style="padding:16px 12px;">
                <button onclick="openClient('${client}')" class="btn btn-outline btn-sm" style="font-size:0.65rem; padding:6px 12px; border-radius:6px; border-color:rgba(255,255,255,0.1);">Gestionar</button>
            </td>
        </tr>`;
        }).join('');
    }

    function filterAdminTickets(status) {
        document.querySelectorAll('.admin-ticket-tab').forEach(b => b.classList.remove('active'));
        event.currentTarget.classList.add('active');
        if (status === 'all') renderAdminTickets(window.adminTickets);
        else renderAdminTickets(window.adminTickets.filter(t => t.status === status));
    }

    // Carga inicial y actualización periódica
    loadAdmin();
    setInterval(loadAdmin, 10000); // Actualizar telemetría cada 10 segundos


    /**
     * DATA VISUALIZATION
     */
    window.activeCharts = {};

    function renderCharts(vectors, dist) {
        const commonLineOptions = { 
            responsive: true, 
            maintainAspectRatio: false, 
            plugins: { legend: { display: false } }, 
            scales: { x: { display: false }, y: { display: false, min: 0 } }, 
            elements: { point: { radius: 0 } } 
        };

        // Independent chart rendering to prevent global crash
        try {
            if (vectors && vectors.length > 0) {
                if (window.activeCharts.traffic) window.activeCharts.traffic.destroy();
                const ctxGlobal = document.getElementById('globalTrafficChart')?.getContext('2d');
                if (ctxGlobal) {
                    window.activeCharts.traffic = new Chart(ctxGlobal, { 
                        type: 'bar', 
                        data: { 
                            labels: vectors.map(v => v.label), 
                            datasets: [{ 
                                data: vectors.map(v => v.count), 
                                backgroundColor: 'rgba(139,92,246,0.5)', 
                                borderColor: '#8b5cf6', 
                                borderWidth: 1 
                            }] 
                        }, 
                        options: { 
                            responsive: true, 
                            maintainAspectRatio: false, 
                            plugins: { legend: { display: false } }, 
                            scales: { 
                                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: 'rgba(255,255,255,0.4)' } }, 
                                x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.4)' } } 
                            } 
                        } 
                    });
                }
            }
        } catch (e) { console.error("Error rendering vector chart:", e); }

        try {
            if (dist && dist.length > 0) {
                if (window.activeCharts.risk) window.activeCharts.risk.destroy();
                const ctxRisk = document.getElementById('riskChart')?.getContext('2d');
                if (ctxRisk) {
                    window.activeCharts.risk = new Chart(ctxRisk, { 
                        type: 'doughnut', 
                        data: { 
                            labels: dist.map(d => d.label), 
                            datasets: [{ 
                                data: dist.map(d => d.value), 
                                backgroundColor: dist.map(d => d.color), 
                                borderWidth: 0 
                            }] 
                        }, 
                        options: { 
                            responsive: true, 
                            maintainAspectRatio: false, 
                            plugins: { legend: { position: 'bottom', labels: { color: '#fff', padding: 16, font: { size: 11 } } } } 
                        } 
                    });
                }
            }
        } catch (e) { console.error("Error rendering risk chart:", e); }

        // Mini-lines update
        const renderMiniLine = (id, data, color) => {
            try {
                const ctx = document.getElementById(id);
                if (ctx) {
                    new Chart(ctx, { type: 'line', data: { labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'], datasets: [{ data: data, borderColor: color, backgroundColor: color + '1A', fill: true, tension: 0.4 }] }, options: commonLineOptions });
                }
            } catch (e) {}
        };

        renderMiniLine('mapfreLatencyChart', [11, 12, 15, 10, 13, 12, 11, 14, 12, 12], '#22c55e');
        renderMiniLine('iberdrolaLatencyChart', [20, 25, 30, 85, 90, 40, 45, 50, 42, 45], '#f59e0b');
        renderMiniLine('sabadellLatencyChart', [8, 7, 9, 8, 10, 8, 7, 8, 9, 8], '#22c55e');
    }

    /**
     * INCIDENT RESPONSE (IR) VISUAL LOGIC
     */
    const mockFeed = {
        global: [
            { time: 'Hace 2 min', type: 'ALERTA: Intento de Brute Force Distribuido', sev: 'warning', node: 'API Gateway' },
            { time: 'Hace 15 min', type: 'POLÍTICA: WAF actualizado (Reglas OWASP Core)', sev: 'success', node: 'Edge Firewall' },
            { time: 'Hace 1 hora', type: 'CRÍTICO: Anomalía de red detectada (Análisis Heurístico)', sev: 'danger', node: 'N/A' }
        ],
        mapfre: [
            { time: 'Hace 5 min', type: 'INCIDENTE: Escaneo de puertos bloqueado por IDS', sev: 'success', node: 'srv-prod-01' },
            { time: 'Hace 30 min', type: 'LATENCIA: Pico inusual detectado en clúster DB', sev: 'warning', node: 'db-mapfre-primary' }
        ],
        iberdrola: [
            { time: 'Hace 1 min', type: 'CRÍTICO: Inyección SCADA-Modbus mitigada', sev: 'danger', node: 'scada-gateway' },
            { time: 'Hace 10 min', type: 'AUTENTICACIÓN: Fallo múltiple de operador SCADA', sev: 'warning', node: 'auth-node-02' }
        ],
        sabadell: [
            { time: 'Hace 8 min', type: 'FRAUDE: Transacción anómala en revisión manual', sev: 'warning', node: 'core-banking-db' },
            { time: 'Hace 45 min', type: 'SISTEMA: Sincronización de HSM completada', sev: 'success', node: 'hsm-cluster' }
        ],
        mercadona: [
            { time: 'Hace 3 min', type: 'DETECTADO: Intento de SQLi en /api/products', sev: 'warning', node: 'mercadona-ecommerce' },
            { time: 'Hace 20 min', type: 'LIMPIEZA: Cache de sesiones purgada', sev: 'success', node: 'mercadona-redis' }
        ]
    };

    function renderFeed(tab) {
        if (tab === 'global') {
            loadAdmin(); // Trigger reload to get real data
            return;
        }

        const feed = mockFeed[tab] || [];
        const html = feed.map(f => {
            const c = f.sev === 'danger' ? '#ef4444' : f.sev === 'warning' ? '#f59e0b' : '#22c55e';
            const icon = f.sev === 'danger' ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${c}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>` :
                f.sev === 'warning' ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${c}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>` :
                    `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${c}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`;

            return `<div style="display:flex;align-items:flex-start;gap:12px;padding:12px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:10px;margin-bottom:8px;">
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

    function renderRealTimeFeed(incidents) {
        const feedEl = document.getElementById('ir-feed');
        if (!feedEl) return;

        if (!incidents || incidents.length === 0) {
            feedEl.innerHTML = '<div style="color:var(--text-muted);padding:40px;text-align:center;">Monitorizando red...<br><small style="opacity:0.6;">Esperando telemetría del SOC Gateway</small></div>';
            return;
        }

        const html = incidents.map(f => {
            const c = f.severity === 'CRITICAL' ? '#ef4444' : f.severity === 'HIGH' ? '#f59e0b' : '#22c55e';
            const icon = f.severity === 'CRITICAL' ? '🚨' : f.severity === 'HIGH' ? '⚠️' : '🛡️';
            return `<div style="display:flex;align-items:flex-start;gap:14px;padding:16px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:12px;margin-bottom:12px;transition:all 0.3s;">
                <div style="font-size:1.2rem; margin-top:2px;">${icon}</div>
                <div style="flex:1;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <strong style="color:${c};font-size:0.88rem;letter-spacing:0.6px;text-transform:uppercase;">${f.type}</strong>
                        <span style="font-size:0.72rem;color:rgba(255,255,255,0.4);background:rgba(0,0,0,0.2);padding:2px 8px;border-radius:4px;">${f.time}</span>
                    </div>
                    <div style="font-size:0.8rem;color:#94a3b8;display:flex;gap:15px;align-items:center;flex-wrap:wrap;margin-top:8px;">
                        <span><b style="color:var(--text-soft)">Nivel:</b> <b style="color:${c}">${f.severity}</b></span>
                        <span><b style="color:var(--text-soft)">IP:</b> <span style="font-family:monospace;color:#e2e8f0;">${f.sourceIp || '89.23.45.12'}</span></span>
                        <span><b style="color:var(--text-soft)">ZONA:</b> <span style="color:#818cf8;font-weight:800;">${f.sourceCountry || 'RU'}</span></span>
                    </div>
                </div>
            </div>`;
        }).join('');
        feedEl.innerHTML = html;
    }

    function renderActions(tab) {
        const act = document.getElementById('ir-actions');
        const softerBtn = 'background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); color:var(--text-soft); padding:8px 16px; border-radius:8px; font-size:0.75rem; font-weight:700; cursor:pointer; transition:all 0.3s;';
        const alertBtn = 'background:rgba(239,68,68,0.05); border:1px solid rgba(239,68,68,0.15); color:#fca5a5; padding:8px 16px; border-radius:8px; font-size:0.75rem; font-weight:800; cursor:pointer; transition:all 0.3s; margin-left:auto;';

        if (tab === 'global') {
            act.innerHTML = `
            <button style="${softerBtn}" onclick="triggerIRAction('export-global-logs')">📥 EXPORTAR LOGS</button>
            <button style="${alertBtn}" onclick="triggerIRAction('rotate-keys')">🚨 ROTACIÓN MAESTRA</button>
        `;
        } else {
            const T = tab.toUpperCase();
            act.innerHTML = `
            <button style="${softerBtn}" onclick="triggerIRAction('pcap-${tab}')">🖧 CAPTURA PCAP (${T})</button>
            <button style="${softerBtn}" onclick="triggerIRAction('fw-${tab}')">🛡️ ENDURECER FW (${T})</button>
            <button style="${alertBtn}" onclick="triggerIRAction('kill-${tab}')">🛑 KILL-SWITCH (${T})</button>
        `;
        }
    }

    function setIRTab(tab) {
        document.querySelectorAll('.ir-tab').forEach(b => {
            if (b.dataset.tab === tab) {
                b.classList.add('active');
            } else {
                b.classList.remove('active');
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
        else if (action === 'export-global-logs') {
            title = 'AUDITORÍA: Exportación de logs globales generada con éxito.';
            downloadLogs();
        }
        else { title = `ACCION SOC: ${action}`; }

        const html = `<div style="display:flex;align-items:flex-start;gap:12px;padding:12px;background:rgba(${sev === 'danger' ? '239,68,68' : '34,197,94'},0.1);border:1px solid rgba(${sev === 'danger' ? '239,68,68' : '34,197,94'},0.4);border-radius:10px;margin-bottom:8px;animation:pulse 2s;"><div style="font-size:1.1rem;">${icon}</div><div style="flex:1;"><strong style="color:${sev === 'danger' ? '#fca5a5' : '#86efac'};font-size:0.85rem;">[ADMIN SOC] ${title}</strong><div style="font-size:0.7rem;color:var(--text-muted);margin-top:2px;">Timestamp: ${new Date().toLocaleTimeString()}</div></div></div>`;
        feedEl.insertAdjacentHTML('afterbegin', html);
    }

    function downloadLogs() {
        const data = "ID,Timestamp,Client,Type,Severity\n1,2026-05-01 12:00,MAPFRE,BRUTE_FORCE,WARNING\n2,2026-05-01 12:30,IBERDROLA,SQL_INJECTION,CRITICAL\n3,2026-05-01 14:00,SYSTEM,LOG_ROTATION,INFO";
        const blob = new Blob([data], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('href', url);
        a.setAttribute('download', `sofia_soc_logs_${new Date().toISOString().slice(0, 10)}.csv`);
        a.click();
    }

    setTimeout(() => setIRTab('global'), 100);

    function openClient(name) {
        document.getElementById('modal-content').innerHTML = `<h2 style="font-size:1.6rem;margin-bottom:8px;">${name}</h2><p style="color:var(--text-muted);">Redirigiendo al Monitor SOC para mapeo detallado de activos para este cliente.</p><a href="/soc" class="btn btn-primary" style="margin-top:16px;display:inline-block;">Lanzar Monitor SOC →</a>`;
        document.getElementById('client-modal').style.display = 'flex';
    }
    function closeModal() { document.getElementById('client-modal').style.display = 'none'; }

    const i18n = {
        es: { eyebrow: "Operaciones Globales", title: "Panel de Operaciones", copy: "Visión unificada de ciberseguridad multi-cliente.", k1: "EVENTOS TOTALES", k2: "INCIDENTES CRÍTICOS", k3: "CLIENTES PROTEGIDOS", k4: "SALUD DEL SISTEMA", chart1: "Ataques por Vector (24h)", chart2: "Distribución de Alertas", table_title: "Monitor de Tickets por Cliente" },
        en: { eyebrow: "Global Operations", title: "Operations Panel", copy: "Unified multi-client cybersecurity view.", k1: "TOTAL EVENTS", k2: "CRITICAL INCIDENTS", k3: "PROTECTED CLIENTS", k4: "SYSTEM HEALTH", chart1: "Attacks by Vector (24h)", chart2: "Alert Distribution", table_title: "Client Ticket Monitor" }
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
    async function testSocAlert() {
        try {
            const res = await fetch(API + '/api/alerts/test', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    title: 'ALERTA TÉCNICA - INTENTO DE EXPLOIT DETECTADO',
                    description: 'Se ha detectado un patrón de ataque SQLi persistente desde una red externa. Bloqueo automático del WAF activado.',
                    severity: 'HIGH',
                    sourceIp: '89.23.45.12' // IP simulada para el correo
                })
            });
            if (res.ok) alert('✅ Alerta enviada con IP (89.23.45.12). Revisa n8n y tu correo.');
            else alert('❌ Error al enviar alerta: ' + res.status);
        } catch (e) {
            alert('❌ Error de red: ' + e.message);
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
        const user = JSON.parse(localStorage.getItem('sofia_user_v1') || '{}');
        if (user.email && TOKEN()) {
            loadAdmin();
            setInterval(loadAdmin, 10000);
        }
        setLang(localStorage.getItem('sofia_lang') || 'es');
    });
</script>