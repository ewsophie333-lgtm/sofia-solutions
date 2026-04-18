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
        ['key' => 'dashboard', 'href' => '/dashboard', 'label' => 'Resumen ejecutivo', 'icon' => '◧'],
        ['key' => 'soc', 'href' => '/admin/security-monitor', 'label' => 'Security monitor', 'icon' => '◎'],
        ['key' => 'login', 'href' => '/login', 'label' => 'Cerrar sesión', 'icon' => '→'],
        ['key' => 'grafana', 'href' => 'http://localhost:3000', 'label' => 'Grafana', 'icon' => '↗', 'external' => true],
    ];
    ?>
    <nav class="sidebar-nav" aria-label="Navegación de aplicación">
        <?php foreach ($items as $item): ?>
            <?php $external = $item['external'] ?? false; ?>
            <a
                href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
                class="<?= $activeNav === $item['key'] ? 'active' : '' ?>"
                <?= $external ? 'target="_blank" rel="noreferrer"' : '' ?>
            >
                <span class="nav-icon" aria-hidden="true"><?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?></span>
                <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    <?php
}
