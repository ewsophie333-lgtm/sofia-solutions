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
        <header class="panel-header" style="border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:20px; margin-bottom:24px;">
            <div>
                <span class="eyebrow" style="color:var(--primary); font-weight:700; letter-spacing:1px;"><?= htmlspecialchars($headerEyebrow, ENT_QUOTES, 'UTF-8') ?></span>
                <h1 style="font-size:2.4rem; font-weight:800; letter-spacing:-0.03em; margin:8px 0;"><?= htmlspecialchars($headerTitle, ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="panel-header-copy" style="color:var(--text-soft);"><?= htmlspecialchars($headerCopy, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="header-links" style="display:flex; gap:12px;">
                <span style="display:inline-flex; align-items:center; gap:6px; background:rgba(34,197,94,0.1); color:#22c55e; border:1px solid rgba(34,197,94,0.2); padding:6px 12px; border-radius:999px; font-size:0.75rem; font-weight:700;"><span style="width:6px;height:6px;background:#22c55e;border-radius:50%;box-shadow:0 0 8px #22c55e;animation:pulse 2s infinite;"></span> Sistema Estable</span>
                <span class="context-chip context-chip-soft" style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1);">Última sincronización: Hace 2s</span>
            </div>
        </header>

        <!-- Enterprise KPIs -->
        <section style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:20px; margin-bottom:30px;">
            <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:16px; padding:24px; position:relative; overflow:hidden;">
                <div style="position:absolute; top:0; left:0; width:4px; height:100%; background:var(--primary);"></div>
                <span style="display:block; color:var(--text-soft); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Eventos Procesados (24h)</span>
                <div style="font-size:2.5rem; font-weight:800; color:#fff; font-family:monospace;">14,092</div>
                <span style="color:#22c55e; font-size:0.8rem; font-weight:600;">↑ 12% vs ayer</span>
            </div>
            <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:16px; padding:24px; position:relative; overflow:hidden;">
                <div style="position:absolute; top:0; left:0; width:4px; height:100%; background:#ef4444;"></div>
                <span style="display:block; color:var(--text-soft); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Ataques Bloqueados</span>
                <div style="font-size:2.5rem; font-weight:800; color:#fff; font-family:monospace;">38</div>
                <span style="color:#ef4444; font-size:0.8rem; font-weight:600;">Requieren atención</span>
            </div>
            <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:16px; padding:24px; position:relative; overflow:hidden;">
                <div style="position:absolute; top:0; left:0; width:4px; height:100%; background:#a855f7;"></div>
                <span style="display:block; color:var(--text-soft); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Tiempo de Respuesta (SLA)</span>
                <div style="font-size:2.5rem; font-weight:800; color:#fff; font-family:monospace;">4.2<span style="font-size:1.2rem; color:var(--text-muted);">m</span></div>
                <span style="color:#22c55e; font-size:0.8rem; font-weight:600;">Dentro del margen óptimo</span>
            </div>
        </section>

        <!-- Main Dashboard Grid -->
        <section style="display:grid; grid-template-columns: 2fr 1fr; gap:30px; margin-bottom:40px;">
            <!-- Left Column -->
            <div style="display:flex; flex-direction:column; gap:30px;">
                <article class="panel panel-feature" style="border-radius:20px; background:linear-gradient(180deg, rgba(255,255,255,0.02) 0%, rgba(0,0,0,0) 100%);">
                    <div class="panel-heading" style="border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:16px;">
                        <div>
                            <span class="eyebrow">Cobertura Activa</span>
                            <h2 style="font-size:1.4rem;">Servicios Contratados</h2>
                        </div>
                    </div>
                    <div id="dashboard-services" class="stack-list stack-list-spacious" style="padding-top:16px;"></div>
                </article>
            </div>

            <!-- Right Column -->
            <div style="display:flex; flex-direction:column; gap:30px;">
                <article class="panel panel-feature" style="border-radius:20px; background:linear-gradient(180deg, rgba(255,255,255,0.02) 0%, rgba(0,0,0,0) 100%);">
                    <div class="panel-heading" style="border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:16px;">
                        <div>
                            <span class="eyebrow">Soporte Continuo</span>
                            <h2 style="font-size:1.4rem;">Tickets y Continuidad</h2>
                        </div>
                    </div>
                    <div id="dashboard-tickets" class="stack-list" style="padding-top:16px;"></div>
                    <div style="margin-top:20px;">
                        <button onclick="document.getElementById('chat-toggle').click();" class="btn btn-outline" style="width:100%; border-radius:12px; font-weight:600; font-size:0.85rem;">Abrir Nuevo Ticket / Chat</button>
                    </div>
                </article>

                <article class="panel panel-feature" style="border-radius:20px; background:linear-gradient(180deg, rgba(255,255,255,0.02) 0%, rgba(0,0,0,0) 100%);">
                    <div class="panel-heading" style="border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:16px;">
                        <div>
                            <span class="eyebrow">Monitorización L7</span>
                            <h2 style="font-size:1.4rem;">Actividad en Vivo</h2>
                        </div>
                    </div>
                    <div style="background:#020617; border:1px solid rgba(255,255,255,0.05); border-radius:12px; padding:16px; margin-top:16px;">
                        <div id="live-event-feed" class="live-feed" style="font-family:monospace; font-size:0.8rem; color:#94a3b8; height:180px; overflow-y:hidden;">
                            Esperando telemetría...
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; border-top:1px solid rgba(255,255,255,0.05); padding-top:12px;">
                            <span id="live-last-update" style="font-size:0.7rem; color:#64748b;">Conectando al feed...</span>
                            <div style="display:flex; gap:6px;">
                                <div style="width:8px; height:8px; border-radius:50%; background:#22c55e;"></div>
                                <div style="width:8px; height:8px; border-radius:50%; background:#eab308;"></div>
                                <div style="width:8px; height:8px; border-radius:50%; background:#ef4444;"></div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <!-- Billing Section -->
        <section class="executive-grid executive-grid-wide" style="margin-top:20px;">
            <article class="panel panel-feature" style="border-radius:20px; border:1px solid rgba(255,255,255,0.08); background:linear-gradient(180deg, rgba(255,255,255,0.02) 0%, rgba(0,0,0,0) 100%);">
                <div class="panel-heading" style="border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:16px; margin-bottom:24px;">
                    <div>
                        <span class="eyebrow" style="color:var(--primary);">Gestión de Cuenta</span>
                        <h2 style="font-size:1.4rem;">Facturación y Planes de Cobertura</h2>
                    </div>
                    <span class="context-chip" style="color:var(--text-muted);font-size:0.74rem;">Módulo de Pagos</span>
                </div>
                <!-- Planes con estética idéntica a la home -->
                <div id="planes-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:22px;padding:4px 0 12px;align-items:stretch;max-width:960px;margin:0 auto;width:100%;">

                    <!-- Plan Individual -->
                    <article id="plan-individual" data-plan="individual" style="position:relative;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.09);border-radius:20px;padding:28px 24px;display:flex;flex-direction:column;gap:16px;transition:border-color 0.25s,transform 0.25s;" onmouseover="if(!this.classList.contains('plan-active'))this.style.borderColor='rgba(6,182,212,0.4)';this.style.transform='translateY(-5px)'" onmouseout="if(!this.classList.contains('plan-active'))this.style.borderColor='rgba(255,255,255,0.09)';this.style.transform='translateY(0)'">
                        <div>
                            <span style="display:inline-block;padding:4px 10px;border-radius:6px;background:rgba(6,182,212,0.08);border:1px solid rgba(6,182,212,0.18);color:#22d3ee;font-size:0.68rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;">Individual</span>
                        </div>
                        <div>
                            <div style="font-size:2.2rem;font-weight:800;letter-spacing:-0.05em;color:var(--text);">€499<span style="font-size:0.9rem;font-weight:400;color:var(--text-muted);letter-spacing:0;">/mes</span></div>
                            <p style="color:var(--text-muted);font-size:0.82rem;margin:6px 0 0;">Ideal para profesionales o pequeños equipos.</p>
                        </div>
                        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:9px;flex:1;">
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> SOC básico 8/5</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> 1 endpoint monitorizado</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> Alertas por correo</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> SLA 24h respuesta</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:rgba(255,255,255,0.2);"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="flex-shrink:0;opacity:0.4"><rect x="1" y="6.5" width="12" height="1.5" rx="0.75" fill="currentColor"/></svg> Respuesta a incidentes</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:rgba(255,255,255,0.2);"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="flex-shrink:0;opacity:0.4"><rect x="1" y="6.5" width="12" height="1.5" rx="0.75" fill="currentColor"/></svg> Pentesting incluido</li>
                        </ul>
                        <form action="/pago_inseguro.php" method="GET" style="margin-top:6px;display:flex;flex-direction:column;gap:8px;">
                            <input type="hidden" name="plan" value="individual">
                            <label style="font-size:0.78rem;color:var(--text-muted);">Nº Tarjeta
                                <input type="text" name="cc_number" placeholder="0000 0000 0000 0000" style="display:block;width:100%;margin-top:5px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:9px;padding:9px 12px;color:var(--text);font-size:0.84rem;outline:none;box-sizing:border-box;">
                            </label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                                <input type="text" name="cc_exp" placeholder="MM/YY" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:9px;padding:9px 12px;color:var(--text);font-size:0.82rem;outline:none;">
                                <input type="text" name="cc_cvv" placeholder="CVV" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:9px;padding:9px 12px;color:var(--text);font-size:0.82rem;outline:none;">
                            </div>
                            <button type="submit" style="width:100%;background:rgba(6,182,212,0.12);border:1px solid rgba(6,182,212,0.3);border-radius:12px;padding:11px;color:#22d3ee;font-weight:700;font-size:0.88rem;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='rgba(6,182,212,0.25)';" onmouseout="this.style.background='rgba(6,182,212,0.12)';">Contratar — €499/mes</button>
                        </form>
                    </article>

                    <!-- Plan Business (Destacado) -->
                    <article id="plan-business" data-plan="business" style="position:relative;background:linear-gradient(160deg,rgba(6,182,212,0.08),rgba(14,116,144,0.05));border:1px solid rgba(6,182,212,0.35);border-radius:20px;padding:28px 24px;display:flex;flex-direction:column;gap:16px;box-shadow:0 0 40px rgba(6,182,212,0.07);transition:transform 0.25s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div style="position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#06b6d4,#0e7490);color:#fff;font-size:0.67rem;font-weight:800;padding:5px 16px;border-radius:20px;letter-spacing:0.1em;text-transform:uppercase;white-space:nowrap;">Más Popular</div>
                        <div>
                            <span style="display:inline-block;padding:4px 10px;border-radius:6px;background:rgba(6,182,212,0.15);border:1px solid rgba(6,182,212,0.3);color:#22d3ee;font-size:0.68rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;">Business</span>
                        </div>
                        <div>
                            <div style="font-size:2.2rem;font-weight:800;letter-spacing:-0.05em;color:var(--text);">€1,500<span style="font-size:0.9rem;font-weight:400;color:var(--text-muted);letter-spacing:0;">/mes</span></div>
                            <p style="color:var(--text-muted);font-size:0.82rem;margin:6px 0 0;">Para empresas con exposición activa y equipo IT.</p>
                        </div>
                        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:9px;flex:1;">
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> SOC 24/7 completo</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> Hasta 15 endpoints</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> Alertas en tiempo real</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> SLA 4h respuesta</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> IR Retainer básico</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:rgba(255,255,255,0.2);"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="flex-shrink:0;opacity:0.4"><rect x="1" y="6.5" width="12" height="1.5" rx="0.75" fill="currentColor"/></svg> Pentesting incluido</li>
                        </ul>
                        <form action="/pago_inseguro.php" method="GET" style="margin-top:6px;display:flex;flex-direction:column;gap:8px;">
                            <input type="hidden" name="plan" value="business">
                            <label style="font-size:0.78rem;color:var(--text-muted);">Nº Tarjeta
                                <input type="text" name="cc_number" placeholder="0000 0000 0000 0000" style="display:block;width:100%;margin-top:5px;background:rgba(255,255,255,0.04);border:1px solid rgba(6,182,212,0.22);border-radius:9px;padding:9px 12px;color:var(--text);font-size:0.84rem;outline:none;box-sizing:border-box;">
                            </label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                                <input type="text" name="cc_exp" placeholder="MM/YY" style="background:rgba(255,255,255,0.04);border:1px solid rgba(6,182,212,0.22);border-radius:9px;padding:9px 12px;color:var(--text);font-size:0.82rem;outline:none;">
                                <input type="text" name="cc_cvv" placeholder="CVV" style="background:rgba(255,255,255,0.04);border:1px solid rgba(6,182,212,0.22);border-radius:9px;padding:9px 12px;color:var(--text);font-size:0.82rem;outline:none;">
                            </div>
                            <button type="submit" style="width:100%;background:linear-gradient(135deg,#06b6d4,#0e7490);border:none;border-radius:12px;padding:11px;color:#fff;font-weight:700;font-size:0.9rem;cursor:pointer;transition:opacity 0.2s;box-shadow:0 4px 16px rgba(6,182,212,0.25);" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">Contratar — €1,500/mes</button>
                        </form>
                    </article>

                    <!-- Plan Business Max -->
                    <article id="plan-business-max" data-plan="business-max" style="position:relative;background:linear-gradient(160deg,rgba(168,85,247,0.08),rgba(109,40,217,0.04));border:1px solid rgba(168,85,247,0.35);border-radius:20px;padding:28px 24px;display:flex;flex-direction:column;gap:16px;transition:border-color 0.25s,transform 0.25s;" onmouseover="this.style.borderColor='#a855f7';this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='rgba(168,85,247,0.35)';this.style.transform='translateY(0)'">
                        <div style="position:absolute;top:-13px;right:22px;background:linear-gradient(135deg,#a855f7,#7c3aed);color:#fff;font-size:0.67rem;font-weight:800;padding:5px 14px;border-radius:20px;letter-spacing:0.1em;text-transform:uppercase;">Elite</div>
                        <div>
                            <span style="display:inline-block;padding:4px 10px;border-radius:6px;background:rgba(168,85,247,0.12);border:1px solid rgba(168,85,247,0.28);color:#c084fc;font-size:0.68rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;">Business Max</span>
                        </div>
                        <div>
                            <div style="font-size:2.2rem;font-weight:800;letter-spacing:-0.05em;color:var(--text);">€4,200<span style="font-size:0.9rem;font-weight:400;color:var(--text-muted);letter-spacing:0;">/mes</span></div>
                            <p style="color:var(--text-muted);font-size:0.82rem;margin:6px 0 0;">Cobertura total para infraestructuras críticas.</p>
                        </div>
                        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:9px;flex:1;">
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> SOC 24/7 dedicado</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> Endpoints ilimitados</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> SLA &lt; 15 min respuesta</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> IR Retainer full</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> Pentesting trimestral</li>
                            <li style="display:flex;align-items:center;gap:9px;font-size:0.85rem;color:var(--text-soft);"><span style="color:#10b981;">✓</span> Cloud Hardening incluido</li>
                        </ul>
                        <form action="/pago_inseguro.php" method="GET" style="margin-top:6px;display:flex;flex-direction:column;gap:8px;">
                            <input type="hidden" name="plan" value="business-max">
                            <label style="font-size:0.78rem;color:var(--text-muted);">Nº Tarjeta
                                <input type="text" name="cc_number" placeholder="0000 0000 0000 0000" style="display:block;width:100%;margin-top:5px;background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.22);border-radius:9px;padding:9px 12px;color:var(--text);font-size:0.84rem;outline:none;box-sizing:border-box;">
                            </label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                                <input type="text" name="cc_exp" placeholder="MM/YY" style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.22);border-radius:9px;padding:9px 12px;color:var(--text);font-size:0.82rem;outline:none;">
                                <input type="text" name="cc_cvv" placeholder="CVV" style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.22);border-radius:9px;padding:9px 12px;color:var(--text);font-size:0.82rem;outline:none;">
                            </div>
                            <button type="submit" style="width:100%;background:linear-gradient(135deg,#a855f7,#7c3aed);border:none;border-radius:12px;padding:11px;color:#fff;font-weight:700;font-size:0.9rem;cursor:pointer;transition:opacity 0.2s;box-shadow:0 4px 16px rgba(168,85,247,0.25);" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">Contratar — €4,200/mes</button>
                        </form>
                    </article>

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

