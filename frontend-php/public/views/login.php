<main class="auth-shell">
    <section class="auth-panel auth-panel-brand">
        <div class="auth-brand-frame">
            <?php renderLogo('brand-mark brand-mark-login'); ?>
            <span class="eyebrow">Sofia Solutions</span>
            <h1>Your Security, Our Mission</h1>
            <p>Acceso corporativo a la plataforma de seguridad gestionada y operación continua.</p>
        </div>
    </section>

    <section class="auth-panel auth-panel-form">
        <div class="auth-card">
            <span class="card-kicker">Acceso a plataforma</span>
            <h2>Iniciar sesión</h2>
            <p class="card-copy">Introduce tus credenciales para acceder al entorno operativo de Sofia Solutions.</p>

            <form id="login-form" class="login-form" novalidate>
                <label>
                    <span>Correo electrónico</span>
                    <input type="email" name="email" placeholder="admin@sofia.local" autocomplete="username" required>
                </label>
                <label>
                    <span>Contraseña</span>
                    <input type="password" name="password" placeholder="Introduce tu contraseña" autocomplete="current-password" required>
                </label>
                <?php if (isset($mode) && $mode === 'secure'): ?>
                <div class="captcha-container" style="margin:16px 0; padding:14px 12px; border:1px solid var(--primary); border-radius:8px; display:flex; align-items:center; gap:12px; background:rgba(6,182,212,0.07); box-shadow:0 0 10px rgba(6,182,212,0.08);">
                    <input type="checkbox" id="hcaptcha" name="hcaptcha" required style="width:20px;height:20px;accent-color:var(--primary);cursor:pointer;">
                    <div>
                        <label for="hcaptcha" style="margin:0; cursor:pointer; font-weight:600; color:var(--text);">No soy un robot</label>
                        <small style="display:block; color:var(--text-muted); font-size:0.75rem; margin-top:2px;">Protección Anti-Bot activa — Modo Seguro</small>
                    </div>
                    <span style="margin-left:auto; font-size:1.3rem;">🛡️</span>
                </div>
                <?php else: ?>
                <div class="captcha-container" style="margin:16px 0; padding:12px; border:1px dashed rgba(239,68,68,0.4); border-radius:8px; display:flex; align-items:center; gap:10px; background:rgba(239,68,68,0.05);">
                    <span style="font-size:1.1rem;">⚠️</span>
                    <small style="color:rgba(239,68,68,0.8); font-size:0.78rem;">Login vulnerable — Sin protección Anti-Bot ni CAPTCHA</small>
                </div>
                <?php endif; ?>
                <button class="btn btn-primary btn-block" type="submit">Iniciar sesión</button>
                <p id="login-error" class="form-error" hidden></p>
            </form>

            <div class="auth-footer-row">
                <a href="/">Volver a la página principal</a>
            </div>
        </div>
    </section>
</main>
