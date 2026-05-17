<?php
declare(strict_types=1);

require __DIR__ . '/includes/helpers.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

// Aquí manejo todas las rutas de mi página. No necesito redirecciones porque todo pasa por este router.

$routes = [
    '/' => ['title' => 'Sofia Solutions | Seguridad gestionada para entornos críticos', 'view' => 'home'],
    '/login' => ['title' => 'Acceso | Sofia Solutions', 'view' => 'login', 'mode' => 'vulnerable'],
    '/login-seguro' => ['title' => 'Acceso seguro | Sofia Solutions', 'view' => 'login', 'mode' => 'secure'],
    '/dashboard' => ['title' => 'Dashboard Ejecutivo | Sofia Solutions', 'view' => 'dashboard'],
    '/admin' => ['title' => 'Panel de Operaciones | Sofia Solutions', 'view' => 'admin-dashboard'],
    '/admin/security-monitor' => ['title' => 'SOC Monitor | Sofia Solutions', 'view' => 'soc'],
    '/soc' => ['title' => 'SOC Monitor | Sofia Solutions', 'view' => 'soc'],
    '/admin/audit-tool' => ['title' => 'Security Audit Kit | Sofia Solutions', 'view' => 'audit'],
    '/sistema/consola' => ['title' => 'Consola Maestra | Sofia Solutions', 'view' => 'audit'],
    '/consola' => ['title' => 'Consola Maestra | Sofia Solutions', 'view' => 'audit'],
    '/phishing' => ['title' => 'Acceso Expirado | Sofia Solutions', 'view' => 'phishing'],
    '/phising' => ['title' => 'Acceso Expirado | Sofia Solutions', 'view' => 'phishing'],
];

$route = $routes[$path] ?? $routes['/'];
$title = $route['title'];
$view = $route['view'];
$mode = $route['mode'] ?? '';

$customerReviews = [
    [
        'company' => 'Iberdrola S.A.',
        'rating' => '4.9/5',
        'quote' => 'El SOC de Sofia redujo el tiempo de escalado en incidentes críticos y nos dio visibilidad real sobre nuestra infraestructura eléctrica y activos SCADA.',
        'service' => 'SOC 24/7',
        'avatar' => 'https://readdy.ai/api/search-image?query=professional%20male%20executive%20portrait%20corporate%20headshot%20dark%20background%20confident%20smile&width=80&height=80&seq=t1&orientation=squarish'
    ],
    [
        'company' => 'MAPFRE Seguros',
        'rating' => '4.8/5',
        'quote' => 'El servicio de IR Retainer y la cobertura de identidad nos permitieron contener una brecha en menos de 2 horas. Operación impecable.',
        'service' => 'IR Retainer',
        'avatar' => 'https://readdy.ai/api/search-image?query=professional%20female%20executive%20portrait%20corporate%20headshot%20confident%20business%20woman&width=80&height=80&seq=t2&orientation=squarish'
    ],
    [
        'company' => 'Banco Sabadell',
        'rating' => '5.0/5',
        'quote' => 'Sofia Solutions gestiona la seguridad de nuestro sistema de banca central con un SLA de respuesta que supera cualquier expectativa del sector.',
        'service' => 'SOC 24/7',
        'avatar' => 'https://readdy.ai/api/search-image?query=professional%20male%20IT%20director%20portrait%20corporate%20headshot%20mature%20executive&width=80&height=80&seq=t3&orientation=squarish'
    ],
];