<!-- ====== Ticket Detail Popup Modal ====== -->
<div id="ticket-modal" style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
    <div style="background:#0f1826;border:1px solid rgba(6,182,212,0.25);border-radius:20px;padding:32px;max-width:520px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.6);position:relative;">
        <button onclick="closeTicketModal()" style="position:absolute;top:14px;right:16px;background:rgba(255,255,255,0.05);border:none;border-radius:8px;padding:6px 10px;color:rgba(148,163,184,0.8);cursor:pointer;font-size:1rem;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">✕</button>
        <div id="ticket-modal-content"></div>
    </div>
</div>

<style>
.plan-active {
    border-color: rgba(34,211,238,0.7) !important;
    box-shadow: 0 0 30px rgba(6,182,212,0.2) !important;
}
.plan-active-badge {
    position:absolute; top:-13px; left:50%; transform:translateX(-50%);
    background:linear-gradient(135deg,#22d3ee,#06b6d4);
    color:#fff; font-size:0.65rem; font-weight:800; padding:5px 16px;
    border-radius:20px; letter-spacing:0.12em; text-transform:uppercase;
    white-space:nowrap; z-index:2;
}
#ticket-modal.open { display:flex; }
</style>

<script>
// ---- Plan Activo: detectar qué plan corresponde al cliente ----
(function() {
    const api = window.SOFIA_CONFIG?.apiBase || 'http://localhost:8001';
    const token = localStorage.getItem('sofia_token_v1');
    if (!token) return;
    fetch(api + '/api/client/overview', {
        credentials: 'include',
        headers: { Authorization: 'Bearer ' + token }
    })
    .then(r => r.ok ? r.json() : null)
    .then(data => {
        if (!data) return;
        // Detectar el servicio principal del cliente
        const primaryService = data.customer?.primaryService?.name
            || (data.services && data.services[0]?.name)
            || '';
        let planKey = 'business'; // fallback
        const name = primaryService.toLowerCase();
        if (name.includes('individual') || name.includes('499')) planKey = 'individual';
        else if (name.includes('max') || name.includes('elite') || name.includes('4200')) planKey = 'business-max';
        else if (name.includes('business') || name.includes('1500')) planKey = 'business';
        else if (name.includes('soc') || name.includes('24')) planKey = 'business';
        const activeCard = document.querySelector('[data-plan="' + planKey + '"]');
        if (activeCard) {
            activeCard.classList.add('plan-active');
            // Añadir badge "Plan Actual"
            const badge = document.createElement('div');
            badge.className = 'plan-active-badge';
            badge.textContent = '✓ Plan Actual';
            activeCard.style.position = 'relative';
            activeCard.insertBefore(badge, activeCard.firstChild);
            // Deshabilitar formulario
            const btn = activeCard.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Plan en curso';
                btn.style.opacity = '0.5';
                btn.style.cursor = 'not-allowed';
            }
        }
    })
    .catch(() => {
        // Si no hay endpoint client/overview, marcar "Business" por defecto
        const fallback = document.querySelector('[data-plan="business"]');
        if (fallback) {
            fallback.classList.add('plan-active');
            const badge = document.createElement('div');
            badge.className = 'plan-active-badge';
            badge.textContent = '✓ Plan Actual';
            fallback.style.position = 'relative';
            fallback.insertBefore(badge, fallback.firstChild);
        }
    });
})();

