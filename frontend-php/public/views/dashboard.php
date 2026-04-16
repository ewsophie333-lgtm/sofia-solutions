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
                <a class="btn btn-secondary" href="/admin/security-monitor">Abrir SOC</a>
            </div>
        </section>

        <section id="dashboard-kpis" class="kpi-grid"></section>

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
