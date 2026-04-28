<main class="home-shell">
    <div class="page-backdrop"></div>

    <!-- ===== HEADER ===== -->
    <header class="site-header page-container">
        <a class="brand-lockup brand-lockup-header" href="/">
            <?php renderLogo('brand-mark brand-mark-header'); ?>
        </a>
        <div style="display:flex;align-items:center;gap:10px;">
            <?php renderTopNav('home'); ?>
            <button id="lang-toggle" onclick="toggleLang()" style="display:inline-flex;align-items:center;gap:6px;padding:9px 14px;border-radius:999px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);color:rgba(203,213,225,0.8);font-size:0.85rem;font-weight:600;cursor:pointer;transition:all 0.2s;font-family:inherit;" onmouseover="this.style.background='rgba(255,255,255,0.08)';this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.04)';this.style.color='rgba(203,213,225,0.8)'">
                <span style="font-size:1rem;">&#x1F310;</span>
                <span id="lang-label">EN</span>
            </button>
        </div>
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
            <div class="hero-panel-shell" style="background:rgba(15,23,42,0.8); border:1px solid rgba(255,255,255,0.08); backdrop-filter:blur(12px);">
                <div class="signal-row" style="gap:8px;">
                    <span class="signal-chip" style="background:rgba(255,255,255,0.05); border-color:rgba(255,255,255,0.1); color:rgba(203,213,225,0.9); font-size:0.75rem;">
                        <span style="width:6px;height:6px;border-radius:50%;background:#10b981;box-shadow:0 0 6px #10b981;display:inline-block;margin-right:5px;"></span>SOC en vivo
                    </span>
                    <span class="signal-chip signal-chip-soft" style="font-size:0.75rem; color:rgba(148,163,184,0.7);">Clientes enterprise</span>
                    <span class="signal-chip" style="background:rgba(255,255,255,0.03); border-color:rgba(255,255,255,0.08); color:rgba(148,163,184,0.7); font-size:0.75rem;">ISO 27001</span>
                </div>
                <h2 style="margin:18px 0 12px;font-size:1.35rem;letter-spacing:-0.03em;">Operación basada en evidencia y visibilidad real</h2>
                <p style="color:var(--text-soft);line-height:1.7;font-size:0.92rem;">
                    Combinamos correlación de eventos, servicios gestionados y hardening para convertir la seguridad en una capacidad operativa medible.
                </p>
                <div class="hero-timeline" style="margin-top:22px;gap:10px;">
                    <article style="border-left:3px solid rgba(255,255,255,0.15); padding:12px 16px; border-radius:0 10px 10px 0; background:rgba(255,255,255,0.02);">
                        <strong style="font-size:0.92rem; color:rgba(255,255,255,0.9);">01 · Ingesta</strong>
                        <small style="display:block; margin-top:4px; color:rgba(148,163,184,0.7); font-size:0.82rem;">Logs, endpoints, VPN, WAF, identidad y actividad cloud.</small>
                    </article>
                    <article style="border-left:3px solid rgba(255,255,255,0.15); padding:12px 16px; border-radius:0 10px 10px 0; background:rgba(255,255,255,0.02);">
                        <strong style="font-size:0.92rem; color:rgba(255,255,255,0.9);">02 · Correlación</strong>
                        <small style="display:block; margin-top:4px; color:rgba(148,163,184,0.7); font-size:0.82rem;">Contexto por activo, cliente, criticidad y vector de ataque.</small>
                    </article>
                    <article style="border-left:3px solid rgba(255,255,255,0.15); padding:12px 16px; border-radius:0 10px 10px 0; background:rgba(255,255,255,0.02);">
                        <strong style="font-size:0.92rem; color:rgba(255,255,255,0.9);">03 · Acción</strong>
                        <small style="display:block; margin-top:4px; color:rgba(148,163,184,0.7); font-size:0.82rem;">Escalado, ticketing, seguimiento y evidencia para defensa.</small>
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
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:rgba(255,255,255,0.22);"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="flex-shrink:0;opacity:0.4"><rect x="1" y="6.5" width="12" height="1.5" rx="0.75" fill="currentColor"/></svg> Respuesta a incidentes</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:rgba(255,255,255,0.22);"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="flex-shrink:0;opacity:0.4"><rect x="1" y="6.5" width="12" height="1.5" rx="0.75" fill="currentColor"/></svg> Pentesting incluido</li>
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
                    <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem;color:rgba(255,255,255,0.22);"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="flex-shrink:0;opacity:0.4"><rect x="1" y="6.5" width="12" height="1.5" rx="0.75" fill="currentColor"/></svg> Pentesting incluido</li>
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
                <small style="display:block; color:rgba(255,255,255,0.75); font-size:0.72rem;">Ventas & Consultas · En línea</small>
            </div>
            <div style="margin-left:auto;width:8px;height:8px;border-radius:50%;background:#22c55e;box-shadow:0 0 6px #22c55e;"></div>
        </div>

        <!-- Mensajes -->
        <div id="chat-messages" style="flex:1; overflow-y:auto; padding:14px; display:flex; flex-direction:column; gap:10px; font-size:0.85rem;">
            <div style="background:rgba(6,182,212,0.1); border:1px solid rgba(6,182,212,0.2); border-radius:12px 12px 12px 2px; padding:10px 12px; max-width:85%; color:var(--text);">
                👋 ¡Hola! ¿En qué puedo ayudarte a mejorar la seguridad de tu empresa hoy?
            </div>
            <div class="chat-suggestion" onclick="sendChatMessage('¿Qué planes de SOC tenéis?')" style="background:rgba(255,255,255,0.04); border:1px solid var(--border); border-radius:8px; padding:8px 10px; cursor:pointer; color:var(--text-muted); font-size:0.8rem; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--text)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">📋 ¿Qué planes de SOC tenéis?</div>
            <div class="chat-suggestion" onclick="sendChatMessage('Quiero saber más de pentesting')" style="background:rgba(255,255,255,0.04); border:1px solid var(--border); border-radius:8px; padding:8px 10px; cursor:pointer; color:var(--text-muted); font-size:0.8rem; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--text)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">🔍 Quiero saber más de pentesting</div>
        </div>

        <!-- Input -->
        <div style="padding:10px; border-top:1px solid var(--border); display:flex; gap:8px; background:rgba(0,0,0,0.2);">
            <input id="chat-input" type="text" placeholder="Escribe tu consulta..." onkeydown="if(event.key==='Enter')sendChatMessage()" style="flex:1; background:rgba(255,255,255,0.05); border:1px solid var(--border); border-radius:8px; padding:8px 12px; color:var(--text); font-size:0.85rem; outline:none;">
            <button onclick="sendChatMessage()" style="background:var(--primary,#06b6d4); border:none; border-radius:8px; width:36px; display:flex; align-items:center; justify-content:center; color:#fff; cursor:pointer; font-size:1rem; transition:opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">➤</button>
        </div>
    </div>