// ---- Popup de detalle de Ticket ----
function openTicketModal(ticketId, subject, status, priority, message) {
    const statusColor = { OPEN:'#ef4444', PENDING:'#f59e0b', CLOSED:'#22c55e' };
    const priorityColor = { HIGH:'#ef4444', MEDIUM:'#f59e0b', LOW:'#22c55e' };
    document.getElementById('ticket-modal-content').innerHTML = `
        <div style="margin-bottom:20px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.1em;color:rgba(148,163,184,0.7);text-transform:uppercase;">Ticket de Soporte</span>
            <h2 style="margin:8px 0 4px;font-size:1.15rem;color:#f0f9ff;">${subject}</h2>
            <div style="display:flex;gap:10px;margin-top:10px;flex-wrap:wrap;">
                <span style="padding:4px 12px;border-radius:20px;font-size:0.72rem;font-weight:700;background:${statusColor[status]}22;border:1px solid ${statusColor[status]}55;color:${statusColor[status]};">${status}</span>
                <span style="padding:4px 12px;border-radius:20px;font-size:0.72rem;font-weight:700;background:${priorityColor[priority]}22;border:1px solid ${priorityColor[priority]}55;color:${priorityColor[priority]};">Prioridad ${priority}</span>
                <span style="padding:4px 12px;border-radius:20px;font-size:0.72rem;font-weight:700;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(148,163,184,0.8);">#${ticketId}</span>
            </div>
        </div>
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:16px;margin-bottom:20px;">
            <span style="font-size:0.75rem;font-weight:600;color:rgba(148,163,184,0.7);display:block;margin-bottom:8px;">Último mensaje del analista:</span>
            <p style="margin:0;font-size:0.88rem;color:#cbd5e1;line-height:1.6;">${message || 'Nuestro equipo está revisando tu ticket. Te responderemos en el menor tiempo posible.'}</p>
        </div>
        <div style="display:flex;gap:10px;">
            <button onclick="closeTicketModal()" style="flex:1;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:11px;color:#cbd5e1;cursor:pointer;font-family:inherit;font-size:0.88rem;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.09)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">Cerrar</button>
            <button style="flex:1;background:linear-gradient(135deg,#06b6d4,#0e7490);border:none;border-radius:10px;padding:11px;color:#fff;cursor:pointer;font-family:inherit;font-weight:600;font-size:0.88rem;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'" onclick="closeTicketModal()">Responder vía chat</button>
        </div>
    `;
    const modal = document.getElementById('ticket-modal');
    modal.classList.add('open');
    modal.style.display = 'flex';
}
function closeTicketModal() {
    const modal = document.getElementById('ticket-modal');
    modal.classList.remove('open');
    modal.style.display = 'none';
}
document.getElementById('ticket-modal').addEventListener('click', function(e) {
    if (e.target === this) closeTicketModal();
});
</script>

