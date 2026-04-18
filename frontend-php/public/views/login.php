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
                <div class="captcha-container" style="margin:16px 0; padding:12px; border:1px solid var(--border); border-radius:6px; display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" id="hcaptcha" name="hcaptcha" required>
                    <label for="hcaptcha" style="margin:0; cursor:pointer;">No soy un robot (Protección Anti-Bot)</label>
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
