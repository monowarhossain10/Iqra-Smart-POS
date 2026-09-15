<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Returns | <?= e(APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= e(APP_URL) ?>/assets/css/app.css" rel="stylesheet">
    <link href="<?= e(APP_URL) ?>/assets/css/modules.css" rel="stylesheet">
</head>
<body style="margin: 0; background-color: #f4f6f9; font-family: 'DM Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1e293b;">

    <div style="display: flex; min-height: 100vh; background-color: #f4f6f9;">
        <!-- Sidebar / Slide Menu Bar -->
        <aside style="width: 280px; background: #ffffff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; box-shadow: 2px 0 5px rgba(0,0,0,0.02);">
            <div style="padding: 20px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #f1f5f9;">
                <div style="width: 40px; height: 40px; background: #2563eb; color: white; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 1.2rem;">
                    <i class="fa-solid fa-feather-pointed"></i>
                </div>
                <div>
                    <strong style="font-size: 1.1rem; text-transform: uppercase; color: #1e293b; display: block; letter-spacing: 0.5px;">iqra</strong>
                    <span style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">stationary solutions</span>
                </div>
            </div>
            
            <div style="flex: 1; overflow-y: auto; padding: 16px 12px;">
                <div style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; padding-left: 12px;">Navigation</div>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 4px;">
                    <li><a href="?route=pos" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-cash-register" style="width: 20px; color: #64748b;"></i>Point of sale <span style="margin-left: auto; background: #f1f5f9; padding: 2px 6px; font-size: 0.75rem; border-radius: 4px; color: #64748b;">F2</span></a></li>
                    <li><a href="?route=inventory" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-boxes-stacked" style="width: 20px; color: #64748b;"></i>Inventory</a></li>
                    <li><a href="?route=purchases" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-truck-field" style="width: 20px; color: #64748b;"></i>Purchases</a></li>
                    <li><a href="?route=services" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-print" style="width: 20px; color: #64748b;"></i>Service billing</a></li>
                    <li><a href="?route=returns" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #2563eb; font-size: 0.9rem; font-weight: 600; background: #eff6ff;"><i class="fa-solid fa-arrow-rotate-left" style="width: 20px; color: #2563eb;"></i>Product returns</a></li>
                    <li><a href="?route=mfs" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-mobile-screen-button" style="width: 20px; color: #64748b;"></i>MFS ledger</a></li>
                    <li><a href="?route=utility" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-receipt" style="width: 20px; color: #64748b;"></i>Utility bills</a></li>
                    <li><a href="?route=income" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-hand-holding-dollar" style="width: 20px; color: #64748b;"></i>Income records</a></li>
                    <li><a href="?route=expenses" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-money-bill-wave" style="width: 20px; color: #64748b;"></i>Expenses</a></li>
                    <li><a href="?route=reports" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-chart-line" style="width: 20px; color: #64748b;"></i>Reports</a></li>
                    <li><a href="?route=salaries" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-users-gear" style="width: 20px; color: #64748b;"></i>Employee salaries</a></li>
                    <li><a href="?route=users" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-users" style="width: 20px; color: #64748b;"></i>User management</a></li>
                    
                    <!-- Logout Option -->
                    <li style="margin-top: 15px; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                        <a href="?route=logout" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #dc2626; font-size: 0.9rem; font-weight: 500; background: #fef2f2;">
                            <i class="fa-solid fa-right-from-bracket" style="width: 20px; color: #dc2626;"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>

            <div style="padding: 16px; border-top: 1px solid #f1f5f9;">
                <div style="display: flex; align-items: center; gap: 12px; background: #f8fafc; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; cursor: pointer;">
                    <i class="fa-regular fa-circle-question" style="font-size: 1.2rem; color: #2563eb;"></i>
                    <div style="display: flex; flex-direction: column; line-height: 1.2;">
                        <strong style="font-size: 0.85rem; color: #1e293b;">Need a hand?</strong>
                        <small style="font-size: 0.75rem; color: #64748b;">View quick guides</small>
                    </div>
                    <i class="fa-solid fa-arrow-up-right-from-square" style="margin-left: auto; font-size: 0.8rem; color: #94a3b8;"></i>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content standalone-module" style="margin-left: 280px; flex: 1; padding: 30px; box-sizing: border-box;">
            
            <!-- Topbar Header -->
            <header class="topbar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; background: white; padding: 20px 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div>
                    <p class="eyebrow mb-1" style="font-size: 0.75rem; font-weight: 700; color: #2563eb; text-transform: uppercase; margin: 0 0 2px 0; letter-spacing: 0.5px;">Inventory controls</p>
                    <h1 style="font-size: 1.4rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-arrow-rotate-left module-title-icon" style="color: #2563eb;"></i>Product Returns
                    </h1>
                    <p class="module-subtitle" style="font-size: 0.85rem; color: #64748b; margin: 4px 0 0 0;">Process returns against original sales or purchase vouchers</p>
                </div>
                <a class="btn btn-soft" href="<?= e(APP_URL) ?>" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
                </a>
            </header>

            <!-- Alerts -->
            <?php if ($error): ?>
                <div class="alert alert-danger module-alert" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;"><?= e($error) ?></div>
            <?php elseif ($flash): ?>
                <div class="alert alert-success module-alert" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;"><?= e($flash) ?></div>
            <?php endif; ?>

            <!-- Module Grid (Steps) -->
            <div class="module-grid" style="display: grid; grid-template-columns: <?= $source ? '1fr 1fr' : '1fr' ?>; gap: 20px; margin-bottom: 25px;">
                
                <!-- Step 1: Find Voucher Panel -->
                <section class="panel" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    <div class="panel-heading" style="margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                        <div>
                            <span class="section-kicker" style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;">Step 1</span>
                            <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 2px 0 0 0;">Find original voucher</h2>
                        </div>
                    </div>
                    <form method="get" class="row g-3" style="display: flex; flex-direction: column; gap: 15px;">
                        <input type="hidden" name="route" value="returns">
                        <div class="col-12">
                            <label class="form-label" style="font-size: 0.85rem; font-weight: 600; color: #475569; display: block; margin-bottom: 6px;">Sales or purchase voucher number</label>
                            <input class="form-control" name="voucher" value="<?= e($_GET['voucher'] ?? '') ?>" placeholder="SALE-20260910... or PURCHASE-..." required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100" style="background: #2563eb; color: white; border: none; padding: 10px 16px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; cursor: pointer; width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Load voucher items
                            </button>
                        </div>
                    </form>
                    
                    <?php if ($source): ?>
                        <hr class="module-divider" style="margin: 20px 0; border: none; border-top: 1px solid #f1f5f9;">
                        <div class="source-voucher" style="background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 4px;">
                            <span style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;"><?= e(ucfirst($source['voucher']['voucher_type'])) ?> voucher</span>
                            <strong style="font-size: 1rem; color: #1e293b;"><?= e($source['voucher']['voucher_number']) ?></strong>
                            <small style="font-size: 0.75rem; color: #64748b;">Created <?= date('d M Y, h:i A', strtotime($source['voucher']['created_at'])) ?></small>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Step 2: Process Return Panel (Shown if $source exists) -->
                <?php if ($source): ?>
                    <section class="panel" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                        <div class="panel-heading" style="margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                            <div>
                                <span class="section-kicker" style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;">Step 2</span>
                                <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 2px 0 0 0;">Process return</h2>
                            </div>
                        </div>
                        <form method="post" class="row g-3" style="display: flex; flex-direction: column; gap: 15px;">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="voucher_number" value="<?= e($source['voucher']['voucher_number']) ?>">
                            
                            <div class="col-12">
                                <label class="form-label" style="font-size: 0.85rem; font-weight: 600; color: #475569; display: block; margin-bottom: 6px;">Product</label>
                                <select class="form-select" name="product_id" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; outline: none; background: white; box-sizing: border-box;">
                                    <?php foreach($source['items'] as $item): if ($item['remaining_quantity'] > 0): ?>
                                        <option value="<?= $item['product_id'] ?>"><?= e($item['name']) ?> · Remaining <?= number_format($item['remaining_quantity'], 3) ?></option>
                                    <?php endif; endforeach; ?>
                                </select>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div class="col-md-6">
                                    <label class="form-label" style="font-size: 0.85rem; font-weight: 600; color: #475569; display: block; margin-bottom: 6px;">Return quantity</label>
                                    <input class="form-control" name="quantity" type="number" min=".001" step=".001" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" style="font-size: 0.85rem; font-weight: 600; color: #475569; display: block; margin-bottom: 6px;">Reason</label>
                                    <input class="form-control" name="reason" placeholder="Damaged, incorrect item..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                                </div>
                            </div>

                            <div class="col-12" style="margin-top: 5px;">
                                <button class="btn btn-primary w-100" style="background: #16a34a; color: white; border: none; padding: 10px 16px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; cursor: pointer; width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                                    <i class="fa-solid fa-check me-1"></i> Confirm return
                                </button>
                            </div>
                        </form>
                    </section>
                <?php endif; ?>
            </div>

            <!-- Audit Trail Panel (Recent Returns) -->
            <section class="panel" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div class="panel-heading" style="margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                    <div>
                        <span class="section-kicker" style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;">Audit trail</span>
                        <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 2px 0 0 0;">Recent returns</h2>
                    </div>
                </div>
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table align-middle" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e2e8f0; color: #475569;">
                                <th style="padding: 12px; font-weight: 600;">Return no.</th>
                                <th style="padding: 12px; font-weight: 600;">Source voucher</th>
                                <th style="padding: 12px; font-weight: 600;">Type</th>
                                <th style="padding: 12px; font-weight: 600;">Total</th>
                                <th style="padding: 12px; font-weight: 600;">Processed by</th>
                                <th style="padding: 12px; font-weight: 600;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($returns as $return): ?>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 12px;"><strong style="color: #1e293b;"><?= e($return['return_number']) ?></strong></td>
                                    <td style="padding: 12px; color: #475569;"><?= e($return['source_voucher']) ?></td>
                                    <td style="padding: 12px;">
                                        <span style="background: #f1f5f9; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; color: #475569;"><?= e($return['return_type']) ?></span>
                                    </td>
                                    <td style="padding: 12px;"><strong style="color: #1e293b;">৳ <?= number_format((float)$return['total'], 2) ?></strong></td>
                                    <td style="padding: 12px; color: #475569;"><?= e($return['full_name']) ?></td>
                                    <td style="padding: 12px; color: #64748b; font-size: 0.85rem;"><?= date('d M Y, h:i A', strtotime($return['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; if (!$returns): ?>
                                <tr>
                                    <td colspan="6" class="empty-state" style="text-align: center; padding: 40px; color: #64748b; font-style: italic;">
                                        <i class="fa-solid fa-receipt" style="font-size: 2rem; margin-bottom: 10px; display: block; color: #cbd5e1;"></i>
                                        No returns recorded yet.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Footer -->
            <footer style="display: flex; justify-content: space-between; align-items: center; padding-top: 25px; margin-top: 25px; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 0.85rem;">
                <span>© <?= date('Y') ?> Iqra Stationary Solutions</span>
                <span style="display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-circle" style="font-size: 0.5rem; color: #10b981;"></i> All systems operational</span>
            </footer>
        </main>
    </div>

</body>
</html>