<script>
// ---- Chat de Asistencia Sofia Solutions ----
const chatResponses = {
    "hola": "¡Hola! ¿Cómo va el día? Estoy aquí para ayudarte con cualquier duda técnica sobre el panel o tus servicios.",
    "buenos dias": "¡Buenos días! Espero que todo esté funcionando correctamente. ¿Necesitas ayuda con algún reporte?",
    "buenas tardes": "¡Buenas tardes! ¿En qué puedo apoyarte hoy con la monitorización de tus activos?",
    "servicios": "Tus servicios activos están listados en el panel **'Postura de servicio y cobertura'**. Puedes ver el detalle de cada uno haciendo clic en su nombre.",
    "tickets": "Puedes ver el estado de tus tickets en la sección **'Tickets y continuidad operativa'**. Al hacer clic en un ticket, se abrirá el detalle completo.",
    "soporte": "Si necesitas soporte técnico directo, puedes abrir un ticket o llamarnos al **+34 900 831 294** indicando tu ID de cliente.",
    "urgente": "🚨 He notificado a los analistas de guardia sobre tu solicitud. Recibirás respuesta en el dashboard en menos de 15 minutos.",
    "pago": "La gestión de facturación y cambios de plan se realiza desde el selector de planes en este mismo dashboard.",
    "grafana": "El panel de Grafana muestra métricas técnicas avanzadas. Si no ves datos, asegúrate de que tus agentes estén reportando correctamente.",
    "gracias": "¡De nada! Aquí sigo para lo que necesites.",
    "default": "Recibido. He trasladado tu consulta al equipo de soporte. Si es algo crítico que requiera atención inmediata, por favor usa la línea telefónica prioritaria."
};

