<?php
$summaryData = get_defined_vars()['summary'] ?? [];
$summary = array_merge([
    'sales_today' => 0,
    'purchases_month' => 0,
    'service_today' => 0,
    'float' => 0,
    'low_stock' => 0,
], is_array($summaryData) ? $summaryData : []);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(APP_NAME) ?> | Dashboard</title>
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
                    <li><a href="?route=returns" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; color: #475569; font-size: 0.9rem; font-weight: 500;"><i class="fa-solid fa-arrow-rotate-left" style="width: 20px; color: #64748b;"></i>Product returns</a></li>
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
        <main style="margin-left: 280px; flex: 1; padding: 30px; box-sizing: border-box;">
            
            <!-- Topbar Header -->
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; background: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div>
                    <p style="font-size: 0.75rem; font-weight: 700; color: #2563eb; text-transform: uppercase; margin: 0 0 2px 0; letter-spacing: 0.5px;"><?= date('l, d F Y') ?></p>
                    <h1 style="font-size: 1.4rem; font-weight: 700; color: #1e293b; margin: 0;">Good morning, <?= e(explode(' ', Auth::user()['name'] ?? 'Admin')[0]) ?> <span style="color: #f59e0b;">✦</span></h1>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button style="background: #f8fafc; border: 1px solid #e2e8f0; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; position: relative;" title="Notifications">
                        <i class="fa-regular fa-bell"></i>
                        <span style="position: absolute; top: 8px; right: 8px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></span>
                    </button>
                    <div style="display: flex; align-items: center; gap: 10px; background: #f8fafc; padding: 6px 12px; border-radius: 30px; border: 1px solid #e2e8f0;">
                        <div style="width: 32px; height: 32px; background: #2563eb; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.85rem;">IS</div>
                        <div style="line-height: 1.2;">
                            <strong style="font-size: 0.85rem; display: block; color: #1e293b;"><?= e(Auth::user()['name'] ?? 'Admin User') ?></strong>
                            <small style="font-size: 0.7rem; color: #64748b;"><?= e(Auth::user()['role'] ?? 'Admin') ?></small>
                        </div>
                        <a href="?route=logout" style="color: #dc2626; margin-left: 8px; font-size: 0.9rem;" title="Sign out"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
                    </div>
                </div>
            </header>

            <!-- Quick Actions / Business Pulse -->
            <section style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Today at a glance</span>
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 2px 0 0 0;">Business pulse</h2>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="?route=pos" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(37,99,235,0.2);">
                        <i class="fa-solid fa-plus"></i> New sale
                    </a>
                    <a href="?route=reports" style="background: white; color: #475569; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #e2e8f0;">
                        <i class="fa-solid fa-file-export"></i> View reports
                    </a>
                </div>
            </section>

            <!-- KPI Cards Grid -->
            <section style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
                <!-- Card 1 -->
                <article style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; border-top: 4px solid #0d9488;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <span style="width: 36px; height: 36px; background: #f0fdf4; color: #0d9488; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-arrow-trend-up"></i></span>
                        <span style="font-size: 0.75rem; font-weight: 600; color: #059669; background: #ecfdf5; padding: 2px 6px; border-radius: 4px;"><i class="fa-solid fa-arrow-up"></i> 12.8%</span>
                    </div>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0 0 4px 0;">Sales today</p>
                    <h3 style="font-size: 1.4rem; font-weight: 700; color: #1e293b; margin: 0 0 6px 0;">৳ <?= number_format($summary['sales_today'], 0) ?></h3>
                    <small style="font-size: 0.75rem; color: #94a3b8;">Compared to yesterday</small>
                </article>

                <!-- Card 2 -->
                <article style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; border-top: 4px solid #f43f5e;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <span style="width: 36px; height: 36px; background: #fff1f2; color: #f43f5e; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-basket-shopping"></i></span>
                        <span style="font-size: 0.75rem; font-weight: 600; color: #dc2626; background: #fef2f2; padding: 2px 6px; border-radius: 4px;"><i class="fa-solid fa-arrow-down"></i> 3.2%</span>
                    </div>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0 0 4px 0;">Purchases this month</p>
                    <h3 style="font-size: 1.4rem; font-weight: 700; color: #1e293b; margin: 0 0 6px 0;">৳ <?= number_format($summary['purchases_month'], 0) ?></h3>
                    <small style="font-size: 0.75rem; color: #94a3b8;">Across all suppliers</small>
                </article>

                <!-- Card 3 -->
                <article style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; border-top: 4px solid #f59e0b;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <span style="width: 36px; height: 36px; background: #fffbeb; color: #f59e0b; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-print"></i></span>
                        <span style="font-size: 0.75rem; font-weight: 600; color: #059669; background: #ecfdf5; padding: 2px 6px; border-radius: 4px;"><i class="fa-solid fa-arrow-up"></i> 8.4%</span>
                    </div>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0 0 4px 0;">Service revenue</p>
                    <h3 style="font-size: 1.4rem; font-weight: 700; color: #1e293b; margin: 0 0 6px 0;">৳ <?= number_format($summary['service_today'], 0) ?></h3>
                    <small style="font-size: 0.75rem; color: #94a3b8;">Printing & digital services</small>
                </article>

                <!-- Card 4 -->
                <article style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; border-top: 4px solid #2563eb;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <span style="width: 36px; height: 36px; background: #eff6ff; color: #2563eb; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-mobile-screen-button"></i></span>
                        <span style="font-size: 0.75rem; font-weight: 600; color: #2563eb; background: #eff6ff; padding: 2px 6px; border-radius: 4px;">Healthy</span>
                    </div>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0 0 4px 0;">MFS float balance</p>
                    <h3 style="font-size: 1.4rem; font-weight: 700; color: #1e293b; margin: 0 0 6px 0;">৳ <?= number_format($summary['float'], 0) ?></h3>
                    <small style="font-size: 0.75rem; color: #94a3b8;">Across 3 providers</small>
                </article>
            </section>

            <!-- Dashboard Grid (Sales Table & Low Stock) -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 25px;">
                <!-- Recent Sales Panel -->
                <section style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;">Live activity</span>
                            <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 2px 0 0 0;">Recent sales</h2>
                        </div>
                        <a href="?route=sales" style="color: #2563eb; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">View all <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                            <thead>
                                <tr style="border-bottom: 2px solid #e2e8f0; color: #475569;">
                                    <th style="padding: 10px; font-weight: 600;">Invoice</th>
                                    <th style="padding: 10px; font-weight: 600;">Customer</th>
                                    <th style="padding: 10px; font-weight: 600;">Operator</th>
                                    <th style="padding: 10px; font-weight: 600;">Amount</th>
                                    <th style="padding: 10px; font-weight: 600;">Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentSales)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 40px; color: #64748b; font-style: italic;">
                                            <i class="fa-solid fa-receipt" style="font-size: 2rem; margin-bottom: 10px; display: block; color: #cbd5e1;"></i>
                                            No sales recorded yet
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentSales as $sale): ?>
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 10px;">
                                                <strong style="color: #1e293b; display: block;"><?= e($sale['invoice_number']) ?></strong>
                                                <small style="color: #94a3b8; font-size: 0.75rem;"><?= date('h:i A', strtotime($sale['created_at'])) ?></small>
                                            </td>
                                            <td style="padding: 10px; color: #475569;"><?= e($sale['customer_name']) ?></td>
                                            <td style="padding: 10px; color: #475569; display: flex; align-items: center; gap: 8px;">
                                                <span style="width: 24px; height: 24px; background: #e2e8f0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600; color: #475569;"><?= e(substr($sale['full_name'], 0, 1)) ?></span>
                                                <?= e($sale['full_name']) ?>
                                            </td>
                                            <td style="padding: 10px;"><strong style="color: #1e293b;">৳ <?= number_format((float) $sale['total'], 0) ?></strong></td>
                                            <td style="padding: 10px;">
                                                <span style="background: #f1f5f9; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; color: #475569;"><?= e($sale['payment_method']) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Low Stock Panel -->
                <section style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 600; color: #dc2626; text-transform: uppercase;">Needs attention</span>
                            <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 2px 0 0 0;">Low stock <span style="background: #fef2f2; color: #dc2626; padding: 1px 6px; border-radius: 10px; font-size: 0.75rem;"><?= $summary['low_stock'] ?></span></h2>
                        </div>
                        <a href="?route=inventory" style="color: #2563eb; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">Inventory <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    
                    <?php if (empty($lowStockProducts)): ?>
                        <div style="text-align: center; padding: 40px 0; color: #64748b; font-style: italic;">
                            <i class="fa-solid fa-box-open" style="font-size: 2rem; margin-bottom: 10px; display: block; color: #cbd5e1;"></i>
                            All shelves are stocked
                        </div>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <?php foreach ($lowStockProducts as $product): ?>
                                <div style="display: flex; align-items: center; gap: 12px; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #f1f5f9;">
                                    <div style="width: 32px; height: 32px; background: #fee2e2; color: #dc2626; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                    <div style="flex: 1; line-height: 1.2;">
                                        <strong style="font-size: 0.85rem; color: #1e293b; display: block;"><?= e($product['name']) ?></strong>
                                        <small style="font-size: 0.75rem; color: #64748b;"><?= e($product['sku']) ?></small>
                                    </div>
                                    <div style="text-align: right;">
                                        <strong style="font-size: 0.9rem; color: #dc2626; display: block;"><?= number_format((float) $product['stock_quantity'], 0) ?></strong>
                                        <small style="font-size: 0.7rem; color: #94a3b8;">left</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
            </div>

            <!-- Quick Sale Bar Panel -->
            <section style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; margin-bottom: 25px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                    <div>
                        <span style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;">Counter ready</span>
                        <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 2px 0 0 0;">Quick sale</h2>
                    </div>
                    <a href="?route=pos" style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 6px;">
                        <kbd style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #cbd5e1; font-size: 0.7rem;">F2</kbd> Open full POS
                    </a>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="position: relative;">
                        <i class="fa-solid fa-barcode" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                        <input id="productSearch" type="search" placeholder="Scan barcode or search product..." autocomplete="off" style="width: 100%; padding: 12px 14px 12px 42px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        <kbd style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: #f1f5f9; padding: 2px 6px; border-radius: 4px; border: 1px solid #cbd5e1; font-size: 0.75rem; color: #64748b;">⌘ K</kbd>
                        <div id="productResults" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #cbd5e1; border-radius: 0 0 8px 8px; z-index: 50; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: none;"></div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <button style="background: #2563eb; color: white; border: none; padding: 6px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">All items</button>
                        <button style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 6px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">Writing</button>
                        <button style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 6px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">Paper</button>
                        <button style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 6px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">Office</button>
                        <a href="?route=pos" style="margin-left: auto; color: #2563eb; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">Open POS <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 0.85rem;">
                <span>© <?= date('Y') ?> Iqra Stationary Solutions</span>
                <span style="display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-circle" style="font-size: 0.5rem; color: #10b981;"></i> All systems operational</span>
            </footer>
        </main>
    </div>

    <script>window.IQRA_APP_URL = <?= json_encode(APP_URL) ?>;</script>
    <script src="<?= e(APP_URL) ?>/assets/js/app.js"></script>
</body>
</html>