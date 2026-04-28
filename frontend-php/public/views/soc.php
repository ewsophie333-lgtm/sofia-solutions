<?php
$activeNav = 'soc';
$headerEyebrow = 'SOC Security Monitor';
$headerTitle = 'Consola operativa de monitorización y defensa';
$headerCopy = 'Vista consolidada de incidentes, vectores de ataque, cobertura y operación en tiempo real.';
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
            <small>Todos los sensores activos y sincronizados.</small>
        </div>
    </aside>

    <section class="content">
        <!-- Header -->
        <header class="panel-header">
            <div>
                <span class="eyebrow"><?= htmlspecialchars($headerEyebrow, ENT_QUOTES, 'UTF-8') ?></span>
                <h1><?= htmlspecialchars($headerTitle, ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="panel-header-copy"><?= htmlspecialchars($headerCopy, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="header-links">
                <span class="context-chip">Actualizado automáticamente</span>
                <span class="context-chip context-chip-soft">En tiempo real</span>
            </div>
        </header>

        <!-- Status strip -->
        <section class="status-strip" style="display:flex;flex-direction:row;flex-wrap:wrap;gap:10px;margin-top:18px;padding:14px 18px;">
            <span style="display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:8px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.25);color:#34d399;font-size:0.8rem;font-weight:600;">
                <span style="width:7px;height:7px;border-radius:50%;background:#10b981;box-shadow:0 0 8px #10b981;animation:pulse 2s infinite;"></span> WAF Activo
            </span>
            <span style="display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:8px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.25);color:#34d399;font-size:0.8rem;font-weight:600;">
                <span style="width:7px;height:7px;border-radius:50%;background:#10b981;box-shadow:0 0 8px #10b981;animation:pulse 2s infinite;"></span> Telemetría VPN: OK
            </span>
            <span style="display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:8px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.25);color:#34d399;font-size:0.8rem;font-weight:600;">
                <span style="width:7px;height:7px;border-radius:50%;background:#10b981;box-shadow:0 0 8px #10b981;animation:pulse 2s infinite;"></span> SIEM: Sincronizado
            </span>
            <span style="display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:8px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.35);color:#f87171;font-size:0.8rem;font-weight:600;">
                <span style="width:7px;height:7px;border-radius:50%;background:#ef4444;box-shadow:0 0 8px #ef4444;animation:pulse 1.2s infinite;"></span> Alertas: 3 Críticas
            </span>
            <span style="display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:8px;background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);color:#fbbf24;font-size:0.8rem;font-weight:600;">
                <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;box-shadow:0 0 8px #f59e0b;animation:pulse 1.8s infinite;"></span> EDR: Monitorizando
            </span>
            <span style="display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:8px;background:rgba(6,182,212,0.08);border:1px solid rgba(6,182,212,0.2);color:#22d3ee;font-size:0.8rem;font-weight:600;">
                <span style="width:7px;height:7px;border-radius:50%;background:#06b6d4;box-shadow:0 0 8px #06b6d4;animation:pulse 2.5s infinite;"></span> Threat Intel: En vivo
            </span>
        </section>
        <style>@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.6;transform:scale(1.4)}}</style>

        <!-- KPIs -->
        <section class="kpi-grid" style="margin-top:20px;">
            <article class="kpi-card" data-tone="warn">
                <span class="meta-label">Eventos / Hora</span>
                <strong>24,532</strong>
                <small>+12% vs promedio</small>
                <div class="tone-bar"></div>
            </article>
            <article class="kpi-card" data-tone="bad">
                <span class="meta-label">Intentos Críticos</span>
                <strong>418</strong>
                <small>Requieren revisión</small>
                <div class="tone-bar"></div>
            </article>
            <article class="kpi-card" data-tone="ok">
                <span class="meta-label">Eficacia WAF</span>
                <strong>98.7%</strong>
                <small>Bloqueos activos</small>
                <div class="tone-bar"></div>
            </article>
            <article class="kpi-card" data-tone="ok">
                <span class="meta-label">SLA Respuesta</span>
                <strong>4m 12s</strong>
                <small>Nivel T1</small>
                <div class="tone-bar"></div>
            </article>
        </section>

        <!-- Tab navigation Premium -->
        <div style="display:flex; gap:8px; margin-top:32px; border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:12px;">
            <button class="soc-tab active" data-tab="overview" onclick="switchTab('overview')" style="padding:10px 24px; background:rgba(6,182,212,0.1); border:1px solid rgba(6,182,212,0.3); border-radius:12px; color:#22d3ee; font-size:0.85rem; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.3s; box-shadow:0 4px 12px rgba(6,182,212,0.15);">Vista General</button>
            <button class="soc-tab" data-tab="incidents" onclick="switchTab('incidents')" style="padding:10px 24px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:12px; color:rgba(148,163,184,0.8); font-size:0.85rem; font-weight:600; cursor:pointer; font-family:inherit; transition:all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="if(!this.classList.contains('active'))this.style.background='rgba(255,255,255,0.02)'">Incidentes Activos <span style="background:#ef4444;color:#fff;padding:2px 8px;border-radius:10px;font-size:0.7rem;margin-left:6px;">3</span></button>
            <button class="soc-tab" data-tab="tickets" onclick="switchTab('tickets')" style="padding:10px 24px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:12px; color:rgba(148,163,184,0.8); font-size:0.85rem; font-weight:600; cursor:pointer; font-family:inherit; transition:all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="if(!this.classList.contains('active'))this.style.background='rgba(255,255,255,0.02)'">Tickets de Soporte</button>
            <button class="soc-tab" data-tab="grafana" onclick="switchTab('grafana')" style="padding:10px 24px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:12px; color:rgba(148,163,184,0.8); font-size:0.85rem; font-weight:600; cursor:pointer; font-family:inherit; transition:all 0.3s; margin-left:auto; display:flex; align-items:center; gap:8px;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="if(!this.classList.contains('active'))this.style.background='rgba(255,255,255,0.02)'"><span style="color:#f59e0b;">📊</span> Grafana Metrics</button>
        </div>

        <!-- TAB: Vista General -->
        <div id="tab-overview" class="soc-tab-panel" style="margin-top:20px;">
            <section class="executive-grid executive-grid-wide">
                <!-- Vectores -->
                <article class="panel panel-feature">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Superficie de ataque</span>
                            <h2>Vectores y tendencias</h2>
                        </div>
                    </div>
                    <div class="stack-list stack-list-spaci                    <div class="stack-item">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <strong>Inyección SQL (Payloads)</strong>
                                <span class="severity warn">ALTO</span>
                            </div>
                            <small>12,450 intentos bloqueados — portal e-commerce de Mercadona</small>
                        </div>
                        <div class="stack-item">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <strong>Relleno de Credenciales</strong>
                                <span class="severity bad">CRÍTICO</span>
                            </div>
                            <small>Picos detectados en portal de empleados de Iberdrola</small>
                        </div>
                        <div class="stack-item">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <strong>Acceso no Autorizado OT</strong>
                                <span class="severity bad">CRÍTICO</span>
                            </div>
                            <small>Red de refinería de Repsol — contenido en perímetro</small>
                        </div>
                        <div class="stack-item">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <strong>Phishing de Correo (BEC)</strong>
                                <span class="severity warn">ALTO</span>
                            </div>
                            <small>Campaña activa detectada contra corredores de MAPFRE</small>
                        </div>        </div>
                    </div>
                </article>

                <!-- Países y servicios -->
                <article class="panel" style="display:flex;flex-direction:column;gap:0;">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Geolocalización</span>
                            <h2>Top países origen</h2>
                        </div>
                    </div>
                    <div class="stack-list" style="flex:1;">
                        <div class="stack-item" style="display:flex;align-items:center;gap:12px;">
                            <span style="font-size:1.4rem;">🇷🇺</span>
                            <div style="flex:1;"><strong>Rusia</strong><small style="display:block;">45% tráfico anómalo — ASNs conocidos</small></div>
                            <div style="width:80px;height:6px;border-radius:3px;background:rgba(239,68,68,0.15);overflow:hidden;"><div style="width:45%;height:100%;background:#ef4444;border-radius:3px;"></div></div>
                        </div>
                        <div class="stack-item" style="display:flex;align-items:center;gap:12px;">
                            <span style="font-size:1.4rem;">🇨🇳</span>
                            <div style="flex:1;"><strong>China</strong><small style="display:block;">22% tráfico anómalo — Scanners masivos</small></div>
                            <div style="width:80px;height:6px;border-radius:3px;background:rgba(245,158,11,0.15);overflow:hidden;"><div style="width:22%;height:100%;background:#f59e0b;border-radius:3px;"></div></div>
                        </div>
                        <div class="stack-item" style="display:flex;align-items:center;gap:12px;">
                            <span style="font-size:1.4rem;">🇺🇸</span>
                            <div style="flex:1;"><strong>EE.UU.</strong><small style="display:block;">15% tráfico anómalo — Proxies / VPNs</small></div>
                            <div style="width:80px;height:6px;border-radius:3px;background:rgba(6,182,212,0.15);overflow:hidden;"><div style="width:15%;height:100%;background:#06b6d4;border-radius:3px;"></div></div>
                        </div>
                        <div class="stack-item" style="display:flex;align-items:center;gap:12px;">
                            <span style="font-size:1.4rem;">🇧🇷</span>
                            <div style="flex:1;"><strong>Brasil</strong><small style="display:block;">10% tráfico anómalo — Botnets</small></div>
                            <div style="width:80px;height:6px;border-radius:3px;background:rgba(139,92,246,0.15);overflow:hidden;"><div style="width:10%;height:100%;background:#8b5cf6;border-radius:3px;"></div></div>
                        </div>
                    </div>
                </article>
            </section>

            <!-- Servicios protegidos y gráfico -->
            <section class="executive-grid" style="margin-top:20px;">
                <article class="panel">
                    <div class="panel-heading">
                        <div><span class="eyebrow">Cobertura</span><h2>Servicios protegidos</h2></div>
                    </div>
                    <div class="stack-list">
                        <div class="stack-item" style="display:flex;align-items:center;gap:12px;">
                            <span style="width:8px;height:8px;border-radius:50%;background:#10b981;box-shadow:0 0 6px #10b981;flex-shrink:0;"></span>
                            <div><strong>Seguridad de Endpoint (EDR)</strong><small style="display:block;">4,320 agentes activos y reportando</small></div>
                        </div>
                        <div class="stack-item" style="display:flex;align-items:center;gap:12px;">
                            <span style="width:8px;height:8px;border-radius:50%;background:#10b981;box-shadow:0 0 6px #10b981;flex-shrink:0;"></span>
                            <div><strong>WAF Cloud Perimetral</strong><small style="display:block;">Protección activa en 14 endpoints</small></div>
                        </div>
                        <div class="stack-item" style="display:flex;align-items:center;gap:12px;">
                            <span style="width:8px;height:8px;border-radius:50%;background:#10b981;box-shadow:0 0 6px #10b981;flex-shrink:0;"></span>
                            <div><strong>Protección de Identidad (IAM)</strong><small style="display:block;">98% de usuarios con MFA forzado</small></div>
                        </div>
                        <div class="stack-item" style="display:flex;align-items:center;gap:12px;">
                            <span style="width:8px;height:8px;border-radius:50%;background:#f59e0b;box-shadow:0 0 6px #f59e0b;flex-shrink:0;"></span>
                            <div><strong>DLP (Data Loss Prevention)</strong><small style="display:block;">Políticas activas — 2 alertas esta semana</small></div>
                        </div>
                    </div>
                </article>

                <!-- Gráfico de tickets por empresa -->
                <article class="panel">
                    <div class="panel-heading">
                        <div><span class="eyebrow">Análisis</span><h2>Tickets abiertos por cliente</h2></div>
                    </div>
                    <div class="css-graph" style="padding:8px 0;">
                        <div class="bar-chart" style="height:150px;">
                            <div class="bar" style="--h: 85%;"><span class="label">Aquila</span><div class="bar-fill"></div></div>
                            <div class="bar" style="--h: 40%;"><span class="label">Nordex</span><div class="bar-fill"></div></div>
                            <div class="bar" style="--h: 95%;"><span class="label">Helios</span><div class="bar-fill"></div></div>
                            <div class="bar" style="--h: 20%;"><span class="label">Otros</span><div class="bar-fill"></div></div>
                        </div>
                    </div>
                </article>
            </section>
        </div>

        <!-- TAB: Incidentes -->
        <div id="tab-incidents" class="soc-tab-panel" style="display:none; margin-top:28px;">
            <section class="executive-grid executive-grid-wide">
                <article class="panel panel-feature" style="grid-column:1/-1; border-radius:20px; background:linear-gradient(180deg, rgba(255,255,255,0.02) 0%, rgba(0,0,0,0) 100%);">
                    <div class="panel-heading" style="border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:16px;">
                        <div>
                            <span class="eyebrow" style="color:#ef4444;">Centro de Operaciones</span>
                            <h2 style="font-size:1.4rem;">Incidentes de Seguridad en Curso</h2>
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button class="btn btn-outline" style="border-radius:8px; padding:6px 12px; font-size:0.8rem;">Filtrar</button>
                            <button class="btn" style="background:#ef4444; color:#fff; border-radius:8px; padding:6px 12px; font-size:0.8rem; border:none;">Generar Reporte</button>
                        </div>
                    </div>
                    <div class="stack-list" style="padding-top:16px;">
                        <div class="stack-item ticket-row" style="display:grid;grid-template-columns:80px 1fr auto;align-items:center;gap:16px; background:rgba(239,68,68,0.04); border:1px solid rgba(239,68,68,0.15); border-radius:12px; padding:16px;">
                            <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700; font-family:monospace;">10:45 AM</span>
                            <div>
                                <strong style="font-size:1.05rem; color:#fff;">Alerta de acceso inusual — VPN Iberdrola</strong>
                                <small style="display:block; color:var(--text-soft); margin-top:4px; font-size:0.85rem;">IP: 185.220.101.14 (Rusia) → Intentos anómalos contra portal de empleados</small>
                            </div>
                            <span class="severity bad" style="padding:6px 12px; font-size:0.75rem; border-radius:8px;">CRÍTICO</span>
                        </div>
                        <div class="stack-item ticket-row" style="display:grid;grid-template-columns:80px 1fr auto;align-items:center;gap:16px; background:rgba(239,68,68,0.04); border:1px solid rgba(239,68,68,0.15); border-radius:12px; padding:16px;">
                            <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700; font-family:monospace;">10:15 AM</span>
                            <div>
                                <strong style="font-size:1.05rem; color:#fff;">Malware beaconing detectado — Banco Sabadell</strong>
                                <small style="display:block; color:var(--text-soft); margin-top:4px; font-size:0.85rem;">Banca central: comunicación C2 anómala detectada y bloqueada en capa 7</small>
                            </div>
                            <span class="severity bad" style="padding:6px 12px; font-size:0.75rem; border-radius:8px;">CRÍTICO</span>
                        </div>
                        <div class="stack-item ticket-row" style="display:grid;grid-template-columns:80px 1fr auto;align-items:center;gap:16px; background:rgba(245,158,11,0.04); border:1px solid rgba(245,158,11,0.15); border-radius:12px; padding:16px;">
                            <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700; font-family:monospace;">10:42 AM</span>
                            <div>
                                <strong style="font-size:1.05rem; color:#fff;">Phishing Corporativo — MAPFRE Seguros</strong>
                                <small style="display:block; color:var(--text-soft); margin-top:4px; font-size:0.85rem;">Adjunto malicioso entregado en buzón de 14 usuarios. Auto-purgado por políticas DLP.</small>
                            </div>
                            <span class="severity warn" style="padding:6px 12px; font-size:0.75rem; border-radius:8px;">ALTO</span>
                        </div>
                        <div class="stack-item ticket-row" style="display:grid;grid-template-columns:80px 1fr auto;align-items:center;gap:16px; opacity:0.7; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:12px; padding:16px;">
                            <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700; font-family:monospace;">09:50 AM</span>
                            <div>
                                <strong style="font-size:1.05rem; color:var(--text-soft);">SQLi Masivo — Mercadona</strong>
                                <small style="display:block; margin-top:4px; font-size:0.85rem;">12,450 payloads SQL inyectados contra endpoint de catálogo. WAF absorbiendo tráfico.</small>
                            </div>
                            <span class="severity warn" style="padding:6px 12px; font-size:0.75rem; border-radius:8px; opacity:0.8;">CONTENIDO</span>
                        </div>
                    </div>
                </article>
            </section>
        </div>

        <!-- TAB: Tickets -->
        <div id="tab-tickets" class="soc-tab-panel" style="display:none; margin-top:28px;">
            <section class="executive-grid executive-grid-wide">
                <article class="panel panel-feature" style="grid-column:1/-1; border-radius:20px; background:linear-gradient(180deg, rgba(255,255,255,0.02) 0%, rgba(0,0,0,0) 100%);">
                    <div class="panel-heading" style="border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:16px;">
                        <div>
                            <span class="eyebrow" style="color:var(--primary);">Soporte al Cliente</span>
                            <h2 style="font-size:1.4rem;">Tickets Activos por Organización</h2>
                        </div>
                        <span class="context-chip" style="color:var(--text-muted);font-size:0.75rem; border-color:#f59e0b; color:#f59e0b; background:rgba(245,158,11,0.1);">[!] Entorno Demo: IDs No Securizados (IDOR)</span>
                    </div>
                    <div class="stack-list" style="padding-top:16px;">
                        <div class="stack-item ticket-row" style="display:grid;grid-template-columns:1fr auto auto;align-items:center;gap:20px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:12px; padding:16px; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='rgba(255,255,255,0.02)'">
                            <div>
                                <strong style="font-size:1.05rem; color:#fff;">Iberdrola S.A.</strong>
                                <small style="display:block; margin-top:4px; color:var(--text-soft); font-size:0.85rem;">Ticket #1023 — Intento de acceso a sistema SCADA desde origen anómalo</small>
                            </div>
                            <span class="severity bad" style="padding:6px 12px; border-radius:8px;">CRÍTICO</span>
                            <a href="/admin/ticket.php?id=1023" class="btn btn-secondary btn-sm" style="border-radius:8px;">Gestionar</a>
                        </div>
                        <div class="stack-item ticket-row" style="display:grid;grid-template-columns:1fr auto auto;align-items:center;gap:20px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:12px; padding:16px; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='rgba(255,255,255,0.02)'">
                            <div>
                                <strong style="font-size:1.05rem; color:#fff;">MAPFRE Seguros</strong>
                                <small style="display:block; margin-top:4px; color:var(--text-soft); font-size:0.85rem;">Ticket #1044 — Campaña de Phishing masivo en carteras de seguros y agentes</small>
                            </div>
                            <span class="severity warn" style="padding:6px 12px; border-radius:8px;">ALTO</span>
                            <a href="/admin/ticket.php?id=1044" class="btn btn-secondary btn-sm" style="border-radius:8px;">Gestionar</a>
                        </div>
                        <div class="stack-item ticket-row" style="display:grid;grid-template-columns:1fr auto auto;align-items:center;gap:20px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:12px; padding:16px; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='rgba(255,255,255,0.02)'">
                            <div>
                                <strong style="font-size:1.05rem; color:#fff;">Banco Sabadell</strong>
                                <small style="display:block; margin-top:4px; color:var(--text-soft); font-size:0.85rem;">Ticket #1055 — Beaconing C2 identificado en segmento de red de banca central</small>
                            </div>
                            <span class="severity bad" style="padding:6px 12px; border-radius:8px;">CRÍTICO</span>
                            <a href="/admin/ticket.php?id=1055" class="btn btn-secondary btn-sm" style="border-radius:8px;">Gestionar</a>
                        </div>
                        <div class="stack-item ticket-row" style="display:grid;grid-template-columns:1fr auto auto;align-items:center;gap:20px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:12px; padding:16px; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='rgba(255,255,255,0.02)'">
                            <div>
                                <strong style="font-size:1.05rem; color:#fff;">Mercadona S.A.</strong>
                                <small style="display:block; margin-top:4px; color:var(--text-soft); font-size:0.85rem;">Ticket #1061 — Mitigación de inyección SQL (SQLi) masiva en infraestructura e-commerce</small>
                            </div>
                            <span class="severity warn" style="padding:6px 12px; border-radius:8px;">ALTO</span>
                            <a href="/admin/ticket.php?id=1061" class="btn btn-secondary btn-sm" style="border-radius:8px;">Gestionar</a>
                        </div>
                        <div class="stack-item ticket-row" style="display:grid;grid-template-columns:1fr auto auto;align-items:center;gap:20px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:12px; padding:16px; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='rgba(255,255,255,0.02)'">
                            <div>
                                <strong style="font-size:1.05rem; color:#fff;">Repsol S.A.</strong>
                                <small style="display:block; margin-top:4px; color:var(--text-soft); font-size:0.85rem;">Ticket #1072 — Escalada: Acceso OT no autorizado en perímetro de refinería industrial</small>
                            </div>
                            <span class="severity bad" style="padding:6px 12px; border-radius:8px;">CRÍTICO</span>
                            <a href="/admin/ticket.php?id=1072" class="btn btn-secondary btn-sm" style="border-radius:8px;">Gestionar</a>
                        </div>
                    </div>
                </article>
            </section>
        </div>

        <!-- TAB: Grafana -->
        <div id="tab-grafana" class="soc-tab-panel" style="display:none;margin-top:20px;">
            <article class="panel panel-feature" style="padding:0;overflow:hidden;min-height:600px;">
                <div style="padding:18px 20px;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;gap:12px;">
                    <span class="eyebrow">Métricas en tiempo real</span>
                    <span style="margin-left:auto;font-size:0.8rem;color:rgba(148,163,184,0.7);">Grafana · admin/admin</span>
                    <a href="http://localhost:3000" target="_blank" rel="noreferrer" class="btn btn-secondary btn-sm">Abrir en pestaña ↗</a>
                </div>
                <iframe
                    src="http://localhost:3000/d/sofia-main/sofia-solutions?orgId=1&theme=dark&kiosk=1"
                    width="100%"
                    height="580"
                    frameborder="0"
                    style="display:block;background:#0d0d0d;"
                    title="Grafana Dashboard — Sofia Solutions"
                ></iframe>
                <div id="grafana-fallback" style="display:none;padding:60px 40px;text-align:center;color:rgba(148,163,184,0.7);">
                    <div style="font-size:3rem;margin-bottom:16px;">📊</div>
                    <p style="font-size:1rem;margin-bottom:8px;">Grafana no disponible en este momento.</p>
                    <p style="font-size:0.85rem;margin-bottom:20px;">Asegúrate de que el contenedor grafana está corriendo.</p>
                    <a href="http://localhost:3000" target="_blank" rel="noreferrer" class="btn btn-primary">Abrir Grafana ↗</a>
                </div>
            </article>
        </div>

    </section>
</main>

<script>
function switchTab(tabId) {
    // Hide all panels
    document.querySelectorAll('.soc-tab-panel').forEach(p => p.style.display = 'none');
    // Reset all tab buttons
    document.querySelectorAll('.soc-tab').forEach(b => {
        b.style.background = 'rgba(255,255,255,0.03)';
        b.style.borderColor = 'rgba(255,255,255,0.07)';
        b.style.color = 'rgba(148,163,184,0.8)';
    });
    // Show selected panel
    document.getElementById('tab-' + tabId).style.display = '';
    // Activate selected tab button
    const activeBtn = document.querySelector('[data-tab="' + tabId + '"]');
    if (activeBtn) {
        activeBtn.style.background = 'rgba(6,182,212,0.1)';
        activeBtn.style.borderColor = 'rgba(6,182,212,0.25)';
        activeBtn.style.color = '#22d3ee';
    }
    // Check if Grafana iframe loaded
    if (tabId === 'grafana') {
        const iframe = document.querySelector('iframe[title*="Grafana"]');
        const fallback = document.getElementById('grafana-fallback');
        if (iframe) {
            iframe.onerror = () => {
                iframe.style.display = 'none';
                fallback.style.display = '';
            };
        }
    }
}
</script>