</div>

<script>
// ---- Chat de Asistencia Sofia Solutions (Home) ----
const chatResponses = {
    "hola": "¡Hola! Soy el asistente virtual de Sofia Solutions. ¿En qué puedo ayudarte a fortalecer la seguridad de tu empresa hoy?",
    "buenos dias": "¡Buenos días! Es un placer saludarte. ¿Cómo podemos ayudarte con tus necesidades de ciberseguridad?",
    "buenas tardes": "¡Buenas tardes! Estamos a tu disposición para cualquier consulta técnica o comercial.",
    "numero": "Puedes contactar directamente con nuestra oficina central en el **+34 900 831 294**. Si eres cliente, recuerda que tienes línea directa 24/7 con el SOC.",
    "telefono": "Nuestro teléfono de atención al cliente es el **+34 900 831 294**. ¿Deseas que un consultor te llame en un horario específico?",
    "contacto": "Estamos disponibles por email en **contacto@sofiasolutions.local** o por teléfono en el **+34 900 831 294**. ¿Qué vía prefieres?",
    "precio": "Nuestras tarifas comienzan en los **499€/mes** para el plan Individual. El plan Business (€1,500) es el más popular para PYMES, y el Elite (€4,200) para infraestructuras críticas. ¿Cuál encaja mejor con tu volumen de activos?",
    "plan": "Disponemos de tres niveles: Individual, Business y Business Max (Elite). Todos incluyen monitorización, pero varían en el SLA de respuesta y el número de activos. ¿Para cuántos servidores o endpoints necesitas cobertura?",
    "soc": "Nuestro SOC (Security Operations Center) opera 24/7 con analistas expertos en detección de intrusiones y respuesta ante incidentes. Utilizamos telemetría avanzada para proteger tu negocio sin interrupciones.",
    "pentesting": "Realizamos auditorías ofensivas (Pentesting) tanto web como de infraestructura. El objetivo es encontrar tus vulnerabilidades antes de que lo haga un atacante. ¿Te gustaría recibir un presupuesto?",
    "incidentes": "En caso de un incidente activo, por favor utiliza el botón de 'Soporte Urgente' si ya eres cliente, o llama inmediatamente al +34 900 831 294.",
    "donde": "Nuestra sede principal de operaciones SOC se encuentra en España, pero damos cobertura a infraestructuras en toda la Unión Europea.",
    "servicios": "Ofrecemos Monitorización SOC 24/7, Pentesting ofensivo, Respuesta ante Incidentes (IR Retainer) y Cloud Hardening. ¿Sobre cuál de ellos necesitas más detalle?",
    "gracias": "¡A ti! Ha sido un placer. Quedo a tu disposición para lo que necesites.",
    "default": "Entiendo. Un consultor senior de ciberseguridad revisará tu consulta y se pondrá en contacto contigo en breve. Si necesitas una respuesta inmediata sobre nuestros planes o servicios, te recomiendo llamarnos al +34 900 831 294."
};

