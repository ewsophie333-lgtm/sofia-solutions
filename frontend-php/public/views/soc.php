<?php
$activeNav = 'soc';
$headerEyebrow = 'SOC Security Monitor';
$headerTitle = 'Consola operativa de monitorización y defensa';
$headerCopy = 'Vista consolidada de incidentes, presión de ataque, orígenes, servicios cubiertos y estado de operación.';
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

        <section id="soc-status" class="status-strip"></section>
        <section id="soc-kpis" class="kpi-grid"></section>

        <section class="executive-grid executive-grid-wide">
            <article class="panel panel-feature" id="soc-vectors-section">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Attack surface</span>
                        <h2>Vectores y tendencias</h2>
                    </div>
                </div>
                <div id="soc-vectors" class="stack-list stack-list-spacious"></div>
            </article>
            <article class="panel" id="soc-incidents-section">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Live feed</span>
                        <h2>Incidentes en curso</h2>
                    </div>
                </div>
                <div id="soc-incidents" class="stack-list"></div>
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
                <div id="soc-countries" class="stack-list"></div>
            </article>
            <article class="panel" id="soc-services-section">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Coverage</span>
                        <h2>Servicios y capacidad protegida</h2>
                    </div>
                </div>
                <div id="soc-services" class="stack-list"></div>
            </article>
        </section>
    </section>
</main>
