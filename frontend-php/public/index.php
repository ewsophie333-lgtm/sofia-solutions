<?php
declare(strict_types=1);

require __DIR__ . '/includes/helpers.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

// Admin route is handled by the router below — no redirect needed

$routes = [
    '/'                    => ['title' => 'Sofia Solutions | Seguridad gestionada para entornos críticos', 'view' => 'home'],
    '/login'               => ['title' => 'Acceso | Sofia Solutions', 'view' => 'login', 'mode' => 'vulnerable'],
    '/login-seguro'        => ['title' => 'Acceso seguro | Sofia Solutions', 'view' => 'login', 'mode' => 'secure'],
    '/dashboard'           => ['title' => 'Dashboard Ejecutivo | Sofia Solutions', 'view' => 'dashboard'],
    '/admin'               => ['title' => 'Panel de Operaciones | Sofia Solutions', 'view' => 'admin-dashboard'],
    '/admin/security-monitor' => ['title' => 'SOC Monitor | Sofia Solutions', 'view' => 'soc'],
    '/soc'                 => ['title' => 'SOC Monitor | Sofia Solutions', 'view' => 'soc'],
    '/admin/audit-tool'    => ['title' => 'Security Audit Kit | Sofia Solutions', 'view' => 'audit'],
    '/sistema/consola'     => ['title' => 'Consola Maestra | Sofia Solutions', 'view' => 'audit'],
    '/consola'             => ['title' => 'Consola Maestra | Sofia Solutions', 'view' => 'audit'],
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
        'avatar' => 'https://randomuser.me/api/portraits/men/51.jpg'
    ],
    [
        'company' => 'MAPFRE Seguros',
        'rating' => '4.8/5',
        'quote' => 'El servicio de IR Retainer y la cobertura de identidad nos permitieron contener una brecha en menos de 2 horas. Operación impecable.',
        'service' => 'IR Retainer',
        'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg'
    ],
    [
        'company' => 'Banco Sabadell',
        'rating' => '5.0/5',
        'quote' => 'Sofia Solutions gestiona la seguridad de nuestro sistema de banca central con un SLA de respuesta que supera cualquier expectativa del sector.',
        'service' => 'SOC 24/7',
        'avatar' => 'https://randomuser.me/api/portraits/men/33.jpg'
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
    <link rel="stylesheet" href="/assets/app.css">
    <script>
    // GUARDIA GLOBAL DE ROLES (Se ejecuta antes que el body)
    (function() {
        const user = JSON.parse(localStorage.getItem('sofia_user_v1') || '{}');
        const path = window.location.pathname;
        if (user.role === 'ADMIN' && path === '/dashboard') {
            window.location.href = '/admin';
        }
        if (user.role === 'CLIENT' && path === '/admin') {
            window.location.href = '/dashboard';
        }
    })();
    </script>
</head>
<body data-view="<?= htmlspecialchars($view, ENT_QUOTES, 'UTF-8') ?>" data-login-mode="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
<?php require __DIR__ . '/views/' . $view . '.php'; ?>

<script>
window.SOFIA_CONFIG = {
  apiBase: window.location.origin, // Usa el mismo dominio para evitar problemas de túneles mixtos
  view: "<?= htmlspecialchars($view, ENT_QUOTES, 'UTF-8') ?>",
  loginMode: "<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"
};
</script>
<script src="/assets/app.js"></script>
<?php include __DIR__ . '/includes/ai-assistant.php'; ?>
</body>
</html>
