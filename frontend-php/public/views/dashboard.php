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
    </section>
</main>
