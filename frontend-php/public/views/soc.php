<?php
$activeNav = 'soc';
$headerEyebrow = 'SOC Security Monitor';
$headerTitle = 'Consola operativa de monitorización y defensa';
$headerCopy = 'Vista consolidada de incidentes, presión de ataque, orígenes, servicios cubiertos y estado de operación.';
?>
<main class="app-shell readdy-dashboard">
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
                    <span class="meta-label">Periodo</span>
                    <strong>Last 24 Hours · Real-time</strong>
                </article>
                <article>
                    <span class="meta-label">Fuentes</span>
                    <strong>WAF · VPN · EDR · Cloud</strong>
                </article>
                <article>
                    <span class="meta-label">Analyst feed</span>
                    <strong>Operativo</strong>
                </article>
            </div>
            <div class="header-links">
                <a class="btn btn-secondary" href="/dashboard">Volver al dashboard</a>
                <a class="btn btn-primary" href="http://localhost:3000" target="_blank" rel="noreferrer">Abrir Grafana</a>
            </div>
        </section>

        <section id="soc-status" class="status-strip">
            <span class="signal-chip signal-chip-soft">WAF Active</span>
            <span class="signal-chip signal-chip-soft">VPN Telemetry: OK</span>
            <span class="signal-chip signal-chip-soft">SIEM Logs: Synced</span>
            <span class="signal-chip" style="background:rgba(239, 68, 68, 0.2); border-color:rgba(239, 68, 68, 0.4); color:#fca5a5;">Alerts: 3 Critical</span>
        </section>
        <section id="soc-kpis" class="kpi-grid">
            <article class="kpi-card" data-tone="warn">
                <span class="meta-label">Eventos / Hora</span>
                <strong>24,532</strong>
                <small>+12% vs Promedio</small>
                <div class="tone-bar"></div>
            </article>
            <article class="kpi-card" data-tone="bad">
                <span class="meta-label">Intentos Críticos</span>
                <strong>418</strong>
                <small>Requieren revisión</small>
                <div class="tone-bar"></div>
            </article>
            <article class="kpi-card" data-tone="ok">
                <span class="meta-label">Bloqueos Activos</span>
                <strong>98.7%</strong>
                <small>Eficacia WAF</small>
                <div class="tone-bar"></div>
            </article>
            <article class="kpi-card" data-tone="ok">
                <span class="meta-label">SLA Respuesta</span>
                <strong>4m 12s</strong>
                <small>Nivel de servicio T1</small>
                <div class="tone-bar"></div>
            </article>
        </section>

        <section class="executive-grid executive-grid-wide">
            <article class="panel panel-feature" id="soc-vectors-section">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Attack surface</span>
                        <h2>Vectores y tendencias</h2>
                    </div>
                </div>
                <div id="soc-vectors" class="stack-list stack-list-spacious">
                    <div class="stack-item">
                        <div style="display:flex; justify-content:space-between;">
                            <strong>SQL Injection (Payloads)</strong>
                            <span class="severity warn">HIGH</span>
                        </div>
                        <small>12,450 intentos bloqueados en capa perimetral</small>
                    </div>
                    <div class="stack-item">
                        <div style="display:flex; justify-content:space-between;">
                            <strong>Credential Stuffing</strong>
                            <span class="severity bad">CRITICAL</span>
                        </div>
                        <small>Picos detectados en VIP de Nova Retail</small>
                    </div>
                    <div class="stack-item">
                        <div style="display:flex; justify-content:space-between;">
                            <strong>Path Traversal Attempts</strong>
                            <span class="severity ok">LOW</span>
                        </div>
                        <small>340 firmas en entornos de test aislados</small>
                    </div>
                </div>
            </article>
            <article class="panel" id="soc-incidents-section">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Live feed</span>
                        <h2>Incidentes en curso</h2>
                    </div>
                </div>
                <div id="soc-incidents" class="stack-list">
                    <div class="stack-item ticket-row">
                        <span class="meta-label" style="margin-bottom:4px; font-size:0.7rem;">10:45 AM</span>
                        <strong>Alerta de acceso inusual (VPN)</strong>
                        <small>IP: 185.15.xx.xx (Rusia) -> Lynx Corp.</small>
                    </div>
                    <div class="stack-item ticket-row">
                        <span class="meta-label" style="margin-bottom:4px; font-size:0.7rem;">10:42 AM</span>
                        <strong>DDoS mitigado (Capa 7)</strong>
                        <small>Target: nova-retail.com/checkout</small>
                    </div>
                    <div class="stack-item ticket-row">
                        <span class="meta-label" style="margin-bottom:4px; font-size:0.7rem;">10:15 AM</span>
                        <strong>Exfiltración bloqueada (DLP)</strong>
                        <small>Intento de acceso a BD desde Iberia Health</small>
                    </div>
                    <div class="stack-item ticket-row" style="opacity: 0.7;">
                        <span class="meta-label" style="margin-bottom:4px; font-size:0.7rem;">09:50 AM</span>
                        <strong>Escaneo masivo de puertos</strong>
                        <small>Bloqueado en Firewall perimetral Madrid</small>
                    </div>
                </div>
            </article>
        </section>

        <section class="executive-grid">
            <article class="panel" id="soc-countries-section">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Geolocation</span>
                        <h2>Top países origen</h2>
                    </div>
                </div>
                <div id="soc-countries" class="stack-list">
                    <div class="stack-item">
                        <strong>🇷🇺 Rusia (ASNs sospechosos)</strong>
                        <small>45% del tráfico anómalo</small>
                    </div>
                    <div class="stack-item">
                        <strong>🇨🇳 China (Scanners conocidos)</strong>
                        <small>22% del tráfico anómalo</small>
                    </div>
                    <div class="stack-item">
                        <strong>🇺🇸 USA (Proxies / VPNs)</strong>
                        <small>15% del tráfico anómalo</small>
                    </div>
                </div>
            </article>
            <article class="panel" id="soc-services-section">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Coverage</span>
                        <h2>Servicios protegidos</h2>
                    </div>
                </div>
                <div id="soc-services" class="stack-list">
                    <div class="stack-item">
                        <strong>Endpoint Security (EDR)</strong>
                        <small>4,320 agentes instalados y reportando</small>
                    </div>
                    <div class="stack-item">
                        <strong>Cloud WAF Edge</strong>
                        <small>Protección activa en 14 endpoints</small>
                    </div>
                    <div class="stack-item">
                        <strong>Identity Protection (IAM)</strong>
                        <small>98% de la plantilla con MFA forzado</small>
                    </div>
                </div>
            </article>

            <!-- Gráficos Integrados y Ticketing para todas las empresas -->
            <article class="panel" style="grid-column: 1 / -1; margin-top: 24px;">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Monitorización de Tickets</span>
                        <h2>Visualización de incidencias por Organización (Simulación GET ID)</h2>
                    </div>
                </div>
                <div class="soc-insights" style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div class="css-graph">
                        <h4 style="margin-top:0; color:var(--text-soft);">Tickets Abiertos por Empresa</h4>
                        <div class="bar-chart">
                            <div class="bar" style="--h: 85%;"><span class="label">Lynx</span><div class="bar-fill"></div></div>
                            <div class="bar" style="--h: 40%;"><span class="label">Nova</span><div class="bar-fill"></div></div>
                            <div class="bar" style="--h: 95%;"><span class="label">Iberia</span><div class="bar-fill"></div></div>
                            <div class="bar" style="--h: 20%;"><span class="label">Otros</span><div class="bar-fill"></div></div>
                        </div>
                    </div>
                    <div class="stack-list">
                        <div class="stack-item ticket-row">
                            <strong>Lynx Industrial Group</strong>
                            <small>Ticket #1023: Intrusión en red centralórica.</small>
                            <a href="/admin/ticket.php?id=1023" class="btn btn-secondary btn-sm">Ver Ticket</a>
                        </div>
                        <div class="stack-item ticket-row">
                            <strong>Nova Retail Systems</strong>
                            <small>Ticket #1044: WAF bypass alert (Payload sospechoso).</small>
                            <a href="/admin/ticket.php?id=1044" class="btn btn-secondary btn-sm">Ver Ticket</a>
                        </div>
                        <div class="stack-item ticket-row">
                            <strong>Iberia Health Tech</strong>
                            <small>Ticket #1055: Filtración de BBDD pacientes expuesta.</small>
                            <a href="/admin/ticket.php?id=1055" class="btn btn-secondary btn-sm">Ver Ticket</a>
                        </div>
                        <small style="margin-top:12px; color:var(--warn);">[!] endpoints de tickets no securizados por ID (Insecure Direct Object Reference)</small>
                    </div>
                </div>
            </article>
        </section>
    </section>
</main>
