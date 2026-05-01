<?php
declare(strict_types=1);

function renderLogo(string $class = 'brand-mark'): void
{
    ?>
    <img src="/assets/sofia-logo-login.png" alt="Sofia Solutions" class="<?= htmlspecialchars($class, ENT_QUOTES, 'UTF-8') ?>">
    <?php
}

function renderTopNav(string $activeNav): void
{
    $items = [
        'home' => ['href' => '/', 'label' => 'Inicio'],
        'login' => ['href' => '/login', 'label' => 'Acceso'],
    ];
    ?>
    <nav class="site-nav" aria-label="Navegación principal">
        <?php foreach ($items as $key => $item): ?>
            <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>" class="<?= $activeNav === $key ? 'active' : '' ?>">
                <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <?php
}

function renderAppNav(string $activeNav): void
{
    $items = [
        ['key' => 'dashboard',       'href' => '/dashboard',           'label' => 'Dashboard Cliente',      'icon' => '◧', 'role' => 'CLIENT'],
        ['key' => 'admin-dashboard', 'href' => '/admin',               'label' => 'Panel de Operaciones',   'icon' => '⊞', 'role' => 'ADMIN'],
        ['key' => 'soc',             'href' => '/admin/security-monitor','label' => 'Security Monitor SOC',  'icon' => '◎', 'role' => 'ADMIN'],
        ['key' => 'grafana',         'href' => '/grafana',             'label' => 'Metricas Grafana',       'icon' => '↗', 'external' => true, 'role' => 'ADMIN'],
        ['key' => 'login',           'href' => '/login',               'label' => 'Cerrar sesion',          'icon' => '→'],
    ];
    ?>
    <nav class="sidebar-nav" aria-label="Navegación de aplicación">
        <?php foreach ($items as $item): ?>
            <?php 
                $external = $item['external'] ?? false; 
                $roleRequired = $item['role'] ?? null;
            ?>
            <a
                href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
                class="<?= $activeNav === $item['key'] ? 'active' : '' ?>"
                <?= $external ? 'target="_blank" rel="noreferrer"' : '' ?>
                <?= $roleRequired ? 'data-role-required="' . $roleRequired . '"' : '' ?>
            >
                <span class="nav-icon" aria-hidden="true"><?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?></span>
                <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    <script>
    // Cliente-side role filtering
    (function() {
        const user = JSON.parse(localStorage.getItem('sofia_user_v1') || '{}');
        const role = user.role || 'GUEST';
        document.querySelectorAll('.sidebar-nav [data-role-required]').forEach(el => {
            const req = el.getAttribute('data-role-required');
            if (role !== 'ADMIN' && req === 'ADMIN') el.style.display = 'none';
            if (role === 'ADMIN' && req === 'CLIENT') el.style.display = 'none';
        });
    })();
    </script>
    <?php
}
