<?php
/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS - PORTAL DE PHISHING (SIMULACIÓN DE EVIL PROXY)
 * ============================================================================
 * 
 * He desarrollado esta plantilla 'phishing.php' para simular de forma ultra-realista
 * un vector de ataque avanzado de ingeniería social (Phishing de sesión expirada)
 * integrado con un proxy inverso malicioso (Evil Proxy).
 * 
 * PROPÓSITO ACADÉMICO PARA EL TFG:
 * 1. El portal clona de forma exacta el diseño visual de la pantalla de login legítima.
 * 2. Muestra un banner persuasivo de "Sesión expirada por inactividad", empujando
 *    al usuario a introducir sus datos bajo un falso sentido de urgencia.
 * 3. Al enviar el formulario, las credenciales son interceptadas y enviadas en tiempo
 *    real a la Consola Maestra de Auditoría del atacante utilizando postMessage seguro,
 *    demostrando así la exfiltración silenciosa.
 * 
 * Autor: Sofia Gomez
 * Año: 2026
 * ============================================================================
 */
require_once __DIR__ . '/../includes/helpers.php';
?>
<style>
    body { font-size: 16px; background: #0a0e18; margin: 0; padding: 0; }
    
    .phishing-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.98);
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        color: #ef4444;
        font-family: 'Fira Code', monospace;
        text-align: center;
        padding: 20px;
    }

    .captured-data {
        background: #000;
        border: 1px solid #ef4444;
        padding: 24px;
        border-radius: 12px;
        color: #4ade80;
        font-size: 0.95rem;
        max-width: 550px;
        width: 100%;
        text-align: left;
        box-shadow: 0 0 50px rgba(239, 68, 68, 0.4);
        margin-top: 20px;
    }

    .brand-mark-login {
        max-height: 150px; /* Ajustado para que sea igual al original */
        width: auto;
        margin-bottom: 20px;
        filter: brightness(0) invert(1);
    }

    .timeout-alert {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: #f59e0b;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>

<!-- Pantalla de Éxito del Atacante -->
<div id="phishing-success" class="phishing-overlay">
    <div style="font-size: 3rem; margin-bottom: 20px;">🔓</div>
    <div style="font-size: 1.8rem; font-weight: bold; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 3px;">
        Attack Successful
    </div>
    <p style="color: #fff; margin-bottom: 30px; font-size: 1.1rem;">La sesión ha sido secuestrada mediante <strong>Evil Proxy</strong>.</p>
    
    <div class="captured-data">
        <div style="color: #ef4444; margin-bottom: 15px; font-weight: bold; font-size: 1.1rem; border-bottom: 1px solid rgba(239, 68, 68, 0.3); padding-bottom: 8px;">
            [EVIDENCIA DE EXFILTRACIÓN]
        </div>
        <div style="margin-bottom: 8px;"><strong>EMAIL:</strong> <span id="captured-email" style="color: #fff;"></span></div>
        <div style="margin-bottom: 8px;"><strong>PASSWORD:</strong> <span id="captured-pass" style="color: #fff;"></span></div>
        <div style="margin-top: 20px; color: #94a3b8; font-size: 0.8rem; line-height: 1.5;">
            <span style="color: #4ade80;">[+]</span> Bypass MFA completado exitosamente.<br>
            <span style="color: #4ade80;">[+]</span> Cookie de sesión inyectada en navegador del atacante.<br>
            <span style="color: #4ade80;">[+]</span> Acceso total a infraestructura crítica obtenido.
        </div>
    </div>
    <button onclick="window.location.href='/consola'" style="margin-top: 40px; padding: 14px 30px; background: #ef4444; border: none; color: #fff; border-radius: 8px; cursor: pointer; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
        Volver a la Consola de Auditoría
    </button>
</div>

<main class="auth-shell">
    <section class="auth-panel auth-panel-brand">
        <div class="auth-brand-frame">
            <?php renderLogo('brand-mark brand-mark-login'); ?>
            <span class="eyebrow" style="margin-top:28px;display:block;color:rgba(255,255,255,0.45);letter-spacing:0.14em;">Sofia Solutions</span>
            <h1 style="font-size:clamp(2.4rem,6vw,4.5rem);color:#f1f5f9;line-height:1.02;letter-spacing:-0.04em;margin:16px 0 20px;">
                Tu Seguridad,<br>Nuestra Misión.
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

    <section class="auth-panel auth-panel-form">
        <div class="auth-card" style="width:min(460px,100%);padding:40px 36px;border-radius:18px;background:rgba(10,14,24,0.92);border:1px solid rgba(255,255,255,0.09);box-shadow:0 8px 40px rgba(0,0,0,0.5);">
            
            <div class="timeout-alert">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>Por favor, reingresa tus credenciales para continuar.</span>
            </div>

            <span class="card-kicker" style="color:rgba(148,163,184,0.7);">Acceso a plataforma</span>
            <h2 style="margin:12px 0 24px;font-size:1.65rem;letter-spacing:-0.03em;color:#f1f5f9;">Iniciar sesión</h2>

            <form id="phishing-form" onsubmit="captureAndShow(event)" style="display: grid; gap: 14px;">
                <label style="display:grid;gap:7px;">
                    <span style="font-size:0.82rem;font-weight:600;color:rgba(148,163,184,0.9);">Correo electrónico</span>
                    <input type="email" id="p-email" name="email" placeholder="usuario@empresa.com" required
                        style="min-height:48px;padding:0 15px;border-radius:10px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);color:#f1f5f9;font-size:0.9rem;">
                </label>
                <label style="display:grid;gap:7px;">
                    <span style="font-size:0.82rem;font-weight:600;color:rgba(148,163,184,0.9);">Contraseña</span>
                    <input type="password" id="p-pass" name="password" placeholder="••••••••••••" required
                        style="min-height:48px;padding:0 15px;border-radius:10px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);color:#f1f5f9;font-size:0.9rem;">
                </label>

                <button class="btn btn-primary btn-block" type="submit"
                    style="min-height:50px;border-radius:11px;font-size:0.92rem;margin-top:6px;background:rgba(255,255,255,0.92);color:#0a0e18;font-weight:700;border:none;box-shadow:none;letter-spacing:0.01em;">
                    Iniciar sesión
                </button>
            </form>
            
            <div style="margin-top:20px;display:flex;justify-content:center;">
                <span style="color:rgba(100,116,139,0.8);font-size:0.83rem;">&#8592; Volver al inicio</span>
            </div>
        </div>
    </section>
</main>

<script>
    function captureAndShow(e) {
        e.preventDefault();
        const email = document.getElementById('p-email').value;
        const pass = document.getElementById('p-pass').value;
        
        document.getElementById('captured-email').innerText = email;
        document.getElementById('captured-pass').innerText = pass;
        
        const btn = e.target.querySelector('button');
        btn.innerText = 'Validando...';
        btn.disabled = true;

        setTimeout(() => {
            document.getElementById('phishing-success').style.display = 'flex';
            
            // Enviar datos a la consola maestra (si está abierta)
            if (window.opener) {
                window.opener.postMessage({
                    type: 'PHISHING_SUCCESS',
                    data: `USER: ${email}\nPASS: ${pass}\nTIME: ${new Date().toLocaleTimeString()}\nIP: 192.168.1.45 (via Proxy)`
                }, window.location.origin);
            }
        }, 1200);
    }
</script>
