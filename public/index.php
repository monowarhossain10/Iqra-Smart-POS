<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

$route = trim((string) ($_GET['route'] ?? 'dashboard'), '/');

if ($route === 'api/products') {
    (new ApiController())->products();
}

if ($route === 'logout') {
    Auth::logout();
    redirect('');
}

$moduleRoutes = ['pos', 'sales', 'inventory', 'purchases', 'services', 'mfs', 'utility', 'utf', 'reports', 'returns', 'salaries', 'income', 'expenses', 'users'];

$loginError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $route === 'login') {
    if (!verify_csrf($_POST['_csrf'] ?? null)) {
        $loginError = 'Your session expired. Please try again.';
    } elseif (Auth::attempt((string) ($_POST['email'] ?? ''), (string) ($_POST['password'] ?? ''))) {
        redirect('');
    } else {
        $loginError = 'The email or password is incorrect.';
    }
}

if (!Auth::check()) {
    require dirname(__DIR__) . '/resources/views/auth/login.php';
    exit;
}

$page = $route === '' ? 'dashboard' : $route;
if (in_array($page, $moduleRoutes, true)) {
    (new ModuleController())->handle($page);
    exit;
}
if ($page === 'voucher') {
    if (!Auth::can('view_dashboard')) { http_response_code(403); exit('Forbidden'); }
    $voucher = (new ModuleService(Database::connection()))->voucher((int) ($_GET['id'] ?? 0));
    if (!$voucher) { http_response_code(404); exit('Voucher not found'); }
    require dirname(__DIR__) . '/resources/views/modules/voucher.php';
    exit;
}
if ($page !== 'dashboard') { $page = 'dashboard'; }

$dashboard = new DashboardService(Database::connection());
$summary = $dashboard->summary();
$recentSales = $dashboard->recentSales();
$lowStockProducts = $dashboard->lowStockProducts();
require dirname(__DIR__) . '/resources/views/layouts/app.php';