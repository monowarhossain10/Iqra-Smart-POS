<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MFS Ledger | <?= e(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= e(APP_URL) ?>/assets/css/app.css" rel="stylesheet">
    <link href="<?= e(APP_URL) ?>/assets/css/modules.css" rel="stylesheet">
</head>
<body class="app-body">
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-mark">
                <i class="fa-solid fa-feather-pointed"></i>
            </div>
            <div>
                <strong>iqra</strong>
                <span>stationary solutions</span>
            </div>
        </div>

        <div class="sidebar-label">Workspace</div>
        <nav class="nav flex-column gap-1">
            <a class="nav-link" href="<?= e(APP_URL) ?>"><i class="fa-solid fa-grid-2"></i>Overview</a>
            <a class="nav-link" href="?route=pos"><i class="fa-solid fa-cash-register"></i>Point of sale</a>
            <a class="nav-link" href="?route=inventory"><i class="fa-solid fa-boxes-stacked"></i>Inventory</a>
            <a class="nav-link" href="?route=purchases"><i class="fa-solid fa-truck-field"></i>Purchases</a>
            <a class="nav-link" href="?route=services"><i class="fa-solid fa-print"></i>Service billing</a>
            <a class="nav-link" href="?route=returns"><i class="fa-solid fa-arrow-rotate-left"></i>Product returns</a>
        </nav>

        <div class="sidebar-label mt-4">Financials</div>
        <nav class="nav flex-column gap-1">
            <a class="nav-link active" href="?route=mfs"><i class="fa-solid fa-mobile-screen-button"></i>MFS ledger</a>
            <a class="nav-link" href="?route=utility"><i class="fa-solid fa-receipt"></i>Utility bills</a>
            <a class="nav-link" href="?route=income"><i class="fa-solid fa-hand-holding-dollar"></i>Income records</a>
            <a class="nav-link" href="?route=reports"><i class="fa-solid fa-chart-line"></i>Reports</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="module-heading">
                <button class="mobile-menu" id="menuToggle"><i class="fa-solid fa-bars"></i></button>
                <div>
                    <p class="eyebrow mb-1">Financial controls</p>
                    <h1><i class="fa-solid fa-mobile-screen-button module-title-icon"></i>MFS Ledger</h1>
                    <p class="module-subtitle">Provider accounts, daily float and reconciliation</p>
                </div>
            </div>
            <div class="profile">
                <div class="avatar">IS</div>
                <div class="d-none d-md-block">
                    <strong><?= e(Auth::user()['name']) ?></strong>
                    <small><?= e(Auth::user()['role']) ?></small>
                </div>
                <a href="?route=logout" class="logout-link" title="Logout"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
            </div>
        </header>

        <!-- Alerts -->
        <?php if ($error): ?>
            <div class="alert alert-danger module-alert"><?= e($error) ?></div>
        <?php elseif ($flash): ?>
            <div class="alert alert-success module-alert"><?= e($flash) ?></div>
        <?php endif; ?>

        <!-- Float Grid Cards -->
        <section class="float-grid">
            <?php foreach ($accounts as $account): ?>
                <article class="float-card provider-<?= strtolower($account['provider']) ?>">
                    <span><?= e($account['provider']) ?></span>
                    <strong>৳ <?= number_format((float)$account['current_float'], 2) ?></strong>
                    <small><?= e($account['account_number']) ?> · Current float</small>
                </article>
            <?php endforeach; ?>
        </section>

        <!-- Module Grid (Forms & Settings) -->
        <div class="module-grid">
            <!-- Transaction Voucher Panel -->
            <section class="panel">
                <div class="panel-heading">
                    <div>
                        <span class="section-kicker">Transaction voucher</span>
                        <h2>Record MFS transaction</h2>
                    </div>
                </div>
                <form method="post" class="row g-3">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="action" value="transaction">
                    
                    <div class="col-12">
                        <label class="form-label">Provider account</label>
                        <select name="account_id" class="form-select">
                            <?php foreach($accounts as $account): ?>
                                <option value="<?= $account['id'] ?>"><?= e($account['provider']) ?> · <?= e($account['account_number']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select name="transaction_type" class="form-select">
                            <option value="cash_in">Cash In</option>
                            <option value="cash_out">Cash Out</option>
                            <option value="send_money">Send Money</option>
                            <option value="merchant_payment">Merchant Payment</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Amount</label>
                        <input name="amount" class="form-control" type="number" min=".01" step=".01" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Commission</label>
                        <input name="commission" class="form-control" type="number" min="0" step=".01" value="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Reference</label>
                        <input name="reference_number" class="form-control" placeholder="Optional ref">
                    </div>

                    <div class="col-12">
                        <input name="customer_name" class="form-control mb-2" placeholder="Customer name">
                        <input name="note" class="form-control" placeholder="Note">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary w-100"><i class="fa-solid fa-receipt me-1"></i> Save and print voucher</button>
                    </div>
                </form>
            </section>

            <!-- Account Settings Panel -->
            <section class="panel">
                <div class="panel-heading">
                    <div>
                        <span class="section-kicker">Account settings</span>
                        <h2>Update provider number</h2>
                    </div>
                </div>
                <?php foreach($accounts as $account): ?>
                    <form method="post" class="account-form mb-3">
                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="action" value="account">
                        <input type="hidden" name="account_id" value="<?= $account['id'] ?>">
                        <div class="account-row">
                            <strong><?= e($account['provider']) ?></strong>
                            <input class="form-control" name="account_number" value="<?= e($account['account_number']) ?>" required>
                            <input type="hidden" name="provider" value="<?= e($account['provider']) ?>">
                            <input type="hidden" name="opening_float" value="<?= e($account['opening_float']) ?>">
                            <input type="hidden" name="current_float" value="<?= e($account['current_float']) ?>">
                            <button class="btn btn-soft" title="Update account number"><i class="fa-solid fa-floppy-disk"></i></button>
                        </div>
                    </form>
                <?php endforeach; ?>
            </section>
        </div>

        <!-- Daily Reconciliation Panel -->
        <section class="panel reconciliation-panel">
            <div class="panel-heading">
                <div>
                    <span class="section-kicker">Daily control</span>
                    <h2>Opening and closing reconciliation</h2>
                </div>
                <span class="keyboard-hint">Business date: <?= date('d M Y') ?></span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Provider</th>
                            <th>Opening float</th>
                            <th>Today net</th>
                            <th>Expected closing</th>
                            <th>Actual closing</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dailyAccounts as $account): ?>
                            <tr>
                                <td>
                                    <strong><?= e($account['provider']) ?></strong>
                                    <small><?= e($account['account_number']) ?></small>
                                </td>
                                <td>৳ <?= number_format($account['opening_float'], 2) ?></td>
                                <td class="<?= $account['daily_net'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <?= $account['daily_net'] >= 0 ? '+' : '' ?>৳ <?= number_format($account['daily_net'], 2) ?>
                                </td>
                                <td><strong>৳ <?= number_format($account['expected_closing'], 2) ?></strong></td>
                                <td>
                                    <form method="post" class="reconcile-form">
                                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="action" value="reconcile">
                                        <input type="hidden" name="account_id" value="<?= $account['id'] ?>">
                                        <input type="hidden" name="business_date" value="<?= date('Y-m-d') ?>">
                                        <input type="hidden" name="opening_float" value="<?= $account['opening_float'] ?>">
                                        <input type="hidden" name="expected_closing" value="<?= $account['expected_closing'] ?>">
                                        <input class="form-control" name="actual_closing" type="number" step=".01" value="<?= $account['expected_closing'] ?>" required>
                                </td>
                                <td>
                                        <input class="form-control mb-1" name="notes" placeholder="Notes">
                                        <button class="btn btn-primary btn-sm w-100">Reconcile</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Audit Trail / Recent Reconciliations -->
        <section class="panel">
            <div class="panel-heading">
                <div>
                    <span class="section-kicker">Audit trail</span>
                    <h2>Recent reconciliations</h2>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Provider</th>
                            <th>Expected</th>
                            <th>Actual</th>
                            <th>Difference</th>
                            <th>By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($reconciliations as $row): ?>
                            <tr>
                                <td><?= e($row['business_date']) ?></td>
                                <td><?= e($row['provider']) ?></td>
                                <td>৳ <?= number_format($row['expected_closing'], 2) ?></td>
                                <td>৳ <?= number_format($row['actual_closing'], 2) ?></td>
                                <td class="<?= (float)$row['difference'] == 0 ? 'text-success' : 'text-danger' ?>">
                                    ৳ <?= number_format($row['difference'], 2) ?>
                                </td>
                                <td><?= e($row['full_name']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$reconciliations): ?>
                            <tr>
                                <td colspan="6" class="empty-state text-center py-4">No reconciliations recorded yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Footer -->
        <footer class="app-footer">
            <span>© <?= date('Y') ?> Iqra Stationary Solutions</span>
            <span>Daily financial controls enabled</span>
        </footer>
    </main>

    <script>window.IQRA_APP_URL = <?= json_encode(APP_URL) ?>;</script>
    <script src="<?= e(APP_URL) ?>/assets/js/app.js"></script>
</body>
</html>