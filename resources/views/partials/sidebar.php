<?php
$currentRoute = trim((string) ($_GET['route'] ?? 'dashboard'), '/');
$navigation = [
    ['dashboard', 'Overview', 'fa-grid-2', APP_URL],
    ['pos', 'Point of sale', 'fa-cash-register', '?route=pos'],
    ['sales', 'Recent sales', 'fa-receipt', '?route=sales'],
    ['inventory', 'Inventory', 'fa-boxes-stacked', '?route=inventory'],
    ['purchases', 'Purchases', 'fa-truck-field', '?route=purchases'],
    ['services', 'Service billing', 'fa-print', '?route=services'],
    ['returns', 'Product returns', 'fa-arrow-rotate-left', '?route=returns'],
    ['mfs', 'MFS ledger', 'fa-mobile-screen-button', '?route=mfs'],
    ['utility', 'Utility bills', 'fa-receipt', '?route=utility'],
    ['salaries', 'Employee salaries', 'fa-users-gear', '?route=salaries'],
    ['income', 'Income records', 'fa-hand-holding-dollar', '?route=income'],
    ['expenses', 'Expenses', 'fa-money-bill-wave', '?route=expenses'],
    ['accounts', 'All accounts', 'fa-wallet', '?route=accounts'],
    ['vouchers', 'All Vouchers', 'fa-ticket', '?route=vouchers'],
    ['reports', 'Reports', 'fa-chart-line', '?route=reports'],
    ['users', 'User management', 'fa-users', '?route=users'],
];
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-mark"><i class="fa-solid fa-feather-pointed"></i></div>
        <div class="brand-copy"><strong>iqra</strong><span>stationary solutions</span></div>
    </div>
    <button type="button" class="sidebar-collapse-toggle" id="sidebarCollapseToggle" aria-label="Collapse navigation" aria-expanded="true" title="Collapse navigation"><i class="fa-solid fa-angles-left"></i></button>
    <div class="sidebar-label">Navigation</div>
    <nav class="nav flex-column gap-1">
        <?php foreach ($navigation as [$route, $label, $icon, $href]): ?>
            <a class="nav-link <?= $currentRoute === $route || ($route === 'dashboard' && $currentRoute === '') ? 'active' : '' ?>" href="<?= e($href) ?>">
                <i class="fa-solid <?= e($icon) ?>"></i><span class="nav-label"><?= e($label) ?></span><?= $route === 'pos' ? '<span class="nav-badge">F2</span>' : '' ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-bottom">
        <div class="help-box"><i class="fa-regular fa-circle-question"></i><div class="help-copy"><strong>Need a hand?</strong><small>Use quick actions</small></div></div>
        <a class="nav-link logout-link" href="?route=logout"><i class="fa-solid fa-right-from-bracket"></i><span class="nav-label">Logout</span></a>
    </div>
</aside>
