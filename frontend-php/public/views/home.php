<main class="home-shell">
    <div class="page-backdrop"></div>

    <!-- ===== HEADER ===== -->
    <header class="site-header page-container">
        <a class="brand-lockup brand-lockup-header" href="/">
            <?php renderLogo('brand-mark brand-mark-header'); ?>
        </a>
        <?php renderTopNav('home'); ?>
    </header>

    <!-- ===== HERO ===== -->
    <section class="hero-section page-container">
        <div class="hero-copy">
            <span class="eyebrow" style="color:rgba(148,163,184,0.75);">Cybersecurity &amp; Threat Intelligence</span>
            <h1 style="font-size:clamp(3.2rem,5.5vw,5.2rem);color:#f1f5f9;line-height:1.02;letter-spacing:-0.04em;margin:16px 0 20px;">
                Tu Seguridad,<br>Nuestra Misión.
            </h1>
            <p class="hero-body" style="font-size:1.05rem;max-width:52ch;line-height:1.75;color:rgba(148,163,184,0.85);">
                Sofia Solutions integra monitorización SOC avanzada, pentesting ofensivo y respuesta inmediata ante incidentes.
                Protegemos lo que más importa con precisión, inteligencia activa y visibilidad continua.
            </p>
            <div class="hero-actions" style="margin-top:28px;">
                <a href="/login" style="display:inline-flex;align-items:center;justify-content:center;min-height:48px;padding:0 28px;border-radius:11px;background:rgba(255,255,255,0.92);color:#0a0e18;font-weight:700;font-size:0.92rem;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.92)'">Acceder a la plataforma</a>
                <a class="btn btn-secondary" href="#planes" style="padding:0 24px;font-size:0.92rem;border-color:rgba(255,255,255,0.12);color:rgba(203,213,225,0.8);">Ver planes</a>
            </div>
            <div class="hero-meta-grid" style="margin-top:32px;">
                <article class="hero-meta-card">
                    <span class="meta-label">SLA crítico</span>
                    <strong>&lt; 15 min</strong>
                    <small>Respuesta y escalado coordinado ante incidentes.</small>
                </article>
                <article class="hero-meta-card">
                    <span class="meta-label">Cobertura activa</span>
                    <strong>24/7/365</strong>
                    <small>Visibilidad continua de perímetro, identidad y cloud.</small>
                </article>
                <article class="hero-meta-card">
                    <span class="meta-label">Activos protegidos</span>
                    <strong>184+</strong>
                    <small>Endpoints, correo, VPN, WAF y servicios críticos.</small>
                </article>
            </div>
        </div>

        <aside class="hero-panel">
            <div class="hero-panel-shell" style="background:radial-gradient(circle at top right,rgba(6,182,212,0.1),transparent 40%),rgba(8,12,26,0.95);border:1px solid rgba(6,182,212,0.15);">
                <div class="signal-row" style="gap:8px;">
                    <span class="signal-chip" style="background:rgba(16,185,129,0.1);border-color:rgba(16,185,129,0.25);color:#34d399;font-size:0.78rem;">
                        <span style="width:6px;height:6px;border-radius:50%;background:#10b981;box-shadow:0 0 6px #10b981;display:inline-block;margin-right:5px;"></span>SOC en vivo
                    </span>
                    <span class="signal-chip signal-chip-soft" style="font-size:0.78rem;">Clientes enterprise</span>
                    <span class="signal-chip" style="background:rgba(6,182,212,0.08);border-color:rgba(6,182,212,0.2);color:#22d3ee;font-size:0.78rem;">ISO 27001</span>
                </div>
                <h2 style="margin:18px 0 12px;font-size:1.35rem;letter-spacing:-0.03em;">Operación basada en evidencia y visibilidad real</h2>
                <p style="color:var(--text-soft);line-height:1.7;font-size:0.92rem;">
                    Combinamos correlación de eventos, servicios gestionados y hardening para convertir la seguridad en una capacidad operativa medible.
                </p>
                <div class="hero-timeline" style="margin-top:22px;gap:10px;">
                    <article style="border-left:3px solid rgba(6,182,212,0.5);padding:12px 16px;border-radius:0 10px 10px 0;background:rgba(6,182,212,0.04);">
                        <strong style="font-size:0.92rem;color:#22d3ee;">01 · Ingesta</strong>
                        <small style="display:block;margin-top:4px;color:var(--text-muted);font-size:0.82rem;">Logs, endpoints, VPN, WAF, identidad y actividad cloud.</small>
                    </article>
                    <article style="border-left:3px solid rgba(139,92,246,0.5);padding:12px 16px;border-radius:0 10px 10px 0;background:rgba(139,92,246,0.04);">
                        <strong style="font-size:0.92rem;color:#a78bfa;">02 · Correlación</strong>
                        <small style="display:block;margin-top:4px;color:var(--text-muted);font-size:0.82rem;">Contexto por activo, cliente, criticidad y vector de ataque.</small>
                    </article>
                    <article style="border-left:3px solid rgba(16,185,129,0.5);padding:12px 16px;border-radius:0 10px 10px 0;background:rgba(16,185,129,0.04);">
                        <strong style="font-size:0.92rem;color:#34d399;">03 · Acción</strong>
                        <small style="display:block;margin-top:4px;color:var(--text-muted);font-size:0.82rem;">Escalado, ticketing, seguimiento y evidencia para defensa.</small>
                    </article>
                </div>
            </div>
        </aside>
    </section>

    <!-- ===== CREDIBILITY ===== -->
    <section class="credibility-strip page-container" style="margin-top:32px;">
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

    <!-- ===== SERVICIOS ===== -->
    <section class="section-shell page-container">
        <div class="section-heading">
            <span class="eyebrow">Servicios principales</span>
            <h2>Cuatro líneas operativas con impacto directo en postura, detección y respuesta.</h2>
        </div>
        <div class="service-grid">
            <?php foreach ($serviceHighlights as $service): ?>
                <article class="service-card" style="border-radius:14px;padding:22px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:0;right:0;width:60px;height:60px;background:radial-gradient(circle,rgba(6,182,212,0.06),transparent);border-radius:0 14px 0 0;"></div>
                    <strong style="font-size:1rem;"><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                    <p style="margin-top:10px;"><?= htmlspecialchars($service['copy'], ENT_QUOTES, 'UTF-8') ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ===== PLANES ===== -->
    <section class="section-shell page-container planes-shell" id="planes">
        <div class="section-heading" style="text-align:center;margin:0 auto 36px;max-width:640px;">
            <span class="eyebrow">Planes de Servicio</span>
            <h2 style="margin-top:12px;">Niveles de protección adaptados a tus vectores de riesgo.</h2>
            <p style="color:var(--text-muted);font-size:0.92rem;margin-top:10px;">Sin permanencia mínima el primer año. Facturación mensual.</p>
        </div>

        <!-- Grid de 3 planes -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(270px,1fr));gap:22px;align-items:start;">

            <!-- Plan Individual -->
            <article style="position:relative;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.09);border-radius:20px;padding:32px 26px;display:flex;flex-direction:column;gap:16px;transition:border-color 0.25s,transform 0.25s;" onmouseover="this.style.borderColor='rgba(6,182,212,0.4)';this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.09)';this.style.transform='translateY(0)'">
                <div>
                    <span style="display:inline-block;padding:4px 10px;border-radius:6px;background:rgba(6,182,212,0.08);border:1px solid rgba(6,182,212,0.18);color:#22d3ee;font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;">Individual</span>
                </div>
                <div>
                    <div style="font-size:2.4rem;font-weight:800;letter-spacing:-0.05em;color:var(--text);">€499<span style="font-size:0.95rem;font-weight:400;color:var(--text-muted);letter-spacing:0;">/mes</span></div>
                    <p style="color:var(--text-muted);font-size:0.85rem;margin:8px 0 0;">Ideal para profesionales o pequeños equipos.</p>
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;flex:1;">
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;font-size:0.9rem;">✓</span> SOC básico 8/5</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;font-size:0.9rem;">✓</span> 1 endpoint monitorizado</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;font-size:0.9rem;">✓</span> Alertas por correo</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;font-size:0.9rem;">✓</span> SLA 24h respuesta</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:rgba(255,255,255,0.25);"><span style="font-size:0.9rem;">✗</span> Respuesta a incidentes</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:rgba(255,255,255,0.25);"><span style="font-size:0.9rem;">✗</span> Pentesting incluido</li>
                </ul>
                <a href="/login" class="btn btn-secondary btn-block" style="border-color:rgba(6,182,212,0.3);margin-top:8px;">Contratar plan</a>
            </article>

            <!-- Plan Business (destacado) -->
            <article style="position:relative;background:linear-gradient(160deg,rgba(6,182,212,0.08),rgba(14,116,144,0.05));border:1px solid rgba(6,182,212,0.35);border-radius:20px;padding:32px 26px;display:flex;flex-direction:column;gap:16px;box-shadow:0 0 40px rgba(6,182,212,0.08);transition:transform 0.25s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#06b6d4,#0e7490);color:#fff;font-size:0.68rem;font-weight:800;padding:5px 16px;border-radius:20px;letter-spacing:0.1em;text-transform:uppercase;white-space:nowrap;">Más Popular</div>
                <div>
                    <span style="display:inline-block;padding:4px 10px;border-radius:6px;background:rgba(6,182,212,0.15);border:1px solid rgba(6,182,212,0.3);color:#22d3ee;font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;">Business</span>
                </div>
                <div>
                    <div style="font-size:2.4rem;font-weight:800;letter-spacing:-0.05em;color:var(--text);">€1,500<span style="font-size:0.95rem;font-weight:400;color:var(--text-muted);letter-spacing:0;">/mes</span></div>
                    <p style="color:var(--text-muted);font-size:0.85rem;margin:8px 0 0;">Para empresas con exposición activa y equipo IT.</p>
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;flex:1;">
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> SOC 24/7 completo</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> Hasta 15 endpoints</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> Alertas en tiempo real</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> SLA 4h respuesta</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> IR Retainer básico</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:rgba(255,255,255,0.25);"><span>✗</span> Pentesting incluido</li>
                </ul>
                <a href="/login" class="btn btn-primary btn-block" style="margin-top:8px;">Contratar plan</a>
            </article>

            <!-- Plan Business Max -->
            <article style="position:relative;background:linear-gradient(160deg,rgba(168,85,247,0.08),rgba(109,40,217,0.04));border:1px solid rgba(168,85,247,0.35);border-radius:20px;padding:32px 26px;display:flex;flex-direction:column;gap:16px;transition:border-color 0.25s,transform 0.25s;" onmouseover="this.style.borderColor='#a855f7';this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='rgba(168,85,247,0.35)';this.style.transform='translateY(0)'">
                <div style="position:absolute;top:-13px;right:22px;background:linear-gradient(135deg,#a855f7,#7c3aed);color:#fff;font-size:0.68rem;font-weight:800;padding:5px 14px;border-radius:20px;letter-spacing:0.1em;text-transform:uppercase;">Elite</div>
                <div>
                    <span style="display:inline-block;padding:4px 10px;border-radius:6px;background:rgba(168,85,247,0.12);border:1px solid rgba(168,85,247,0.28);color:#c084fc;font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;">Business Max</span>
                </div>
                <div>
                    <div style="font-size:2.4rem;font-weight:800;letter-spacing:-0.05em;color:var(--text);">€4,200<span style="font-size:0.95rem;font-weight:400;color:var(--text-muted);letter-spacing:0;">/mes</span></div>
                    <p style="color:var(--text-muted);font-size:0.85rem;margin:8px 0 0;">Cobertura total para infraestructuras críticas.</p>
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;flex:1;">
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> SOC 24/7 dedicado</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> Endpoints ilimitados</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> SLA &lt; 15 min respuesta</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> IR Retainer full</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> Pentesting trimestral</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> Cloud Hardening incluido</li>
                </ul>
                <a href="/login" style="display:flex;align-items:center;justify-content:center;width:100%;background:linear-gradient(135deg,#a855f7,#7c3aed);border:none;border-radius:12px;padding:13px;color:#fff;font-weight:700;font-size:0.9rem;cursor:pointer;text-decoration:none;box-shadow:0 4px 18px rgba(168,85,247,0.28);transition:opacity 0.2s;margin-top:8px;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">Contratar plan</a>
            </article>
        </div>
    </section>

    <!-- ===== BENEFICIOS + OPS ===== -->
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

    <!-- ===== VALORACIONES ===== -->
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
                        <img src="<?= htmlspecialchars($review['avatar'], ENT_QUOTES, 'UTF-8') ?>" alt="Avatar CEO" class="review-avatar">
                        <div class="review-info">
                            <strong><?= htmlspecialchars($review['company'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <span><?= htmlspecialchars($review['service'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </div>
                    <div class="review-rating">
                        <span style="color:#f59e0b;letter-spacing:2px;">★★★★★</span>
                        <strong><?= htmlspecialchars($review['rating'], ENT_QUOTES, 'UTF-8') ?></strong>
                    </div>
                    <p><?= htmlspecialchars($review['quote'], ENT_QUOTES, 'UTF-8') ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ===== CTA / CONTACTO ===== -->
    <section class="cta-band page-container" style="background:linear-gradient(135deg,rgba(6,182,212,0.06),rgba(139,92,246,0.06));border:1px solid rgba(6,182,212,0.15);border-radius:20px;margin-bottom:48px;">
        <div>
            <span class="eyebrow">Contacto y Soporte</span>
            <h2 style="margin-top:10px;">¿Listo para proteger tu infraestructura? Hablemos.</h2>
        </div>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;color:var(--text-soft);font-size:0.95rem;">
                <span style="color:#22d3ee;">✉</span>
                <a href="mailto:contacto@sofiasolutions.local" style="color:var(--text-soft);text-decoration:none;" onmouseover="this.style.color='#22d3ee'" onmouseout="this.style.color='var(--text-soft)'">contacto@sofiasolutions.local</a>
            </div>
            <div style="display:flex;align-items:center;gap:10px;color:var(--text-soft);font-size:0.95rem;">
                <span style="color:#22d3ee;">☎</span>
                <span>+34 900 831 294</span>
            </div>
            <div style="display:flex;align-items:center;gap:10px;color:var(--text-soft);font-size:0.95rem;">
                <span style="color:#22d3ee;">🕐</span>
                <span>Atención 24/7 para incidentes críticos</span>
            </div>
        </div>
    </section>

</main>
