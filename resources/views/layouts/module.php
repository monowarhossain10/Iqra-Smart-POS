<?php
$page ??= 'dashboard';
$meta = [
    'dashboard' => ['Overview', 'Welcome to your operational workspace', 'fa-grid-2'],
    'pos'       => ['Point of Sale', 'Fast counter checkout & billing', 'fa-cash-register'],
    'sales'     => ['Recent Sales', 'Review every completed counter transaction', 'fa-receipt'],
    'inventory' => ['Inventory', 'Products and stock control', 'fa-boxes-stacked'],
    'purchases' => ['Purchases', 'Receive stock from suppliers', 'fa-truck-field'],
    'services'  => ['Service Billing', 'Operator work and digital services', 'fa-print'],
    'mfs'       => ['MFS Ledger', 'Track mobile financial services', 'fa-mobile-screen-button'],
    'utility'   => ['Utility Bills', 'Bill collection and payments', 'fa-receipt'],
    'utf'       => ['Utility Bills', 'Bill collection and payments', 'fa-receipt'],
    'income'    => ['Income Records', 'Track business income streams', 'fa-hand-holding-dollar'],
    'expenses'  => ['Expenses', 'Track business operating expenses', 'fa-money-bill-wave'],
    'accounts'  => ['All Accounts', 'Today’s cash movement and financial position', 'fa-wallet'],
    'salaries'  => ['Employee Salaries', 'Manage salary payments', 'fa-users-gear'],
    'vouchers'  => ['All Vouchers', 'View all transaction vouchers', 'fa-ticket'],
    'reports'   => ['Reports', 'Financial and operational insights', 'fa-chart-line'],
    'users'     => ['User Management', 'Manage system users and permissions', 'fa-users'],
    'forbidden' => ['Access restricted', 'Your role cannot open this workspace', 'fa-lock'],
][$page] ?? ['Workspace', '', 'fa-grid-2'];

