<main class="auth-shell">

    <!-- Panel izquierdo de marca -->
    <section class="auth-panel auth-panel-brand">
        <div class="auth-brand-frame">
            <?php renderLogo('brand-mark brand-mark-login'); ?>
            <span class="eyebrow" style="color:#22d3ee;margin-top:28px;display:block;">Sofia Solutions</span>
            <h1 style="margin:14px 0 16px;font-size:clamp(1.9rem,3.5vw,2.8rem);line-height:1.06;letter-spacing:-0.04em;">
                Your Security,<br>Our Mission.
            </h1>
            <p style="color:var(--text-soft);line-height:1.7;font-size:0.95rem;max-width:38ch;">
                Acceso corporativo a la plataforma de seguridad gestionada, monitorización SOC y operación continua.
            </p>

            <!-- Indicadores de estado del sistema -->
            <div style="display:flex;flex-direction:column;gap:10px;margin-top:36px;">
                <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;background:rgba(16,185,129,0.07);border:1px solid rgba(16,185,129,0.2);">
                    <span style="width:8px;height:8px;border-radius:50%;background:#10b981;box-shadow:0 0 8px #10b981;flex-shrink:0;"></span>
                    <span style="font-size:0.82rem;color:#34d399;font-weight:600;">Plataforma operativa</span>
                    <span style="margin-left:auto;font-size:0.75rem;color:var(--text-muted);">100% uptime</span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;background:rgba(6,182,212,0.07);border:1px solid rgba(6,182,212,0.18);">
                    <span style="width:8px;height:8px;border-radius:50%;background:#06b6d4;box-shadow:0 0 8px #06b6d4;flex-shrink:0;"></span>
                    <span style="font-size:0.82rem;color:#22d3ee;font-weight:600;">SOC activo 24/7</span>
                    <span style="margin-left:auto;font-size:0.75rem;color:var(--text-muted);">En línea</span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;background:rgba(139,92,246,0.07);border:1px solid rgba(139,92,246,0.18);">
                    <span style="width:8px;height:8px;border-radius:50%;background:#8b5cf6;box-shadow:0 0 8px #8b5cf6;flex-shrink:0;"></span>
                    <span style="font-size:0.82rem;color:#a78bfa;font-weight:600;">Telemetría sincronizada</span>
                    <span style="margin-left:auto;font-size:0.75rem;color:var(--text-muted);">En tiempo real</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Panel derecho del formulario -->
    <section class="auth-panel auth-panel-form">
        <div class="auth-card" style="width:min(480px,100%);padding:40px 36px;border-radius:20px;background:rgba(8,12,26,0.85);border:1px solid rgba(6,182,212,0.12);box-shadow:0 8px 40px rgba(0,0,0,0.5);">

            <span class="card-kicker" style="color:#22d3ee;">Acceso a plataforma</span>
            <h2 style="margin:12px 0 6px;font-size:1.7rem;letter-spacing:-0.03em;">Iniciar sesión</h2>
            <p class="card-copy" style="color:var(--text-muted);font-size:0.9rem;margin-bottom:28px;line-height:1.6;">
                Introduce tus credenciales corporativas para acceder al entorno operativo.
            </p>

            <form id="login-form" class="login-form" novalidate style="gap:16px;">
                <label style="display:grid;gap:8px;">
                    <span style="font-size:0.85rem;font-weight:600;color:var(--text-soft);">Correo electrónico</span>
                    <input type="email" name="email" placeholder="usuario@empresa.com" autocomplete="username" required
                           style="min-height:50px;padding:0 16px;border-radius:12px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);color:var(--text);font-size:0.92rem;">
                </label>
                <label style="display:grid;gap:8px;">
                    <span style="font-size:0.85rem;font-weight:600;color:var(--text-soft);">Contraseña</span>
                    <input type="password" name="password" placeholder="••••••••••••" autocomplete="current-password" required
                           style="min-height:50px;padding:0 16px;border-radius:12px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);color:var(--text);font-size:0.92rem;">
                </label>

                <?php if (isset($mode) && $mode === 'secure'): ?>
                <div style="display:flex;align-items:center;gap:12px;padding:13px 14px;border:1px solid rgba(6,182,212,0.25);border-radius:12px;background:rgba(6,182,212,0.06);">
                    <input type="checkbox" id="hcaptcha" name="hcaptcha" required style="width:18px;height:18px;accent-color:var(--primary);cursor:pointer;flex-shrink:0;">
                    <div style="flex:1;">
                        <label for="hcaptcha" style="margin:0;cursor:pointer;font-weight:600;color:var(--text);font-size:0.88rem;">No soy un robot</label>
                        <small style="display:block;color:var(--text-muted);font-size:0.75rem;margin-top:3px;">Verificación de seguridad requerida</small>
                    </div>
                    <span style="font-size:1.2rem;">🛡️</span>
                </div>
                <?php endif; ?>

                <button class="btn btn-primary btn-block" type="submit"
                        style="min-height:52px;border-radius:12px;font-size:0.95rem;margin-top:4px;background:linear-gradient(135deg,#06b6d4,#7c3aed);box-shadow:0 4px 20px rgba(6,182,212,0.25);">
                    Iniciar sesión
                </button>
                <p id="login-error" class="form-error" hidden></p>
            </form>

            <div class="auth-footer-row" style="margin-top:20px;justify-content:space-between;align-items:center;">
                <a href="/" style="color:var(--text-muted);font-size:0.85rem;text-decoration:none;" onmouseover="this.style.color='#22d3ee'" onmouseout="this.style.color='var(--text-muted)'">← Volver al inicio</a>
                <span style="color:var(--text-muted);font-size:0.78rem;">Acceso seguro · TLS 1.3</span>
            </div>
        </div>
    </section>

</main>