function sendChatMessage(textOverride) {
    const input = document.getElementById('chat-input');
    const msg = textOverride || input.value.trim();
    if (!msg) return;
    
    const container = document.getElementById('chat-messages');
    
    // Eliminar sugerencias si las hay
    const suggestions = container.querySelectorAll('.chat-suggestion');
    suggestions.forEach(s => s.remove());
    
    // Mensaje del usuario
    const userBubble = document.createElement('div');
    userBubble.style.cssText = 'background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); border-radius:12px 12px 2px 12px; padding:10px 12px; max-width:85%; color:#fff; align-self:flex-end; margin-left:auto; font-size:0.85rem; line-height:1.4;';
    userBubble.textContent = msg;
    container.appendChild(userBubble);
    input.value = '';
    
    // Scroll
    container.scrollTop = container.scrollHeight;
    
    // Simular escritura del "humano"
    setTimeout(() => {
        let reply = chatResponses["default"];
        const lowerMsg = msg.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        
        // Búsqueda inteligente de palabras clave
        for (const key in chatResponses) {
            if (lowerMsg.includes(key)) {
                reply = chatResponses[key];
                break;
            }
        }
        
        const botBubble = document.createElement('div');
        botBubble.style.cssText = 'background:rgba(6,182,212,0.1); border:1px solid rgba(6,182,212,0.2); border-radius:12px 12px 12px 2px; padding:10px 12px; max-width:85%; color:var(--text); font-size:0.85rem; line-height:1.4;';
        botBubble.innerHTML = reply;
        container.appendChild(botBubble);
        container.scrollTop = container.scrollHeight;
    }, 1000);
}
</script>

<script>
// ---- Language Toggle ES / EN ----
var currentLang = 'es';
var translations = {
  hero_title_es: 'Tu Seguridad,<br>Nuestra Misión.',
  hero_title_en: 'Your Security,<br>Our Mission.',
  hero_body_es: 'Sofia Solutions integra monitorización SOC avanzada, pentesting ofensivo y respuesta inmediata ante incidentes.\n                Protegemos lo que más importa con precisión, inteligencia activa y visibilidad continua.',
  hero_body_en: 'Sofia Solutions combines advanced SOC monitoring, offensive pentesting, and immediate incident response.\n                We protect what matters most with precision, active intelligence, and continuous visibility.',
  btn_access_es: 'Acceder a la plataforma',
  btn_access_en: 'Access the platform',
  btn_plans_es: 'Ver planes',
  btn_plans_en: 'View plans',
};

function toggleLang() {
  currentLang = currentLang === 'es' ? 'en' : 'es';
  document.getElementById('lang-label').textContent = currentLang === 'es' ? 'EN' : 'ES';

  var heroTitle = document.querySelector('.hero-copy h1');
  if (heroTitle) heroTitle.innerHTML = currentLang === 'es' ? translations.hero_title_es : translations.hero_title_en;

  var heroCopy = document.querySelector('.hero-copy .hero-body');
  if (heroCopy) heroCopy.textContent = currentLang === 'es'
    ? 'Sofia Solutions integra monitorización SOC avanzada, pentesting ofensivo y respuesta inmediata ante incidentes. Protegemos lo que más importa con precisión, inteligencia activa y visibilidad continua.'
    : 'Sofia Solutions combines advanced SOC monitoring, offensive pentesting, and immediate incident response. We protect what matters most with precision, active intelligence, and continuous visibility.';

  var btnAccess = document.querySelector('.hero-actions a:first-child');
  if (btnAccess) btnAccess.textContent = currentLang === 'es' ? translations.btn_access_es : translations.btn_access_en;

  var btnPlans = document.querySelector('.hero-actions a:last-child');
  if (btnPlans) btnPlans.textContent = currentLang === 'es' ? translations.btn_plans_es : translations.btn_plans_en;
}
</script>
