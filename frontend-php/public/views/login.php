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
                    <small>Utiliza una cuenta autorizada para acceso a entorno corporativo.</small>
                </label>
                <label>
                    <span>Contraseña</span>
                    <input type="password" name="password" placeholder="Introduce tu contraseña" autocomplete="current-password" required>
                    <small>Las credenciales se validan contra la API de autenticación correspondiente.</small>
                </label>
                <button class="btn btn-primary btn-block" type="submit">Iniciar sesión</button>
                <p id="login-error" class="form-error" hidden></p>
            </form>

            <div class="auth-footer-row">
                <a href="/dashboard">Acceso al panel ejecutivo</a>
                <a href="/admin/security-monitor">Monitor de seguridad</a>
            </div>
        </div>
    </section>
</main>
