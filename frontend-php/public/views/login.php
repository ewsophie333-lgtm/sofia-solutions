<main class="auth-shell">

    <section class="auth-panel auth-panel-brand">
        <div class="auth-brand-frame">
            <?php renderLogo('brand-mark brand-mark-login'); ?>
            <span class="eyebrow" style="margin-top:28px;display:block;color:rgba(255,255,255,0.45);letter-spacing:0.14em;">Sofia Solutions</span>
            <h1 style="margin:14px 0 16px;font-size:clamp(1.9rem,3.5vw,2.8rem);line-height:1.06;letter-spacing:-0.04em;color:#f1f5f9;">
                Inteligencia activa.<br>Operación sin interrupciones.
            </h1>

            <div style="display:flex;flex-direction:column;gap:10px;margin-top:38px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="width:7px;height:7px;border-radius:50%;background:#10b981;box-shadow:0 0 8px #10b981;flex-shrink:0;"></span>
                    <span style="font-size:0.82rem;color:rgba(203,213,225,0.75);">Plataforma operativa &mdash; 100% uptime</span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="width:7px;height:7px;border-radius:50%;background:#10b981;box-shadow:0 0 8px #10b981;flex-shrink:0;"></span>
                    <span style="font-size:0.82rem;color:rgba(203,213,225,0.75);">SOC activo 24/7 &mdash; Telemetría en tiempo real</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Panel derecho del formulario -->
    <section class="auth-panel auth-panel-form">
        <div class="auth-card" style="width:min(460px,100%);padding:40px 36px;border-radius:18px;background:rgba(10,14,24,0.92);border:1px solid rgba(255,255,255,0.09);box-shadow:0 8px 40px rgba(0,0,0,0.5);">

            <span class="card-kicker" style="color:rgba(148,163,184,0.7);">Acceso a plataforma</span>
            <h2 style="margin:12px 0 24px;font-size:1.65rem;letter-spacing:-0.03em;color:#f1f5f9;">Iniciar sesión</h2>

            <form id="login-form" class="login-form" novalidate style="gap:14px;">
                <label style="display:grid;gap:7px;">
                    <span style="font-size:0.82rem;font-weight:600;color:rgba(148,163,184,0.9);">Correo electrónico</span>
                    <input type="email" name="email" placeholder="usuario@empresa.com" autocomplete="username" required
                           style="min-height:48px;padding:0 15px;border-radius:10px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);color:#f1f5f9;font-size:0.9rem;">
                </label>
                <label style="display:grid;gap:7px;">
                    <span style="font-size:0.82rem;font-weight:600;color:rgba(148,163,184,0.9);">Contraseña</span>
                    <input type="password" name="password" placeholder="••••••••••••" autocomplete="current-password" required
                           style="min-height:48px;padding:0 15px;border-radius:10px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);color:#f1f5f9;font-size:0.9rem;">
                </label>

                <?php if (isset($mode) && $mode === 'secure'): ?>
                <div style="display:flex;align-items:center;gap:12px;padding:12px 14px;border:1px solid rgba(255,255,255,0.1);border-radius:10px;background:rgba(255,255,255,0.03);">
                    <input type="checkbox" id="hcaptcha" name="hcaptcha" required style="width:17px;height:17px;cursor:pointer;flex-shrink:0;">
                    <div style="flex:1;">
                        <label for="hcaptcha" style="margin:0;cursor:pointer;font-weight:600;color:rgba(203,213,225,0.9);font-size:0.86rem;">No soy un robot</label>
                        <small style="display:block;color:rgba(100,116,139,0.9);font-size:0.73rem;margin-top:2px;">Verificación de seguridad requerida</small>
                    </div>
                    <span style="font-size:1.1rem;opacity:0.6;">&#x1F6E1;</span>
                </div>
                <?php endif; ?>

                <button class="btn btn-primary btn-block" type="submit"
                        style="min-height:50px;border-radius:11px;font-size:0.92rem;margin-top:6px;background:rgba(255,255,255,0.92);color:#0a0e18;font-weight:700;border:none;box-shadow:none;letter-spacing:0.01em;"
                        onmouseover="this.style.background='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.92)'">
                    Iniciar sesión
                </button>
                <p id="login-error" class="form-error" hidden></p>
            </form>

            <div style="margin-top:20px;display:flex;justify-content:center;">
                <a href="/" style="color:rgba(100,116,139,0.8);font-size:0.83rem;text-decoration:none;" onmouseover="this.style.color='rgba(203,213,225,0.9)'" onmouseout="this.style.color='rgba(100,116,139,0.8)'">&#8592; Volver al inicio</a>
            </div>
        </div>
    </section>

</main>
<script>
// LIMPIEZA DE SESION AL LLEGAR AL LOGIN
localStorage.removeItem('sofia_token_v1');
localStorage.removeItem('sofia_user_v1');
</script>
