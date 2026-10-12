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
    <!-- Brand / Logo Section -->
    <div class="sidebar-brand">
        <div class="brand-mark">
            <i class="fa-solid fa-feather-pointed"></i>
        </div>
        <div class="brand-copy">
            <strong>iqra</strong>
            <span>stationary solutions</span>
        </div>
        <button type="button" class="sidebar-collapse-toggle" id="sidebarCollapseToggle" aria-label="Collapse navigation" aria-expanded="true" title="Collapse navigation">
            <i class="fa-solid fa-angles-left"></i>
        </button>
    </div>

    <!-- Navigation Label -->
    <div class="sidebar-menu-wrapper">
        <div class="sidebar-label">Main Menu</div>
        <nav class="nav flex-column">
            <?php foreach ($navigation as [$route, $label, $icon, $href]): ?>
                <?php
                    $isActive = ($currentRoute === $route || ($route === 'dashboard' && $currentRoute === ''));
                ?>
                <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="<?= e($href) ?>">
                    <span class="nav-icon-wrap"><i class="fa-solid <?= e($icon) ?>"></i></span>
                    <span class="nav-label"><?= e($label) ?></span>
                    <?= $route === 'pos' ? '<span class="nav-badge">F2</span>' : '' ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>

    <!-- Bottom Help & Logout Section -->
    <div class="sidebar-bottom">
        <div class="help-box">
            <i class="fa-regular fa-circle-question"></i>
            <div class="help-copy">
                <strong>Need a hand?</strong>
                <small>Use quick actions</small>
            </div>
        </div>
        <a class="nav-link logout-link" href="?route=logout">
            <span class="nav-icon-wrap"><i class="fa-solid fa-right-from-bracket"></i></span>
            <span class="nav-label">Logout</span>
        </a>
    </div>
</aside>