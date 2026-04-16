<main class="home-shell">
    <div class="page-backdrop"></div>
    <header class="site-header page-container">
        <a class="brand-lockup brand-lockup-header" href="/">
            <?php renderLogo('brand-mark brand-mark-header'); ?>
        </a>
        <?php renderTopNav('home'); ?>
    </header>

    <section class="hero-section page-container">
        <div class="hero-copy">
            <span class="eyebrow">Managed security operations</span>
            <h1>Seguridad gestionada para organizaciones que no pueden operar a ciegas.</h1>
            <p class="hero-body">
                Sofia Solutions integra monitorización SOC, hardening, pentesting y respuesta ante incidentes
                en una única plataforma con trazabilidad, métricas y criterio operativo.
            </p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="/login">Acceder a la plataforma</a>
                <a class="btn btn-secondary" href="/admin/security-monitor">Ver monitor SOC</a>
            </div>
            <div class="hero-meta-grid">
                <article class="hero-meta-card">
                    <span class="meta-label">SLA crítico</span>
                    <strong>&lt; 15 min</strong>
                    <small>Escalado, análisis inicial y respuesta coordinada.</small>
                </article>
                <article class="hero-meta-card">
                    <span class="meta-label">Cobertura activa</span>
                    <strong>24/7/365</strong>
                    <small>Visibilidad continua sobre perímetro, identidad y cloud.</small>
                </article>
                <article class="hero-meta-card">
                    <span class="meta-label">Activos protegidos</span>
                    <strong>184</strong>
                    <small>Endpoints, correo, VPN, WAF y servicios críticos.</small>
                </article>
            </div>
        </div>

        <aside class="hero-panel">
            <div class="hero-panel-shell">
                <div class="signal-row">
                    <span class="signal-chip">SOC live</span>
                    <span class="signal-chip signal-chip-soft">Clientes enterprise</span>
                </div>
                <h2>Operación basada en evidencia y visibilidad real</h2>
                <p>
                    Combinamos correlación de eventos, servicios gestionados y hardening para convertir
                    la seguridad en una capacidad operativa medible.
                </p>
                <div class="hero-timeline">
                    <article>
                        <strong>Ingesta</strong>
                        <small>Logs, endpoints, VPN, WAF, identidad y actividad cloud.</small>
                    </article>
                    <article>
                        <strong>Correlación</strong>
                        <small>Contexto por activo, cliente, criticidad y vector de ataque.</small>
                    </article>
                    <article>
                        <strong>Acción</strong>
                        <small>Escalado, ticketing, seguimiento y evidencia para defensa.</small>
                    </article>
                </div>
            </div>
        </aside>
    </section>

    <section class="credibility-strip page-container">
        <div class="credibility-copy">
            <span class="eyebrow">Entornos que protegemos</span>
            <h2>Servicios diseñados para equipos que necesitan continuidad, auditoría y control.</h2>
        </div>
        <div class="credibility-badges">
            <?php foreach ($sectorBadges as $badge): ?>
                <span><?= htmlspecialchars($badge, ENT_QUOTES, 'UTF-8') ?></span>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section-shell page-container">
        <div class="section-heading">
            <span class="eyebrow">Servicios principales</span>
            <h2>Cuatro líneas operativas con impacto directo en postura, detección y respuesta.</h2>
        </div>
        <div class="service-grid">
            <?php foreach ($serviceHighlights as $service): ?>
                <article class="service-card">
                    <strong><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                    <p><?= htmlspecialchars($service['copy'], ENT_QUOTES, 'UTF-8') ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section-shell page-container section-split">
        <article class="split-panel">
            <div class="section-heading">
                <span class="eyebrow">Beneficios operativos</span>
                <h2>Un modelo pensado para equipos que necesitan control y capacidad de decisión.</h2>
            </div>
            <div class="benefit-list">
                <?php foreach ($operationalBenefits as $benefit): ?>
                    <article class="benefit-row">
                        <div>
                            <span class="meta-label"><?= htmlspecialchars($benefit['label'], ENT_QUOTES, 'UTF-8') ?></span>
                            <strong><?= htmlspecialchars($benefit['value'], ENT_QUOTES, 'UTF-8') ?></strong>
                        </div>
                        <p><?= htmlspecialchars($benefit['copy'], ENT_QUOTES, 'UTF-8') ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </article>

        <article class="split-panel split-panel-alt">
            <div class="section-heading">
                <span class="eyebrow">Arquitectura y operación</span>
                <h2>Un entorno desplegable, medible y defendible en un contexto realista.</h2>
            </div>
            <div class="ops-list">
                <article>
                    <strong>Frontend corporativo</strong>
                    <p>Interfaz unificada para acceso, panel ejecutivo y monitorización SOC.</p>
                </article>
                <article>
                    <strong>Backend y base de datos</strong>
                    <p>API desacoplada, PostgreSQL, entidades reales y lógica dual de seguridad.</p>
                </article>
                <article>
                    <strong>Observabilidad técnica</strong>
                    <p>Monitor SOC para negocio y Grafana como soporte técnico de operación.</p>
                </article>
            </div>
        </article>
    </section>

    <section class="section-shell page-container reviews-shell">
        <div class="section-heading section-heading-inline">
            <div>
                <span class="eyebrow">Valoraciones</span>
                <h2>Clientes que evalúan el servicio por continuidad, visibilidad y rapidez operativa.</h2>
            </div>
            <div class="rating-summary">
                <strong>4.9/5</strong>
                <small>Media consolidada de clientes con servicios gestionados</small>
            </div>
        </div>
        <div class="reviews-grid">
            <?php foreach ($customerReviews as $review): ?>
                <article class="review-card">
                    <div class="review-top">
                        <strong><?= htmlspecialchars($review['company'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <span><?= htmlspecialchars($review['service'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="review-rating">
                        <span>★★★★★</span>
                        <strong><?= htmlspecialchars($review['rating'], ENT_QUOTES, 'UTF-8') ?></strong>
                    </div>
                    <p><?= htmlspecialchars($review['quote'], ENT_QUOTES, 'UTF-8') ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="cta-band page-container">
        <div>
            <span class="eyebrow">Siguiente paso</span>
            <h2>Accede a la plataforma y revisa el panel ejecutivo o el monitor de seguridad.</h2>
        </div>
        <div class="hero-actions">
            <a class="btn btn-primary" href="/login">Entrar al workspace</a>
            <a class="btn btn-secondary" href="/dashboard">Ver dashboard</a>
        </div>
    </section>
</main>
