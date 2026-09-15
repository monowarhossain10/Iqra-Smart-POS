<?php
$page ??= 'dashboard';
$meta = [
    'dashboard' => ['Overview', 'Welcome to your operational workspace', 'fa-grid-2'],
    'pos'       => ['Point of Sale', 'Fast counter checkout & billing', 'fa-cash-register'],
    'inventory' => ['Inventory', 'Products and stock control', 'fa-boxes-stacked'],
    'purchases' => ['Purchases', 'Receive stock from suppliers', 'fa-truck-field'],
    'services'  => ['Service Billing', 'Operator work and digital services', 'fa-print'],
    'mfs'       => ['MFS Ledger', 'Track mobile financial services', 'fa-mobile-screen-button'],
    'utility'   => ['Utility Bills', 'Bill collection and payments', 'fa-receipt'],
    'utf'       => ['Utility Bills', 'Bill collection and payments', 'fa-receipt'],
    'income'    => ['Income Records', 'Track business income streams', 'fa-hand-holding-dollar'],
    'expenses'  => ['Expenses', 'Track business operating expenses', 'fa-money-bill-wave'],
    'reports'   => ['Reports', 'Financial and operational insights', 'fa-chart-line'],
    'users'     => ['User Management', 'Manage system users and permissions', 'fa-users'],
    'forbidden' => ['Access restricted', 'Your role cannot open this workspace', 'fa-lock'],
][$page] ?? ['Workspace', '', 'fa-grid-2'];
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
</head>
<body class="app-body">
    <!-- Sidebar Navigation -->
    <aside class="sidebar shadow-sm" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-mark shadow-sm">
                <i class="fa-solid fa-feather-pointed"></i>
            </div>
            <div>
                <strong>iqra</strong>
                <span>stationary solutions</span>
            </div>
        </div>
        <nav class="nav flex-column gap-1 px-2">
            <a class="nav-link <?= $page === 'dashboard' ? 'active' : '' ?>" href="<?= e(APP_URL) ?>"><i class="fa-solid fa-grid-2 me-2"></i>Overview</a>
            <a class="nav-link <?= $page === 'pos' ? 'active' : '' ?>" href="?route=pos"><i class="fa-solid fa-cash-register me-2"></i>Point of sale <span class="nav-badge">F2</span></a>
            <a class="nav-link <?= $page === 'inventory' ? 'active' : '' ?>" href="?route=inventory"><i class="fa-solid fa-boxes-stacked me-2"></i>Inventory</a>
            <a class="nav-link <?= $page === 'purchases' ? 'active' : '' ?>" href="?route=purchases"><i class="fa-solid fa-truck-field me-2"></i>Purchases</a>
            <a class="nav-link <?= $page === 'services' ? 'active' : '' ?>" href="?route=services"><i class="fa-solid fa-print me-2"></i>Service billing</a>
            <a class="nav-link <?= $page === 'returns' ? 'active' : '' ?>" href="?route=returns"><i class="fa-solid fa-arrow-rotate-left me-2"></i>Product returns</a>
            <a class="nav-link <?= $page === 'mfs' ? 'active' : '' ?>" href="?route=mfs"><i class="fa-solid fa-mobile-screen-button me-2"></i>MFS ledger</a>
            <a class="nav-link <?= $page === 'utility' ? 'active' : '' ?>" href="?route=utility"><i class="fa-solid fa-receipt me-2"></i>Utility bills</a>
            <a class="nav-link <?= $page === 'income' ? 'active' : '' ?>" href="?route=income"><i class="fa-solid fa-hand-holding-dollar me-2"></i>Income records</a>
            <a class="nav-link <?= $page === 'expenses' ? 'active' : '' ?>" href="?route=expenses"><i class="fa-solid fa-money-bill-wave me-2"></i>Expenses</a>
            <a class="nav-link <?= $page === 'reports' ? 'active' : '' ?>" href="?route=reports"><i class="fa-solid fa-chart-line me-2"></i>Reports</a>
            <a class="nav-link <?= $page === 'salaries' ? 'active' : '' ?>" href="?route=salaries"><i class="fa-solid fa-users-gear me-2"></i>Employee salaries</a>
            <a class="nav-link <?= $page === 'users' ? 'active' : '' ?>" href="?route=users"><i class="fa-solid fa-users me-2"></i>User management</a>
        </nav>
        <div class="sidebar-bottom p-3">
            <div class="help-box p-3 rounded-3 bg-light border">
                <i class="fa-regular fa-circle-question fs-5 text-muted"></i>
                <div>
                    <strong>Need a hand?</strong>
                    <small class="d-block text-muted">Use quick actions</small>
                </div>
            </div>
        </div>
    </aside>

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
                                    <tr><th>Product</th><th>SKU</th><th>Category</th><th>Buying</th><th>Selling</th><th>Stock</th></tr>
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
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

            <?php elseif ($page === 'pos'): ?>
                <?php $lowStockCount = count(array_filter($products ?? [], static fn ($product) => (float) $product['stock_quantity'] <= (float) $product['low_stock_threshold'])); ?>
                <section class="pos-stats">
                    <div><span>Catalog</span><strong><?= number_format(count($products ?? [])) ?></strong><small>active products</small></div>
                    <div><span>Low stock</span><strong class="text-danger"><?= number_format($lowStockCount) ?></strong><small>need attention</small></div>
                    <div><span>Recent sales</span><strong><?= number_format(count($sales ?? [])) ?></strong><small>latest transactions</small></div>
                    <div><span>Shortcut</span><strong>F2</strong><small>focus product search</small></div>
                </section>
                <div class="pos-layout">
                    <section class="panel pos-products shadow-sm rounded-4 p-4 bg-white">
                        <div class="pos-toolbar">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Counter sales</span><h2 class="h5 fw-bold mb-0">Build an order</h2></div>
                            <div class="pos-search-box"><i class="fa-solid fa-magnifying-glass"></i><input id="posProductSearch" type="search" placeholder="Search name, SKU or scan barcode..." autocomplete="off"><kbd>F2</kbd></div>
                        </div>
                        <div class="pos-categories" role="toolbar" aria-label="Product categories">
                            <button type="button" class="category-pill active" data-category="all">All products</button>
                            <?php $categories = array_values(array_unique(array_filter(array_map(static fn ($product) => $product['category_name'] ?? null, $products ?? [])))); ?>
                            <?php foreach ($categories as $category): ?><button type="button" class="category-pill" data-category="<?= e(strtolower($category)) ?>"><?= e($category) ?></button><?php endforeach; ?>
                        </div>
                        <div class="product-list" id="posProducts">
                            <?php foreach ($products ?? [] as $product): ?>
                                <button type="button" class="product-tile shadow-xs border rounded-3 p-3 text-start bg-white" <?= (float) $product['stock_quantity'] <= 0 ? 'disabled' : '' ?> data-category="<?= e(strtolower($product['category_name'] ?? 'uncategorized')) ?>" data-product='<?= e(json_encode(['id'=>(int)$product['id'],'name'=>$product['name'],'sku'=>$product['sku'],'price'=>(float)$product['selling_price'],'stock'=>(float)$product['stock_quantity']])) ?>'>
                                    <span class="product-tile-icon text-primary mb-2 d-inline-block fs-5"><i class="fa-solid fa-box"></i></span>
                                    <strong class="d-block text-dark text-truncate"><?= e($product['name']) ?></strong>
                                    <small class="d-block text-muted mb-1"><?= e($product['sku']) ?> · <span class="<?= (float) $product['stock_quantity'] <= (float) $product['low_stock_threshold'] ? 'text-danger' : 'text-success' ?>"><?= number_format((float) $product['stock_quantity'], 0) ?> available</span></small>
                                    <b class="text-success">৳ <?= number_format((float) $product['selling_price'], 2) ?></b>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <section class="panel cart-panel shadow-sm rounded-4 p-4 bg-white">
                        <div class="panel-heading d-flex justify-content-between align-items-center mb-3">
                            <div><span class="section-kicker text-primary fw-bold small text-uppercase">Current order</span><h2 class="h5 fw-bold mb-0">Cart <span class="count-badge badge bg-primary" id="cartCount">0</span></h2></div>
                            <button type="button" class="clear-cart btn btn-outline-danger btn-sm" id="clearCart">Clear</button>
                        </div>
                        <div id="cartItems" class="cart-items border rounded-3 p-3 mb-3 bg-light overflow-auto" data-initial-cart='<?= e(json_encode($resumeHold['cart'] ?? [])) ?>'>
                            <div class="empty-state compact text-center text-muted py-4"><i class="fa-solid fa-basket-shopping fs-3 mb-2"></i><br>Select an item to begin</div>
                        </div>
                        <form method="post" id="saleForm" class="cart-form">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="cart" id="cartInput">
                            <input type="hidden" name="hold_id" value="<?= (int) ($resumeHold['id'] ?? 0) ?>">
                            <label class="form-label fw-medium">Customer name <span class="optional text-muted small">Optional</span></label>
                            <input class="form-control mb-2" name="customer_name" placeholder="Walk-in customer" value="<?= e($resumeHold['customer_name'] ?? '') ?>">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-medium">Discount</label>
                                    <input class="form-control" id="saleDiscount" name="discount" type="number" min="0" step=".01" value="<?= e($resumeHold['discount'] ?? '0') ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-medium">Payment</label>
                                    <select class="form-select" name="payment_method">
                                        <?php foreach (['cash', 'bKash', 'Nagad', 'Rocket', 'card', 'mixed'] as $paymentMethod): ?><option <?= ($resumeHold['payment_method'] ?? 'cash') === $paymentMethod ? 'selected' : '' ?>><?= e($paymentMethod) ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-medium">Amount tendered</label>
                                    <input class="form-control" id="paidAmount" name="paid_amount" type="number" min="0" step=".01" placeholder="Optional" value="<?= e($resumeHold['paid_amount'] ?? '') ?>">
                                </div>
                                <div class="col-12"><label class="form-label fw-medium">Hold note <span class="optional text-muted small">Optional</span></label><input class="form-control" name="hold_note" placeholder="Waiting for customer confirmation" value="<?= e($resumeHold['note'] ?? '') ?>"></div>
                            </div>
                            <div class="cart-summary p-3 rounded-3 bg-light mb-3">
                                <div><span>Subtotal</span><strong id="cartSubtotal">৳ 0.00</strong></div>
                                <div><span>Discount</span><strong id="cartDiscount">৳ 0.00</strong></div>
                                <div class="cart-total"><span>Total due</span><strong id="cartTotal">৳ 0.00</strong></div>
                                <div class="change-due"><span>Change</span><strong id="changeDue">৳ 0.00</strong></div>
                            </div>
                            <div class="pos-checkout-actions"><button class="btn btn-outline-warning py-2 fw-semibold" type="submit" name="action" value="hold"><i class="fa-solid fa-pause me-1"></i> Hold bill</button><button class="btn btn-primary py-2 shadow-sm fw-semibold" id="completeSale" type="submit"><i class="fa-solid fa-receipt me-1"></i> Complete sale</button></div>
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
                    <div class="panel-heading d-flex justify-content-between align-items-center mb-3"><div><span class="section-kicker text-primary fw-bold small text-uppercase">Activity</span><h2 class="h5 fw-bold mb-0">Recent sales</h2></div><a class="small fw-semibold" href="?route=reports">View reports <i class="fa-solid fa-arrow-right ms-1"></i></a></div>
                    <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Invoice</th><th>Customer</th><th>Operator</th><th>Payment</th><th>Total</th><th>Time</th></tr></thead><tbody>
                        <?php foreach (array_slice($sales ?? [], 0, 6) as $sale): ?><tr><td><strong><?= e($sale['invoice_number']) ?></strong></td><td><?= e($sale['customer_name'] ?? 'Walk-in customer') ?></td><td><?= e($sale['full_name']) ?></td><td><span class="badge bg-light text-dark border"><?= e($sale['payment_method']) ?></span></td><td><strong>৳ <?= number_format((float)$sale['total'], 2) ?></strong></td><td class="text-muted small"><?= date('d M, h:i A', strtotime($sale['created_at'])) ?></td></tr><?php endforeach; ?>
                        <?php if (empty($sales)): ?><tr><td colspan="6" class="text-center py-4 text-muted">No sales recorded yet.</td></tr><?php endif; ?></tbody></table></div>
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
                                <label class="form-label fw-medium">Product</label>
                                <select name="product_id" class="form-select" required>
                                    <?php foreach ($products ?? [] as $product): ?>
                                        <option value="<?= $product['id'] ?>"><?= e($product['name']) ?> (<?= e($product['sku']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Quantity</label>
                                <input class="form-control" name="quantity" type="number" min=".001" step=".001" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Unit cost</label>
                                <input class="form-control" name="unit_cost" type="number" min="0" step=".01" required>
                            </div>
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
                                    <tr><th>Order</th><th>Supplier</th><th>Total</th><th>Status</th><th>Date</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($purchases ?? [] as $purchase): ?>
                                        <tr>
                                            <td><strong><?= e($purchase['order_number']) ?></strong></td>
                                            <td><?= e($purchase['supplier_name']) ?></td>
                                            <td>৳ <?= number_format((float) $purchase['total'], 2) ?></td>
                                            <td><span class="status-pill status-paid badge bg-success-subtle text-success px-2 py-1"><?= e($purchase['status']) ?></span></td>
                                            <td><?= date('d M Y', strtotime($purchase['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

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
</body>
</html>