$sectorBadges = ['Fintech', 'Industrial', 'Healthcare', 'Retail', 'Cloud Native'];
$serviceHighlights = [
    ['title' => 'SOC 24/7', 'copy' => 'Detección, correlación y escalado continuo con seguimiento de incidentes y priorización por criticidad.'],
    ['title' => 'Pentesting Premium', 'copy' => 'Validación ofensiva controlada para reducir exposición y anticipar fallos explotables.'],
    ['title' => 'IR Retainer', 'copy' => 'Respuesta ante incidentes con SLA de contención, análisis y coordinación operativa.'],
    ['title' => 'Cloud Hardening', 'copy' => 'Endurecimiento de identidades, servicios y perímetro para reducir riesgo residual.'],
];
$operationalBenefits = [
    ['label' => 'Tiempo medio de escalado', 'value' => '< 15 min', 'copy' => 'Cadena de actuación y priorización definidas para incidentes de alto impacto.'],
    ['label' => 'Cobertura operativa', 'value' => '24/7/365', 'copy' => 'Monitorización continua sobre activos, servicios críticos e identidades.'],
    ['label' => 'Superficie protegida', 'value' => '184 activos', 'copy' => 'Endpoints, workloads cloud, perímetro, correo y autenticación corporativa.'],
];
?><!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/app.css?v=1.0.4">
</head>

<body data-view="<?= htmlspecialchars($view, ENT_QUOTES, 'UTF-8') ?>"
    data-login-mode="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
    <?php require __DIR__ . '/views/' . $view . '.php'; ?>

    <!-- DoS Downtime Simulation Overlay -->
    <div id="dos-downtime-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:#202124; z-index:999999; justify-content:center; align-items:center; font-family:system-ui, -apple-system, sans-serif; color:#e8eaed; animation:fadeIn 0.4s ease;">
        <div style="max-width:550px; width:90%; padding:20px; text-align:left; animation:slideDown 0.3s ease;">
            <!-- Chrome Sad Dinosaur SVG -->
            <svg viewBox="0 0 24 24" style="width:72px; height:72px; fill:#9aa0a6; margin-bottom:24px;">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            <h1 style="font-size:20px; font-weight:500; margin:0 0 15px; color:#e8eaed;">No se puede acceder a este sitio web</h1>
            <p style="font-size:13px; color:#9aa0a6; line-height:1.6; margin:0 0 20px;">
                La página <strong>sofia-solutions.serveousercontent.com</strong> ha rechazado la conexión.
            </p>
            
            <div style="border-top:1px solid #3c4043; padding-top:20px; margin-bottom:25px;">
                <p style="font-size:13px; color:#9aa0a6; margin:0 0 10px;">Prueba a realizar las siguientes comprobaciones:</p>
                <ul style="font-size:13px; color:#9aa0a6; margin:0; padding-left:20px; line-height:1.6;">
                    <li>Comprobar la conexión a Internet</li>
                    <li>Comprobar el proxy, el cortafuegos y el estado del contenedor de backend</li>
                </ul>
            </div>
            
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <button onclick="window.location.reload()" style="background:#8ab4f8; color:#202124; border:none; border-radius:4px; padding:8px 16px; font-size:13px; font-weight:500; cursor:pointer; transition:background 0.2s;" onmouseover="this.style.background='#9ec2f9';" onmouseout="this.style.background='#8ab4f8';">
                    Cargar de nuevo
                </button>
                <span style="font-size:12px; color:#9aa0a6; font-family:monospace;">ERR_CONNECTION_REFUSED</span>
            </div>
        </div>
    </div>

    <script>
        function checkDoS() {
            const overlay = document.getElementById('dos-downtime-overlay');
            if (!overlay) return;
            
            const isDoSActive = localStorage.getItem('dos_active') === 'true';
            const isAuditView = window.SOFIA_CONFIG && window.SOFIA_CONFIG.view === 'audit';
            
            if (isDoSActive && !isAuditView) {
                overlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            } else {
                overlay.style.display = 'none';
                if (!isAuditView) {
                    document.body.style.overflow = '';
                }
            }
        }
        
        window.addEventListener('storage', checkDoS);
        setInterval(checkDoS, 200);
        checkDoS();
    </script>

    <script>
        window.SOFIA_CONFIG = {
            apiBase: "",
            view: "<?= htmlspecialchars($view, ENT_QUOTES, 'UTF-8') ?>",
            loginMode: "<?= (getenv('APP_MODE') === 'vulnerable' ? 'vulnerable' : 'secure') ?>"
        };
    </script>
    <script src="/assets/app.js?v=final"></script>
    <?php include __DIR__ . '/includes/ai-assistant.php'; ?>
</body>

</html>