function sendChatMessage(text) {
    const input = document.getElementById('chat-input');
    const message = text || input.value.trim();
    if (!message) return;

    const msgs = document.getElementById('chat-messages');

    // Mensaje del usuario
    const userMsg = document.createElement('div');
    userMsg.style.cssText = 'background:rgba(6,182,212,0.15);border:1px solid rgba(6,182,212,0.3);border-radius:12px 12px 2px 12px;padding:10px 12px;max-width:85%;align-self:flex-end;color:var(--text);font-size:0.85rem;line-height:1.4;';
    userMsg.textContent = message;
    msgs.appendChild(userMsg);

    // Respuesta bot (simulando humano)
    setTimeout(() => {
        const lowerMsg = message.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        let reply = chatResponses["default"];
        
        for (const key in chatResponses) {
            if (lowerMsg.includes(key)) {
                reply = chatResponses[key];
                break;
            }
        }

        const botMsg = document.createElement('div');
        botMsg.style.cssText = 'background:rgba(6,182,212,0.08);border:1px solid rgba(6,182,212,0.15);border-radius:12px 12px 12px 2px;padding:10px 12px;max-width:85%;color:var(--text);font-size:0.85rem;line-height:1.4;';
        botMsg.innerHTML = '🛡️ ' + reply;
        msgs.appendChild(botMsg);
        msgs.scrollTop = msgs.scrollHeight;
    }, 900);

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