// Some legacy module files still contain a complete HTML document. They are
// rendered through this layout, so retain only their main content and discard
// their duplicate document shell, sidebar, and local topbar. Preserve the
// controller-provided view variables while rendering inside the buffer.
$moduleViewData = get_defined_vars();
$renderModule = static function (string $module) use ($moduleViewData): void {
    extract($moduleViewData, EXTR_SKIP);
    ob_start();
    require __DIR__ . '/../modules/' . $module . '.php';
    $document = (string) ob_get_clean();

    if (!preg_match('~<main\\b[^>]*>(.*)</main>~is', $document, $matches)) {
        echo $document;
        return;
    }

    $content = preg_replace(
        '~\\A\\s*(?:<!--.*?-->\\s*)*<header\\b[^>]*>.*?</header>\\s*~is',
        '',
        $matches[1],
        1
    );
    echo $content;
};
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($meta[0]) ?> | <?= e(APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= e(APP_URL) ?>/assets/css/app.css" rel="stylesheet">
    <link href="<?= e(APP_URL) ?>/assets/css/modules.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="app-body">
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content <?= $page === 'pos' ? 'pos-shell' : '' ?>">
        <!-- Topbar -->
        <header class="topbar px-4 py-3 shadow-xs bg-white border-bottom">
            <div class="module-heading">
                <button class="mobile-menu btn btn-light d-md-none" id="menuToggle"><i class="fa-solid fa-bars"></i></button>
                <div>
                    <p class="eyebrow text-uppercase text-muted fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Operations workspace</p>
                    <h1 class="h3 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid <?= e($meta[2]) ?> text-primary module-title-icon fs-4"></i><?= e($meta[0]) ?>
                    </h1>
                    <p class="module-subtitle text-muted small mb-0"><?= e($meta[1]) ?></p>
                </div>
            </div>
            <div class="topbar-actions">
                <div class="profile d-flex align-items-center gap-3">
                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px;">IS</div>
                    <div class="d-none d-md-block text-start lh-sm">
                        <strong class="d-block text-dark"><?= e(Auth::user()['name'] ?? 'Admin User') ?></strong>
                        <small class="text-muted"><?= e(Auth::user()['role'] ?? 'Admin') ?></small>
                    </div>
                    <a href="?route=logout" class="logout-link btn btn-outline-light text-danger border-0 p-2 rounded-circle" title="Sign out"><i class="fa-solid fa-arrow-right-from-bracket fs-5"></i></a>
                </div>
            </div>
        </header>

        <!-- Flash Messages & Alerts -->
        <div class="container-fluid px-4 pt-3">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger module-alert shadow-sm border-0 d-flex align-items-center"><i class="fa-solid fa-circle-exclamation me-2 fs-5"></i><div><?= e($error) ?></div></div>
            <?php elseif (!empty($flash)): ?>
                <div class="alert alert-success module-alert shadow-sm border-0 d-flex align-items-center"><i class="fa-solid fa-circle-check me-2 fs-5"></i><div><?= e($flash) ?></div></div>
            <?php endif; ?>
        </div>

        <!-- Dynamic Content Router Container -->
        <div class="container-fluid px-4 pb-5">
            <?php if ($page === 'forbidden'): ?>
                <section class="panel forbidden-panel text-center py-5 shadow-sm rounded-4 bg-white">
                    <i class="fa-solid fa-lock text-danger fa-3x mb-3"></i>
                    <h2 class="fw-bold">Access restricted</h2>
                    <p class="text-muted">Your current role does not have permission to view this module.</p>
                    <a class="btn btn-primary px-4 py-2 mt-2 shadow-sm" href="<?= e(APP_URL) ?>">Return to dashboard</a>
                </section>

            <?php elseif ($page === 'dashboard'): ?>
                <!-- Dashboard Overview View -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="panel shadow-sm rounded-4 p-4 bg-white border-0">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small fw-semibold text-uppercase">Today's Sales</span>
                                <div class="bg-primary-subtle text-primary p-2 rounded-3"><i class="fa-solid fa-cash-register"></i></div>
                            </div>
                            <h3 class="fw-bold mb-1">৳ <?= number_format($dashboard['today_sales'] ?? 0, 2) ?></h3>
                            <small class="text-success fw-medium"><i class="fa-solid fa-arrow-up me-1"></i> Active counter</small>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="panel shadow-sm rounded-4 p-4 bg-white border-0">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small fw-semibold text-uppercase">Total Products</span>
                                <div class="bg-success-subtle text-success p-2 rounded-3"><i class="fa-solid fa-boxes-stacked"></i></div>
                            </div>
                            <h3 class="fw-bold mb-1"><?= number_format($dashboard['total_products'] ?? 0) ?></h3>
                            <small class="text-muted">In stock catalog</small>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="panel shadow-sm rounded-4 p-4 bg-white border-0">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small fw-semibold text-uppercase">Service Revenue</span>
                                <div class="bg-warning-subtle text-warning p-2 rounded-3"><i class="fa-solid fa-print"></i></div>
                            </div>
                            <h3 class="fw-bold mb-1">৳ <?= number_format($dashboard['service_revenue'] ?? 0, 2) ?></h3>
                            <small class="text-muted">Operator jobs</small>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="panel shadow-sm rounded-4 p-4 bg-white border-0">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small fw-semibold text-uppercase">Low Stock Alert</span>
                                <div class="bg-danger-subtle text-danger p-2 rounded-3"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            </div>
                            <h3 class="fw-bold mb-1 text-danger"><?= number_format($dashboard['low_stock_count'] ?? 0) ?></h3>
                            <small class="text-danger fw-medium">Needs reorder</small>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <section class="panel shadow-sm rounded-4 p-4 bg-white h-100">
                            <div class="panel-heading mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="section-kicker text-primary fw-bold small text-uppercase">Quick Shortcuts</span>
                                    <h2 class="h5 fw-bold mb-0">Operations workspace</h2>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <a href="?route=pos" class="card text-decoration-none border shadow-xs rounded-3 p-3 h-100 text-center bg-light hover-shadow transition">
                                        <i class="fa-solid fa-cash-register text-primary fa-2x mb-2"></i>
                                        <h6 class="fw-bold text-dark mb-1">Point of Sale</h6>
                                        <small class="text-muted">Fast counter checkout</small>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="?route=inventory" class="card text-decoration-none border shadow-xs rounded-3 p-3 h-100 text-center bg-light hover-shadow transition">
                                        <i class="fa-solid fa-boxes-stacked text-success fa-2x mb-2"></i>
                                        <h6 class="fw-bold text-dark mb-1">Inventory</h6>
                                        <small class="text-muted">Stock and products</small>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="?route=services" class="card text-decoration-none border shadow-xs rounded-3 p-3 h-100 text-center bg-light hover-shadow transition">
                                        <i class="fa-solid fa-print text-warning fa-2x mb-2"></i>
                                        <h6 class="fw-bold text-dark mb-1">Service Billing</h6>
                                        <small class="text-muted">Operator tasks & prints</small>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="?route=mfs" class="card text-decoration-none border shadow-xs rounded-3 p-3 h-100 text-center bg-light hover-shadow transition">
                                        <i class="fa-solid fa-mobile-screen-button text-info fa-2x mb-2"></i>
                                        <h6 class="fw-bold text-dark mb-1">MFS Ledger</h6>
                                        <small class="text-muted">bKash, Nagad, Rocket</small>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="?route=utility" class="card text-decoration-none border shadow-xs rounded-3 p-3 h-100 text-center bg-light hover-shadow transition">
                                        <i class="fa-solid fa-receipt text-danger fa-2x mb-2"></i>
                                        <h6 class="fw-bold text-dark mb-1">Utility Bills</h6>
                                        <small class="text-muted">Bill collection</small>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="?route=reports" class="card text-decoration-none border shadow-xs rounded-3 p-3 h-100 text-center bg-light hover-shadow transition">
                                        <i class="fa-solid fa-chart-line text-secondary fa-2x mb-2"></i>
                                        <h6 class="fw-bold text-dark mb-1">Reports</h6>
                                        <small class="text-muted">Insights & analytics</small>
                                    </a>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-4">
                        <section class="panel shadow-sm rounded-4 p-4 bg-white h-100">
                            <div class="panel-heading mb-3">
                                <span class="section-kicker text-primary fw-bold small text-uppercase">System Info</span>
                                <h2 class="h5 fw-bold mb-0">Iqra Stationary</h2>
                            </div>
                            <p class="text-muted small">Welcome back! Manage your stationery store point-of-sale, inventory, digital services, and accounts efficiently from this unified workspace.</p>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted">Logged in user:</span>
                                <strong class="small"><?= e(Auth::user()['name'] ?? 'Admin User') ?></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="small text-muted">Role:</span>
                                <span class="badge bg-primary-subtle text-primary"><?= e(Auth::user()['role'] ?? 'Admin') ?></span>
                            </div>
                            <a href="?route=pos" class="btn btn-primary w-100 shadow-sm fw-semibold"><i class="fa-solid fa-cash-register me-1"></i> Open POS Counter</a>
                        </section>
                    </div>
                </div>

            <?php elseif ($page === 'inventory'): ?>
                <div class="module-grid">
                    <section class="panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Catalog</span><h2 class="h5 fw-bold mb-0">Add product</h2></div>
                        </div>
                        <form method="post" class="row g-3">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <div class="col-12">
                                <label class="form-label fw-medium">Product name</label>
                                <input class="form-control" name="name" required placeholder="e.g. Premium ball pen">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">SKU</label>
                                <input class="form-control" name="sku" required placeholder="PEN-001">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Barcode</label>
                                <input class="form-control" name="barcode" placeholder="Optional barcode">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Category</label>
                                <select class="form-select" name="category_id" required>
                                    <?php foreach ($categories ?? [] as $category): ?>
                                        <option value="<?= $category['id'] ?>"><?= e($category['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Unit</label>
                                <input class="form-control" name="unit" value="piece">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Buying price</label>
                                <input class="form-control" name="buying_price" type="number" min="0" step=".01" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Selling price</label>
                                <input class="form-control" name="selling_price" type="number" min="0" step=".01" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Opening stock</label>
                                <input class="form-control" name="stock_quantity" type="number" min="0" step=".001" value="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Low stock alert at</label>
                                <input class="form-control" name="low_stock_threshold" type="number" min="0" step=".001" value="5">
                            </div>
                            <div class="col-12 pt-2">
                                <button class="btn btn-primary w-100 py-2 shadow-sm fw-semibold"><i class="fa-solid fa-plus me-1"></i> Add product</button>
                            </div>
                        </form>
                    </section>
                    <section class="panel wide-panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading d-flex justify-content-between align-items-center mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Live catalog</span><h2 class="h5 fw-bold mb-0">Products <span class="count-badge badge bg-light text-dark border"><?= count($products ?? []) ?></span></h2></div>
                            <input class="table-filter form-control form-control-sm w-auto" data-filter-table="#productTable" placeholder="Search products...">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="productTable">
                                <thead class="table-light">
                                    <tr><th>Product</th><th>SKU</th><th>Category</th><th>Buying</th><th>Selling</th><th>Stock</th><th>Actions</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products ?? [] as $product): ?>
                                        <tr>
                                            <td><strong><?= e($product['name']) ?></strong><small class="d-block text-muted"><?= e($product['unit']) ?></small></td>
                                            <td><?= e($product['sku']) ?></td>
                                            <td><?= e($product['category_name'] ?? '-') ?></td>
                                            <td>৳ <?= number_format((float) $product['buying_price'], 2) ?></td>
                                            <td><strong>৳ <?= number_format((float) $product['selling_price'], 2) ?></strong></td>
                                            <td><span class="stock-status px-2 py-1 rounded-pill small fw-semibold <?= (float) $product['stock_quantity'] <= (float) $product['low_stock_threshold'] ? 'bg-danger-subtle text-danger stock-low' : 'bg-success-subtle text-success stock-ok' ?>"><?= number_format((float) $product['stock_quantity'], 0) ?></span></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-secondary print-product-labels" title="Print price label" aria-label="Print price label for <?= e($product['name']) ?>" data-label-product="<?= e(json_encode(['name' => $product['name'], 'sku' => $product['sku'], 'barcode' => $product['barcode'] ?? '', 'price' => $product['selling_price']])) ?>">
                                                    <i class="fa-solid fa-tag"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

            <?php elseif ($page === 'pos'): ?>
                <?php $lowStockCount = count(array_filter($products ?? [], static fn ($product) => (float) $product['stock_quantity'] <= (float) $product['low_stock_threshold'])); ?>
                <section class="pos-command-bar" aria-label="POS actions">
                    <div class="counter-status"><span class="status-dot"></span><span><strong>Counter ready</strong><small><?= e(Auth::user()['name'] ?? 'Operator') ?> · <?= date('D, d M') ?></small></span></div>
                    <div class="pos-shortcuts" aria-label="Keyboard shortcuts"><span><kbd>F2</kbd> Search</span><span><kbd>Enter</kbd> Add match</span><span><kbd>Ctrl</kbd> + <kbd>Enter</kbd> Pay</span></div>
                </section>
                <div class="pos-layout">
                    <section class="panel pos-products shadow-sm rounded-4 p-4 bg-white">
                        <div class="pos-toolbar">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">New sale</span><h2 class="h4 fw-bold mb-0">Find a product</h2><small class="text-muted">Search by name, SKU, or barcode scanner</small></div>
                            <div class="pos-search-box" role="search"><i class="fa-solid fa-barcode"></i><input id="posProductSearch" type="search" placeholder="Scan barcode or search products" autocomplete="off" aria-controls="posProducts" aria-label="Search products"><kbd>F2</kbd></div>
                        </div>
                        <div class="pos-categories" role="toolbar" aria-label="Product categories">
                            <button type="button" class="category-pill active" data-category="all">All <span><?= number_format(count($products ?? [])) ?></span></button>
                            <?php $categories = array_values(array_unique(array_filter(array_map(static fn ($product) => $product['category_name'] ?? null, $products ?? [])))); ?>
                            <?php foreach ($categories as $category): ?><?php $categoryCount = count(array_filter($products ?? [], static fn ($product) => strtolower((string) ($product['category_name'] ?? 'uncategorized')) === strtolower($category))); ?><button type="button" class="category-pill" data-category="<?= e(strtolower($category)) ?>"><?= e($category) ?> <span><?= $categoryCount ?></span></button><?php endforeach; ?>
                        </div>
                        <div class="product-list-meta"><strong id="productMatchCount" aria-live="polite"><?= number_format(count($products ?? [])) ?> products</strong><span>Click a product or press <kbd>Enter</kbd> after a search</span></div>
                        <div class="product-list" id="posProducts">
                            <?php foreach ($products ?? [] as $product): ?>
                                <button type="button" class="product-tile shadow-xs border rounded-3 p-3 text-start bg-white" <?= (float) $product['stock_quantity'] <= 0 ? 'disabled' : '' ?> data-category="<?= e(strtolower($product['category_name'] ?? 'uncategorized')) ?>" data-product='<?= e(json_encode(['id'=>(int)$product['id'],'name'=>$product['name'],'sku'=>$product['sku'],'barcode'=>$product['barcode'] ?? '','price'=>(float)$product['selling_price'],'stock'=>(float)$product['stock_quantity']])) ?>'>
                                    <span class="product-tile-icon"><i class="fa-solid fa-box"></i></span>
                                    <span class="product-details"><strong><?= e($product['name']) ?></strong><small><?= e($product['sku']) ?><?= !empty($product['barcode']) ? ' · ' . e($product['barcode']) : '' ?></small></span>
                                    <span class="stock-badge <?= (float) $product['stock_quantity'] <= (float) $product['low_stock_threshold'] ? 'is-low' : '' ?>"><i class="fa-solid fa-cubes-stacked"></i> <?= number_format((float) $product['stock_quantity'], 0) ?> left</span>
                                    <b>৳ <?= number_format((float) $product['selling_price'], 2) ?></b><i class="fa-solid fa-plus product-add-icon" aria-hidden="true"></i>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <div id="noProductMatches" class="pos-no-matches" hidden><i class="fa-solid fa-magnifying-glass"></i><strong>No matching products</strong><span>Try a different name, SKU, or barcode.</span></div>
                    </section>
                    <section class="panel cart-panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading d-flex justify-content-between align-items-center mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Current order</span><h2 class="h5 fw-bold mb-0">Cart <span class="count-badge badge bg-primary" id="cartCount">0</span></h2></div>
                            <button type="button" class="clear-cart btn btn-light btn-sm" id="clearCart" title="Clear current order"><i class="fa-solid fa-trash-can me-1"></i> Clear</button>
                        </div>
                        <div id="cartItems" class="cart-items border rounded-3 p-3 mb-3 bg-light overflow-auto" data-initial-cart='<?= e(json_encode($resumeHold['cart'] ?? [])) ?>'>
                            <div class="empty-state compact text-center text-muted py-4"><i class="fa-solid fa-basket-shopping fs-3 mb-2"></i><br>Select an item to begin</div>
                        </div>
                        <form method="post" id="saleForm" class="cart-form">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="cart" id="cartInput">
                            <input type="hidden" name="hold_id" value="<?= (int) ($resumeHold['id'] ?? 0) ?>">
                            <label class="form-label fw-medium">Customer <span class="optional text-muted small">Optional</span></label>
                            <input class="form-control mb-2" name="customer_name" placeholder="Walk-in customer" value="<?= e($resumeHold['customer_name'] ?? '') ?>">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-medium">Discount</label>
                                    <input class="form-control" id="saleDiscount" name="discount" type="number" min="0" step=".01" value="<?= e($resumeHold['discount'] ?? '0') ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-medium">Payment</label>
                                    <select class="form-select" id="paymentMethod" name="payment_method">
                                        <?php foreach (['cash', 'bKash', 'Nagad', 'Rocket', 'card', 'mixed'] as $paymentMethod): ?><option <?= ($resumeHold['payment_method'] ?? 'cash') === $paymentMethod ? 'selected' : '' ?>><?= e($paymentMethod) ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-medium">Amount received</label>
                                    <input class="form-control" id="paidAmount" name="paid_amount" type="number" min="0" step=".01" placeholder="Enter amount received" value="<?= e($resumeHold['paid_amount'] ?? '') ?>">
                                </div>
                                <div class="col-12"><label class="form-label fw-medium">Hold note <span class="optional text-muted small">Optional</span></label><input class="form-control" name="hold_note" placeholder="Waiting for customer confirmation" value="<?= e($resumeHold['note'] ?? '') ?>"></div>
                            </div>
                            <div class="payment-presets" aria-label="Quick payment selection"><button type="button" data-payment="cash"><i class="fa-solid fa-money-bill-wave"></i> Cash</button><button type="button" data-payment="bKash">bKash</button><button type="button" data-payment="Nagad">Nagad</button><button type="button" data-payment="card"><i class="fa-regular fa-credit-card"></i> Card</button></div>
                            <div class="cart-summary p-3 rounded-3 bg-light mb-3">
                                <div><span>Subtotal</span><strong id="cartSubtotal">৳ 0.00</strong></div>
                                <div><span>Discount</span><strong id="cartDiscount">৳ 0.00</strong></div>
                                <div class="cart-total"><span>Total due</span><strong id="cartTotal">৳ 0.00</strong></div>
                                <div class="change-due"><span id="changeLabel">Change to return</span><strong id="changeDue">৳ 0.00</strong></div>
                            </div>
                            <div class="pos-checkout-actions"><button class="btn btn-outline-warning py-2 fw-semibold" type="submit" name="action" value="hold"><i class="fa-solid fa-pause me-1"></i> Hold <kbd>F4</kbd></button><button class="btn btn-primary py-2 shadow-sm fw-semibold" id="completeSale" type="submit"><i class="fa-solid fa-lock me-1"></i> Pay now <kbd>Ctrl ↵</kbd></button></div>
                        </form>
                    </section>
                </div>
                <section class="panel held-bills-panel shadow-sm rounded-4 p-4 bg-white">
                    <div class="panel-heading d-flex justify-content-between align-items-center mb-3"><div><span class="section-kicker text-warning fw-bold small text-uppercase">Paused checkout</span><h2 class="h5 fw-bold mb-0">Held bills <span class="count-badge badge bg-warning text-dark"><?= number_format(count($heldBills ?? [])) ?></span></h2></div><small class="text-muted">Saved for this operator</small></div>
                    <div class="held-bills-list">
                        <?php foreach ($heldBills ?? [] as $heldBill): ?>
                            <div class="held-bill-row"><div><strong><?= e($heldBill['customer_name'] ?: 'Walk-in customer') ?></strong><small><?= count($heldBill['cart']) ?> item types · <?= date('d M, h:i A', strtotime($heldBill['updated_at'])) ?><?= $heldBill['note'] ? ' · ' . e($heldBill['note']) : '' ?></small></div><div class="held-bill-actions"><a class="btn btn-sm btn-outline-primary" href="?route=pos&resume_hold=<?= (int) $heldBill['id'] ?>"><i class="fa-solid fa-play me-1"></i>Resume</a><button type="button" class="btn btn-sm btn-outline-danger delete-held-bill" data-hold-id="<?= (int) $heldBill['id'] ?>"><i class="fa-solid fa-trash"></i></button></div></div>
                        <?php endforeach; ?>
                        <?php if (empty($heldBills)): ?><div class="empty-state compact text-center text-muted py-3"><i class="fa-solid fa-pause-circle mb-2"></i><br>No held bills</div><?php endif; ?>
                    </div>
                    <form id="deleteHeldBillForm" method="post" class="d-none"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete_hold"><input type="hidden" name="hold_id" id="deleteHeldBillId"></form>
                </section>
                <section class="panel recent-sales-panel shadow-sm rounded-4 p-4 bg-white">
                    <div class="panel-heading d-flex justify-content-between align-items-center mb-3"><div><span class="section-kicker text-primary fw-bold small text-uppercase">Activity</span><h2 class="h5 fw-bold mb-0">Recent sales</h2></div><a class="small fw-semibold" href="?route=sales">View all sales <i class="fa-solid fa-arrow-right ms-1"></i></a></div>
                    <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Invoice</th><th>Customer</th><th>Operator</th><th>Payment</th><th>Total</th><th>Time</th></tr></thead><tbody>
                        <?php foreach (array_slice($sales ?? [], 0, 6) as $sale): ?><tr><td><strong><?= e($sale['invoice_number']) ?></strong></td><td><?= e($sale['customer_name'] ?? 'Walk-in customer') ?></td><td><?= e($sale['full_name']) ?></td><td><span class="badge bg-light text-dark border"><?= e($sale['payment_method']) ?></span></td><td><strong>৳ <?= number_format((float)$sale['total'], 2) ?></strong></td><td class="text-muted small"><?= date('d M, h:i A', strtotime($sale['created_at'])) ?></td></tr><?php endforeach; ?>
                        <?php if (empty($sales)): ?><tr><td colspan="6" class="text-center py-4 text-muted">No sales recorded yet.</td></tr><?php endif; ?></tbody></table></div>
                </section>

            <?php elseif ($page === 'sales'): ?>
                <section class="panel shadow-sm rounded-4 p-4 bg-white mb-4">
                    <form method="get" class="row g-3 align-items-end">
                        <input type="hidden" name="route" value="sales">
                        <div class="col-lg-4"><label class="form-label fw-semibold">Search</label><input class="form-control" name="search" value="<?= e($_GET['search'] ?? '') ?>" placeholder="Invoice, customer, or operator"></div>
                        <div class="col-lg-2"><label class="form-label fw-semibold">From</label><input class="form-control" type="date" name="from" value="<?= e($_GET['from'] ?? '') ?>"></div>
                        <div class="col-lg-2"><label class="form-label fw-semibold">To</label><input class="form-control" type="date" name="to" value="<?= e($_GET['to'] ?? '') ?>"></div>
                        <div class="col-lg-2"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status"><option value="">All statuses</option><?php foreach (['paid', 'partial', 'void'] as $saleStatus): ?><option value="<?= $saleStatus ?>" <?= ($_GET['status'] ?? '') === $saleStatus ? 'selected' : '' ?>><?= ucfirst($saleStatus) ?></option><?php endforeach; ?></select></div>
                        <div class="col-lg-2 d-flex gap-2"><button class="btn btn-primary flex-grow-1"><i class="fa-solid fa-filter me-1"></i>Filter</button><a class="btn btn-outline-secondary" href="?route=sales" title="Clear filters"><i class="fa-solid fa-rotate-left"></i></a></div>
                    </form>
                </section>
                <section class="panel shadow-sm rounded-4 p-4 bg-white">
                    <div class="panel-heading d-flex justify-content-between align-items-center mb-3"><div><span class="section-kicker text-primary fw-bold small text-uppercase">Sales register</span><h2 class="h5 fw-bold mb-0">All sales <span class="badge bg-light text-dark border"><?= number_format(count($sales ?? [])) ?></span></h2></div><button class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="fa-solid fa-print me-1"></i>Print</button></div>
                    <div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>Invoice</th><th>Customer</th><th>Operator</th><th>Subtotal</th><th>Discount</th><th>Total</th><th>Received</th><th>Change / due</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr></thead><tbody>
                        <?php foreach ($sales ?? [] as $sale): $difference = (float) $sale['paid_amount'] - (float) $sale['total']; ?><tr><td><strong><?= e($sale['invoice_number']) ?></strong></td><td><?= e($sale['customer_name'] ?: 'Walk-in customer') ?></td><td><?= e($sale['full_name']) ?></td><td>৳ <?= number_format((float)$sale['subtotal'], 2) ?></td><td>৳ <?= number_format((float)$sale['discount'], 2) ?></td><td><strong>৳ <?= number_format((float)$sale['total'], 2) ?></strong></td><td>৳ <?= number_format((float)$sale['paid_amount'], 2) ?></td><td class="<?= $difference >= 0 ? 'text-success' : 'text-danger' ?>">৳ <?= number_format(abs($difference), 2) ?></td><td><span class="badge bg-light text-dark border"><?= e($sale['payment_method']) ?></span></td><td><span class="badge <?= $sale['status'] === 'paid' ? 'bg-success-subtle text-success' : ($sale['status'] === 'partial' ? 'bg-warning-subtle text-warning-emphasis' : 'bg-danger-subtle text-danger') ?>"><?= e(ucfirst($sale['status'])) ?></span></td><td class="small text-muted"><?= date('d M Y, h:i A', strtotime($sale['created_at'])) ?></td><td><?php if (!empty($sale['voucher_id'])): ?><a class="btn btn-sm btn-outline-primary" href="?route=voucher&id=<?= (int)$sale['voucher_id'] ?>" title="View voucher"><i class="fa-solid fa-eye"></i></a><?php endif; ?></td></tr><?php endforeach; ?>
                        <?php if (empty($sales)): ?><tr><td colspan="12" class="text-center py-5 text-muted"><i class="fa-solid fa-receipt fs-2 d-block mb-2"></i>No sales match the selected filters.</td></tr><?php endif; ?></tbody></table></div>
                </section>

            <?php elseif ($page === 'purchases'): ?>
                <div class="module-grid">
                    <section class="panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Goods receiving</span><h2 class="h5 fw-bold mb-0">Record purchase</h2></div>
                        </div>
                        <form method="post" class="row g-3">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <div class="col-12">
                                <label class="form-label fw-medium">Supplier</label>
                                <select name="supplier_id" class="form-select" required>
                                    <?php foreach ($suppliers ?? [] as $supplier): ?>
                                        <option value="<?= $supplier['id'] ?>"><?= e($supplier['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2"><label class="form-label fw-medium mb-0">Products in this delivery</label><button type="button" class="btn btn-sm btn-outline-primary" id="addPurchaseRow"><i class="fa-solid fa-plus me-1"></i>Add product</button></div>
                                <div id="purchaseItems" class="purchase-items">
                                    <div class="purchase-item-row row g-2 align-items-end">
                                        <div class="col-md-5"><label class="form-label small text-muted">Search created product</label><input class="form-control purchase-product-search mb-1" type="search" placeholder="Name, SKU, barcode..." autocomplete="off"><select id="purchaseProduct0" name="items[0][product_id]" class="form-select purchase-product-select" required><?php foreach ($products ?? [] as $product): ?><option value="<?= $product['id'] ?>"><?= e($product['name']) ?> · <?= e($product['sku']) ?><?= !empty($product['barcode']) ? ' · ' . e($product['barcode']) : '' ?><?= !empty($product['description']) ? ' · ' . e($product['description']) : '' ?></option><?php endforeach; ?></select></div>
                                        <div class="col-md-2"><label class="form-label small text-muted">Quantity</label><input class="form-control purchase-number" name="items[0][quantity]" type="number" min=".001" step=".001" required></div>
                                        <div class="col-md-2"><label class="form-label small text-muted">Unit cost</label><input class="form-control purchase-number" name="items[0][unit_cost]" type="number" min="0" step=".01" required></div>
                                        <div class="col-md-2"><label class="form-label small text-muted">Line discount</label><input class="form-control purchase-number" name="items[0][discount]" type="number" min="0" step=".01" value="0"></div>
                                        <div class="col-md-1"><button type="button" class="btn btn-outline-danger remove-purchase-row" title="Remove product" disabled><i class="fa-solid fa-xmark"></i></button></div>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-2">Add all products received from this supplier in one order.</small>
                            </div>
                            <div class="col-12"><div class="purchase-payment-box"><div class="d-flex justify-content-between align-items-center mb-2"><strong>Payment reconciliation</strong><span class="small text-muted">Record what was actually paid</span></div><div class="row g-2"><div class="col-md-4"><label class="form-label small text-muted">Amount paid</label><input class="form-control purchase-number" name="paid_amount" id="purchasePaidAmount" type="number" min="0" step=".01" value="0"></div><div class="col-md-4"><label class="form-label small text-muted">Payment method</label><select class="form-select" name="payment_method"><option>cash</option><option>bKash</option><option>Nagad</option><option>Rocket</option><option>card</option><option>bank</option></select></div><div class="col-md-4"><label class="form-label small text-muted">Reference</label><input class="form-control" name="payment_reference" placeholder="Receipt or transfer reference"></div></div><div class="purchase-totals mt-3"><span>Gross subtotal <strong id="purchaseGrossTotal">৳ 0.00</strong></span><span>Discounts <strong id="purchaseDiscountTotal">৳ 0.00</strong></span><span>Total due <strong id="purchaseNetTotal">৳ 0.00</strong></span><span>Balance due <strong id="purchaseBalanceDue">৳ 0.00</strong></span></div></div></div>
                            <div class="col-12 pt-2">
                                <button class="btn btn-primary w-100 py-2 shadow-sm fw-semibold"><i class="fa-solid fa-boxes-stacked me-1"></i> Receive stock</button>
                            </div>
                        </form>
                    </section>
                    <section class="panel wide-panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Supplier activity</span><h2 class="h5 fw-bold mb-0">Purchase history</h2></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr><th>Order</th><th>Supplier</th><th>Total</th><th>Paid</th><th>Balance</th><th>Method</th><th>Status</th><th>Date</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($purchases ?? [] as $purchase): ?>
                                        <tr>
                                            <td><strong><?= e($purchase['order_number']) ?></strong></td>
                                            <td><?= e($purchase['supplier_name']) ?></td>
                                            <td>৳ <?= number_format((float) $purchase['total'], 2) ?></td>
                                            <td>৳ <?= number_format((float) ($purchase['paid_amount'] ?? 0), 2) ?></td>
                                            <td class="<?= (float) $purchase['total'] - (float) ($purchase['paid_amount'] ?? 0) > 0 ? 'text-danger' : 'text-success' ?> fw-semibold">৳ <?= number_format(max(0, (float) $purchase['total'] - (float) ($purchase['paid_amount'] ?? 0)), 2) ?></td>
                                            <td><?= e($purchase['payment_method'] ?? 'cash') ?></td>
                                            <td><span class="status-pill status-paid badge bg-success-subtle text-success px-2 py-1"><?= e($purchase['status']) ?></span></td>
                                            <td><?= date('d M Y', strtotime($purchase['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
                <section class="panel shadow-sm rounded-4 p-4 bg-white mb-4">
                    <div class="panel-heading mb-3">
                        <div><span class="section-kicker text-primary fw-bold small text-uppercase">Supplier directory</span><h2 class="h5 fw-bold mb-0">Add supply partner</h2></div>
                        <span class="text-muted small">Store every supplier detail in one place</span>
                    </div>
                    <form method="post" class="row g-3">
                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="action" value="supplier">
                        <div class="col-md-4"><label class="form-label fw-medium">Company / supplier name</label><input class="form-control" name="name" required placeholder="e.g. General Stationery Supply"></div>
                        <div class="col-md-4"><label class="form-label fw-medium">Contact person</label><input class="form-control" name="contact_person" placeholder="Account manager"></div>
                        <div class="col-md-4"><label class="form-label fw-medium">Phone</label><input class="form-control" name="phone" type="tel" placeholder="01700000000"></div>
                        <div class="col-md-4"><label class="form-label fw-medium">Email</label><input class="form-control" name="email" type="email" placeholder="supplier@example.com"></div>
                        <div class="col-md-4"><label class="form-label fw-medium">Opening payable balance</label><input class="form-control" name="opening_due" type="number" min="0" step=".01" value="0"><small class="text-muted">Amount already owed to this supplier</small></div>
                        <div class="col-md-4"><label class="form-label fw-medium">Address</label><input class="form-control" name="address" placeholder="Business address"></div>
                        <div class="col-12"><button class="btn btn-primary fw-semibold"><i class="fa-solid fa-building-circle-plus me-1"></i> Save supplier information</button></div>
                    </form>
                    <div class="table-responsive mt-4"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Supplier</th><th>Contact</th><th>Phone</th><th>Email</th><th>Opening due</th><th>Address</th></tr></thead><tbody>
                        <?php foreach ($suppliers ?? [] as $supplier): ?><tr><td><strong><?= e($supplier['name']) ?></strong></td><td><?= e($supplier['contact_person'] ?? '-') ?></td><td><?= e($supplier['phone'] ?? '-') ?></td><td><?= e($supplier['email'] ?? '-') ?></td><td>৳ <?= number_format((float)($supplier['opening_due'] ?? 0), 2) ?></td><td><?= e($supplier['address'] ?? '-') ?></td></tr><?php endforeach; ?>
                        <?php if (empty($suppliers)): ?><tr><td colspan="6" class="text-center py-4 text-muted">No supplier information has been added yet.</td></tr><?php endif; ?></tbody></table></div>
                </section>

            <?php elseif ($page === 'services'): ?>
                <div class="module-grid">
                    <section class="panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Operator desk</span><h2 class="h5 fw-bold mb-0">New service job</h2></div>
                        </div>
                        <form method="post" class="row g-3">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <div class="col-12">
                                <label class="form-label fw-medium">Service</label>
                                <select name="service_type_id" class="form-select" required>
                                    <?php foreach ($services ?? [] as $service): ?>
                                        <option value="<?= $service['id'] ?>" data-fee="<?= $service['default_fee'] ?>"><?= e($service['name']) ?> · ৳ <?= number_format((float)$service['default_fee'], 0) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Customer</label>
                                <input name="customer_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Phone</label>
                                <input name="customer_phone" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Task description</label>
                                <input name="task_description" class="form-control" required placeholder="Describe the work completed">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Fee</label>
                                <input name="fee" class="form-control" type="number" step=".01" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Payment</label>
                                <select name="payment_method" class="form-select">
                                    <option>cash</option>
                                    <option>bKash</option>
                                    <option>Nagad</option>
                                    <option>Rocket</option>
                                    <option>card</option>
                                </select>
                            </div>
                            <div class="col-12 pt-2">
                                <button class="btn btn-primary w-100 py-2 shadow-sm fw-semibold"><i class="fa-solid fa-check me-1"></i> Complete service</button>
                            </div>
                        </form>
                    </section>
                    <section class="panel wide-panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Operator performance</span><h2 class="h5 fw-bold mb-0">Recent work logs</h2></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr><th>Service</th><th>Customer</th><th>Operator</th><th>Fee</th><th>Date</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs ?? [] as $log): ?>
                                        <tr>
                                            <td><strong><?= e($log['service_name'] ?? 'Custom') ?></strong><small class="d-block text-muted"><?= e($log['task_description']) ?></small></td>
                                            <td><?= e($log['customer_name'] ?? 'Walk-in') ?></td>
                                            <td><?= e($log['full_name']) ?></td>
                                            <td>৳ <?= number_format((float)$log['fee'], 2) ?></td>
                                            <td><?= date('d M, h:i A', strtotime($log['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

            <?php elseif ($page === 'mfs'): ?>
                <section class="float-grid mb-4">
                    <?php foreach ($accounts ?? [] as $account): ?>
                        <article class="float-card provider-<?= strtolower($account['provider']) ?> shadow-sm rounded-4 p-3 bg-white border">
                            <span class="text-muted text-uppercase small fw-semibold"><?= e($account['provider']) ?></span>
                            <strong class="d-block fs-4 text-dark my-1">৳ <?= number_format((float)$account['current_float'], 2) ?></strong>
                            <small class="text-muted"><?= e($account['account_number']) ?> · Current float</small>
                        </article>
                    <?php endforeach; ?>
                </section>
                <div class="module-grid">
                    <section class="panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Digital finance</span><h2 class="h5 fw-bold mb-0">New transaction</h2></div>
                        </div>
                        <form method="post" class="row g-3">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <div class="col-12">
                                <label class="form-label fw-medium">Provider</label>
                                <select name="account_id" class="form-select">
                                    <?php foreach($accounts ?? [] as $account): ?>
                                        <option value="<?= $account['id'] ?>"><?= e($account['provider']) ?> · <?= e($account['account_number']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Transaction type</label>
                                <select name="transaction_type" class="form-select">
                                    <option value="cash_in">Cash In</option>
                                    <option value="cash_out">Cash Out</option>
                                    <option value="send_money">Send Money</option>
                                    <option value="merchant_payment">Merchant Payment</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Amount</label>
                                <input name="amount" class="form-control" type="number" min=".01" step=".01" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Commission</label>
                                <input name="commission" class="form-control" type="number" min="0" step=".01" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Reference</label>
                                <input name="reference_number" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Customer / note</label>
                                <input name="customer_name" class="form-control mb-2" placeholder="Customer name">
                                <input name="note" class="form-control" placeholder="Optional note">
                            </div>
                            <div class="col-12 pt-2">
                                <button class="btn btn-primary w-100 py-2 shadow-sm fw-semibold"><i class="fa-solid fa-plus me-1"></i> Record transaction</button>
                            </div>
                        </form>
                    </section>
                    <section class="panel wide-panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Ledger</span><h2 class="h5 fw-bold mb-0">Recent transactions</h2></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr><th>Provider</th><th>Type</th><th>Amount</th><th>Commission</th><th>Reference</th><th>Date</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach($transactions ?? [] as $transaction): ?>
                                        <tr>
                                            <td><strong><?= e($transaction['provider']) ?></strong></td>
                                            <td><?= e(str_replace('_',' ', $transaction['transaction_type'])) ?></td>
                                            <td>৳ <?= number_format((float)$transaction['amount'],2) ?></td>
                                            <td>৳ <?= number_format((float)$transaction['commission'],2) ?></td>
                                            <td><?= e($transaction['reference_number'] ?? '-') ?></td>
                                            <td><?= date('d M, h:i A', strtotime($transaction['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

            <?php elseif ($page === 'utility'): ?>
                <div class="module-grid">
                    <section class="panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Collection desk</span><h2 class="h5 fw-bold mb-0">New bill payment</h2></div>
                        </div>
                        <form method="post" class="row g-3">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <div class="col-12">
                                <label class="form-label fw-medium">Utility vendor</label>
                                <select name="vendor_id" class="form-select">
                                    <?php foreach($vendors ?? [] as $vendor): ?>
                                        <option value="<?= $vendor['id'] ?>"><?= e($vendor['name']) ?> · <?= e($vendor['service_type']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Account / meter number</label>
                                <input name="account_number" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Customer</label>
                                <input name="customer_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Bill amount</label>
                                <input name="bill_amount" class="form-control" type="number" min="0" step=".01" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Service charge</label>
                                <input name="service_charge" class="form-control" type="number" min="0" step=".01" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Status</label>
                                <select name="status" class="form-select">
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Reference</label>
                                <input name="reference_number" class="form-control">
                            </div>
                            <div class="col-12 pt-2">
                                <button class="btn btn-primary w-100 py-2 shadow-sm fw-semibold"><i class="fa-solid fa-receipt me-1"></i> Save bill payment</button>
                            </div>
                        </form>
                    </section>
                    <section class="panel wide-panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Payment register</span><h2 class="h5 fw-bold mb-0">Recent bills</h2></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr><th>Vendor</th><th>Account</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach($payments ?? [] as $payment): ?>
                                        <tr>
                                            <td><strong><?= e($payment['vendor_name']) ?></strong><small class="d-block text-muted"><?= e($payment['service_type']) ?></small></td>
                                            <td><?= e($payment['account_number']) ?></td>
                                            <td><?= e($payment['customer_name'] ?? 'Walk-in') ?></td>
                                            <td>৳ <?= number_format((float)$payment['bill_amount'] + (float)$payment['service_charge'],2) ?></td>
                                            <td><span class="status-pill status-<?= e($payment['status']) ?> badge bg-<?= $payment['status'] === 'paid' ? 'success' : 'warning' ?>-subtle text-<?= $payment['status'] === 'paid' ? 'success' : 'warning' ?> px-2 py-1"><?= e($payment['status']) ?></span></td>
                                            <td><?= date('d M Y', strtotime($payment['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

            <?php elseif ($page === 'accounts'): ?>
                <?php $todayAccounts = $accountsOverview['today'] ?? []; $financialPosition = $accountsOverview['position'] ?? []; ?>
                <section class="accounts-hero">
                    <div><span class="section-kicker">Financial command center</span><h2>Today’s money movement</h2><p>Live income, outgoings, account balances, and a single financial activity trail.</p></div>
                    <div class="accounts-hero-actions"><a class="btn btn-light" href="?route=income"><i class="fa-solid fa-plus me-1"></i> Add income</a><a class="btn btn-light" href="?route=expenses"><i class="fa-solid fa-minus me-1"></i> Add expense</a><a class="btn btn-primary" href="?route=reports"><i class="fa-solid fa-chart-line me-1"></i> Full report</a></div>
                </section>
                <section class="accounts-kpis">
                    <article class="account-kpi income"><span>Today’s income</span><strong>৳ <?= number_format((float) ($todayAccounts['income'] ?? 0), 2) ?></strong><small>Sales, services, utility fees & other income</small></article>
                    <article class="account-kpi outgoing"><span>Today’s expenses</span><strong>৳ <?= number_format((float) ($todayAccounts['outgoing'] ?? 0), 2) ?></strong><small>Purchases, expenses & salary payments</small></article>
                    <article class="account-kpi <?= (float) ($todayAccounts['net'] ?? 0) < 0 ? 'negative' : 'net' ?>"><span>Net cash movement</span><strong>৳ <?= number_format((float) ($todayAccounts['net'] ?? 0), 2) ?></strong><small>Income less outgoing today</small></article>
                    <article class="account-kpi float"><span>Active MFS float</span><strong>৳ <?= number_format((float) ($financialPosition['mfs_float'] ?? 0), 2) ?></strong><small>Across active provider accounts</small></article>
                </section>
                <div class="accounts-grid">
                    <section class="panel account-breakdown">
                        <div class="panel-heading"><div><span class="section-kicker">Today’s breakdown</span><h2 class="h5 fw-bold mb-0">Income and outgoing</h2></div><span class="text-muted small"><?= date('d M Y') ?></span></div>
                        <div class="account-breakdown-list">
                            <div><span><i class="fa-solid fa-cash-register"></i> POS sales</span><strong>৳ <?= number_format((float) ($todayAccounts['sales'] ?? 0), 2) ?></strong></div>
                            <div><span><i class="fa-solid fa-print"></i> Service revenue</span><strong>৳ <?= number_format((float) ($todayAccounts['services'] ?? 0), 2) ?></strong></div>
                            <div><span><i class="fa-solid fa-receipt"></i> Utility service fees</span><strong>৳ <?= number_format((float) ($todayAccounts['utility_fees'] ?? 0), 2) ?></strong></div>
                            <div><span><i class="fa-solid fa-hand-holding-dollar"></i> Other income</span><strong>৳ <?= number_format((float) ($todayAccounts['other_income'] ?? 0), 2) ?></strong></div>
                            <div class="is-outgoing"><span><i class="fa-solid fa-truck-field"></i> Stock purchases</span><strong>− ৳ <?= number_format((float) ($todayAccounts['purchases'] ?? 0), 2) ?></strong></div>
                            <div class="is-outgoing"><span><i class="fa-solid fa-money-bill-wave"></i> Operating expenses</span><strong>− ৳ <?= number_format((float) ($todayAccounts['expenses'] ?? 0), 2) ?></strong></div>
                            <div class="is-outgoing"><span><i class="fa-solid fa-users-gear"></i> Salary payments</span><strong>− ৳ <?= number_format((float) ($todayAccounts['salaries'] ?? 0), 2) ?></strong></div>
                        </div>
                    </section>
                    <section class="panel account-position">
                        <div class="panel-heading"><div><span class="section-kicker">Financial position</span><h2 class="h5 fw-bold mb-0">Business balances</h2></div><a href="?route=mfs" class="small fw-semibold">Manage MFS</a></div>
                        <div class="position-card"><i class="fa-solid fa-mobile-screen-button"></i><span>MFS float</span><strong>৳ <?= number_format((float) ($financialPosition['mfs_float'] ?? 0), 2) ?></strong></div>
                        <div class="position-card"><i class="fa-solid fa-boxes-stacked"></i><span>Inventory at cost</span><strong>৳ <?= number_format((float) ($financialPosition['inventory_value'] ?? 0), 2) ?></strong></div>
                        <div class="position-card <?= (float) ($financialPosition['customer_due'] ?? 0) > 0 ? 'attention' : '' ?>"><i class="fa-solid fa-clock"></i><span>Customer balances due</span><strong>৳ <?= number_format((float) ($financialPosition['customer_due'] ?? 0), 2) ?></strong></div>
                    </section>
                </div>
                <section class="panel accounts-ledger">
                    <div class="panel-heading"><div><span class="section-kicker">All accounts</span><h2 class="h5 fw-bold mb-0">Financial activity</h2></div><span class="text-muted small">Latest 80 entries</span></div>
                    <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Date & time</th><th>Type</th><th>Description</th><th class="text-end">Money in</th><th class="text-end">Money out</th></tr></thead><tbody>
                        <?php foreach ($accountsOverview['ledger'] ?? [] as $entry): ?><tr><td class="text-muted small"><?= date('d M Y, h:i A', strtotime($entry['occurred_at'])) ?></td><td><span class="ledger-type <?= (float) $entry['outflow'] > 0 ? 'out' : 'in' ?>"><?= e($entry['entry_type']) ?></span></td><td><strong><?= e($entry['description']) ?></strong></td><td class="text-end text-success fw-semibold"><?= (float) $entry['inflow'] > 0 ? '৳ ' . number_format((float) $entry['inflow'], 2) : '—' ?></td><td class="text-end text-danger fw-semibold"><?= (float) $entry['outflow'] > 0 ? '৳ ' . number_format((float) $entry['outflow'], 2) : '—' ?></td></tr><?php endforeach; ?>
                        <?php if (empty($accountsOverview['ledger'])): ?><tr><td colspan="5" class="text-center text-muted py-5">No financial activity has been recorded yet.</td></tr><?php endif; ?>
                    </tbody></table></div>
                </section>

            <?php elseif ($page === 'reports'): ?>
                <section class="panel shadow-sm rounded-4 p-4 bg-white border-0 mb-4">
                    <form method="get" class="row g-3 align-items-end">
                        <input type="hidden" name="route" value="reports">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">From</label>
                            <input class="form-control" type="date" name="start_date" value="<?= e($report['start_date'] ?? date('Y-m-d', strtotime('-29 days'))) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">To</label>
                            <input class="form-control" type="date" name="end_date" value="<?= e($report['end_date'] ?? date('Y-m-d')) ?>">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button class="btn btn-primary flex-grow-1"><i class="fa-solid fa-filter me-1"></i>Apply range</button>
                            <a class="btn btn-outline-success" href="?route=reports&amp;start_date=<?= urlencode((string) ($report['start_date'] ?? '')) ?>&amp;end_date=<?= urlencode((string) ($report['end_date'] ?? '')) ?>&amp;export=excel" title="Export the complete report to Excel"><i class="fa-solid fa-file-excel me-1"></i>Excel</a>
                            <button type="button" class="btn btn-outline-secondary" onclick="window.print()" title="Print report"><i class="fa-solid fa-print"></i></button>
                        </div>
                    </form>
                </section>
                <section class="report-kpis mb-4">
                    <article class="kpi-card accent-teal shadow-sm rounded-4 p-4 bg-white border"><p class="text-muted small mb-1">Sales</p><h3 class="fw-bold mb-0">৳ <?= number_format($report['sales'] ?? 0, 0) ?></h3><small class="text-muted"><?= e($report['start_date'] ?? '') ?> to <?= e($report['end_date'] ?? '') ?></small></article>
                    <article class="kpi-card accent-coral shadow-sm rounded-4 p-4 bg-white border"><p class="text-muted small mb-1">Purchases</p><h3 class="fw-bold mb-0">৳ <?= number_format($report['purchases'] ?? 0, 0) ?></h3><small class="text-muted">Stock investment</small></article>
                    <article class="kpi-card accent-amber shadow-sm rounded-4 p-4 bg-white border"><p class="text-muted small mb-1">Gross profit</p><h3 class="fw-bold mb-0">৳ <?= number_format($report['profit'] ?? 0, 0) ?></h3></article>
                    <article class="kpi-card accent-blue shadow-sm rounded-4 p-4 bg-white border"><p class="text-muted small mb-1">Other revenue</p><h3 class="fw-bold mb-0">৳ <?= number_format(($report['services'] ?? 0) + ($report['utility'] ?? 0) + ($report['income'] ?? 0), 0) ?></h3><small class="text-muted">Services, utility, income</small></article>
                    <article class="kpi-card accent-teal shadow-sm rounded-4 p-4 bg-white border"><p class="text-muted small mb-1">Net operating result</p><h3 class="fw-bold mb-0">৳ <?= number_format($report['net'] ?? 0, 0) ?></h3></article>
                </section>
                <div class="row g-4 mb-4">
                    <section class="col-xl-7">
                        <div class="panel shadow-sm rounded-4 p-4 bg-white h-100">
                            <div class="panel-heading mb-3"><span class="section-kicker text-primary fw-bold small text-uppercase">Performance</span><h2 class="h5 fw-bold mb-0">Top products</h2></div>
                            <div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>Product</th><th>Units</th><th>Revenue</th><th>Share</th></tr></thead><tbody>
                                <?php foreach($report['top_products'] ?? [] as $product): ?><tr><td><strong><?= e($product['name']) ?></strong></td><td><?= number_format((float)$product['quantity'], 0) ?></td><td>৳ <?= number_format((float)$product['revenue'], 2) ?></td><td><div class="report-bar bg-light rounded-pill overflow-hidden" style="height: 8px;"><span class="bg-primary d-block h-100 rounded-pill" style="width:<?= min(100, ((float)$product['revenue'] / max(1, (float)($report['sales'] ?? 1))) * 100) ?>%"></span></div></td></tr><?php endforeach; ?>
                                <?php if (!($report['top_products'] ?? [])): ?><tr><td colspan="4" class="text-center py-4 text-muted">No sales data for this period.</td></tr><?php endif; ?></tbody></table></div>
                        </div>
                    </section>
                    <section class="col-xl-5">
                        <div class="panel shadow-sm rounded-4 p-4 bg-white h-100"><div class="panel-heading mb-3"><span class="section-kicker text-primary fw-bold small text-uppercase">Tender mix</span><h2 class="h5 fw-bold mb-0">Sales by payment method</h2></div><div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>Method</th><th>Orders</th><th>Amount</th></tr></thead><tbody><?php foreach($report['payment_mix'] ?? [] as $payment): ?><tr><td><?= e(ucfirst($payment['payment_method'])) ?></td><td><?= number_format((int)$payment['transactions']) ?></td><td>৳ <?= number_format((float)$payment['amount'], 2) ?></td></tr><?php endforeach; ?><?php if (!($report['payment_mix'] ?? [])): ?><tr><td colspan="3" class="text-center py-4 text-muted">No payments for this period.</td></tr><?php endif; ?></tbody></table></div></div>
                    </section>
                </div>
                <div class="row g-4">
                    <section class="col-xl-7"><div class="panel shadow-sm rounded-4 p-4 bg-white h-100"><div class="panel-heading mb-3"><span class="section-kicker text-primary fw-bold small text-uppercase">Staff management</span><h2 class="h5 fw-bold mb-0">Sales by operator</h2></div><div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>Staff member</th><th>Transactions</th><th>Revenue</th></tr></thead><tbody><?php foreach($report['staff_activity'] ?? [] as $staff): ?><tr><td><strong><?= e($staff['full_name']) ?></strong></td><td><?= number_format((int)$staff['transactions']) ?></td><td>৳ <?= number_format((float)$staff['revenue'], 2) ?></td></tr><?php endforeach; ?><?php if (!($report['staff_activity'] ?? [])): ?><tr><td colspan="3" class="text-center py-4 text-muted">No staff activity for this period.</td></tr><?php endif; ?></tbody></table></div></div></section>
                    <section class="col-xl-5"><div class="panel shadow-sm rounded-4 p-4 bg-white h-100"><div class="panel-heading mb-3"><span class="section-kicker text-danger fw-bold small text-uppercase">Inventory control</span><h2 class="h5 fw-bold mb-0">Low-stock watchlist</h2></div><div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>Product</th><th>SKU</th><th>On hand</th></tr></thead><tbody><?php foreach($report['low_stock'] ?? [] as $item): ?><tr><td><?= e($item['name']) ?></td><td><?= e($item['sku']) ?></td><td class="text-danger fw-semibold"><?= number_format((float)$item['stock_quantity'], 0) ?></td></tr><?php endforeach; ?><?php if (!($report['low_stock'] ?? [])): ?><tr><td colspan="3" class="text-center py-4 text-muted">Stock levels look healthy.</td></tr><?php endif; ?></tbody></table></div></div></section>
                </div>

            <?php elseif ($page === 'mfs'): ?>
                <?php $renderModule('mfs'); ?>

            <?php elseif ($page === 'returns'): ?>
                <?php $renderModule('returns'); ?>

            <?php elseif ($page === 'salaries'): ?>
                <?php $renderModule('salaries'); ?>

            <?php elseif ($page === 'income'): ?>
                <?php $renderModule('income'); ?>

            <?php elseif ($page === 'expenses'): ?>
                <?php $renderModule('expenses'); ?>

            <?php elseif ($page === 'users'): ?>
                <?php $renderModule('users'); ?>

            <?php elseif ($page === 'vouchers'): ?>
                <?php $renderModule('vouchers'); ?>

            <?php endif; ?>
        </div>

        <!-- App Footer -->
        <footer class="app-footer px-4 py-3 bg-white border-top text-muted small d-flex justify-content-between align-items-center mt-auto">
            <span>© <?= date('Y') ?> Iqra Stationary Solutions</span>
            <span>System status <i class="fa-solid fa-circle text-success ms-1" style="font-size: 8px;"></i> All systems operational</span>
        </footer>
    </main>

    <script>window.IQRA_APP_URL = <?= json_encode(APP_URL) ?>;</script>
    <script src="<?= e(APP_URL) ?>/assets/js/app.js"></script>
    
    <!-- Product Label Printing Modal -->
    <div class="modal fade" id="labelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Print Product Labels</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Products</label>
                        <select class="form-select" id="labelProductSelect" multiple size="5">
                            <?php foreach ($products ?? [] as $product): ?>
                                <option value="<?= htmlspecialchars(json_encode($product)) ?>">
                                    <?= e($product['name']) ?> - <?= e($product['sku']) ?> (৳<?= number_format((float)$product['selling_price'], 2) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple products</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Number of Labels per Product</label>
                        <input type="number" class="form-control" id="labelQuantity" value="1" min="1" max="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Label Size</label>
                        <select class="form-select" id="labelSize">
                            <option value="small">Small (50mm x 30mm)</option>
                            <option value="medium" selected>Medium (70mm x 40mm)</option>
                            <option value="large">Large (100mm x 50mm)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="printSelectedLabels()">
                        <i class="fa-solid fa-print me-1"></i> Print Labels
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openLabelModal() {
            var modal = new bootstrap.Modal(document.getElementById('labelModal'));
            modal.show();
        }

        function printSingleLabel(product) {
            printLabels([product], 1, 'medium');
        }

        function printSelectedLabels() {
            var select = document.getElementById('labelProductSelect');
            var selectedProducts = [];
            for (var i = 0; i < select.options.length; i++) {
                if (select.options[i].selected) {
                    selectedProducts.push(JSON.parse(select.options[i].value));
                }
            }
            
            if (selectedProducts.length === 0) {
                alert('Please select at least one product');
                return;
            }

            var quantity = parseInt(document.getElementById('labelQuantity').value) || 1;
            var size = document.getElementById('labelSize').value;
            
            var modal = bootstrap.Modal.getInstance(document.getElementById('labelModal'));
            modal.hide();
            
            printLabels(selectedProducts, quantity, size);
        }

        function printLabels(products, quantity, size) {
            var dimensions = {
                small: { width: '50mm', height: '35mm', titleSize: '11px', skuSize: '9px', priceSize: '14px' },
                medium: { width: '70mm', height: '45mm', titleSize: '13px', skuSize: '10px', priceSize: '16px' },
                large: { width: '100mm', height: '60mm', titleSize: '15px', skuSize: '11px', priceSize: '18px' }
            };
            
            var dim = dimensions[size] || dimensions.medium;
            var labelsHtml = '';
            
            products.forEach(function(product) {
                for (var i = 0; i < quantity; i++) {
                    labelsHtml += `
                        <div class="product-label" style="
                            width: ${dim.width};
                            height: ${dim.height};
                            border: 2px solid #087f73;
                            border-radius: 8px;
                            padding: 8px;
                            margin: 5px;
                            display: inline-block;
                            font-family: 'DM Sans', Arial, sans-serif;
                            page-break-inside: avoid;
                            box-sizing: border-box;
                            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                            box-shadow: 0 2px 8px rgba(8, 127, 115, 0.15);
                            position: relative;
                            overflow: hidden;
                        ">
                            <!-- Brand Header -->
                            <div style="
                                display: flex;
                                align-items: center;
                                gap: 6px;
                                margin-bottom: 6px;
                                padding-bottom: 6px;
                                border-bottom: 1px dashed #087f73;
                            ">
                                <div style="
                                    width: 20px;
                                    height: 20px;
                                    background: #087f73;
                                    color: white;
                                    border-radius: 4px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 10px;
                                    font-weight: bold;
                                ">I</div>
                                <div style="
                                    font-size: 10px;
                                    font-weight: 700;
                                    color: #087f73;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                ">Iqra Stationary</div>
                            </div>
                            
                            <!-- Product Name -->
                            <div style="
                                font-weight: 700;
                                font-size: ${dim.titleSize};
                                color: #0f3332;
                                margin-bottom: 4px;
                                line-height: 1.2;
                                min-height: 1.2em;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                display: -webkit-box;
                                -webkit-line-clamp: 2;
                                -webkit-box-orient: vertical;
                            ">${product.name}</div>
                            
                            <!-- SKU -->
                            <div style="
                                font-size: ${dim.skuSize};
                                color: #64748b;
                                margin-bottom: 6px;
                                font-weight: 500;
                                background: #f1f5f9;
                                padding: 2px 6px;
                                border-radius: 4px;
                                display: inline-block;
                            ">SKU: ${product.sku}</div>
                            
                            <!-- Price Section -->
                            <div style="
                                margin-top: auto;
                                display: flex;
                                align-items: baseline;
                                justify-content: space-between;
                                gap: 4px;
                            ">
                                <div style="
                                    font-size: ${dim.priceSize};
                                    font-weight: 800;
                                    color: #087f73;
                                    font-family: 'Outfit', Arial, sans-serif;
                                ">৳${parseFloat(product.selling_price).toFixed(2)}</div>
                                ${product.barcode ? `<div style="font-size: 8px; color: #94a3b8; max-width: 60px; overflow: hidden; text-overflow: ellipsis;">${product.barcode}</div>` : ''}
                            </div>
                            
                            <!-- Decorative Corner -->
                            <div style="
                                position: absolute;
                                top: 0;
                                right: 0;
                                width: 0;
                                height: 0;
                                border-style: solid;
                                border-width: 0 15px 15px 0;
                                border-color: transparent #087f73 transparent transparent;
                            "></div>
                        </div>
                    `;
                }
            });
            
            // Create a new window for printing
            var printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Print Labels</title>
                    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
                    <style>
                        @page {
                            size: auto;
                            margin: 8mm;
                        }
                        body {
                            margin: 0;
                            padding: 0;
                            font-family: 'DM Sans', Arial, sans-serif;
                            background: #f8fafc;
                        }
                        .labels-container {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 8px;
                            padding: 10px;
                        }
                        @media print {
                            body {
                                background: white;
                            }
                            .labels-container {
                                gap: 5px;
                                padding: 0;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="labels-container">
                        ${labelsHtml}
                    </div>
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.print();
                                window.onafterprint = function() {
                                    window.close();
                                };
                            }, 500);
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
</body>
</html>
