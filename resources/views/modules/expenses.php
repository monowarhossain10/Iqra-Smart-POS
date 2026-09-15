<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expenses | <?= e(APP_NAME) ?></title>
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
                    <li><a href="?route=expenses" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; background: #2563eb; color: white; font-size: 0.9rem; font-weight: 500; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);"><i class="fa-solid fa-money-bill-wave" style="width: 20px; color: white;"></i>Expenses</a></li>
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
            
            <!-- Alerts -->
            <?php if (!empty($error)): ?>
                <div style="background-color: #f8d7da; color: #842029; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c2c7; font-weight: 500;"><?= e($error) ?></div>
            <?php elseif (!empty($flash)): ?>
                <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #badbcc; font-weight: 500;"><?= e($flash) ?></div>
            <?php endif; ?>

            <!-- Top Header -->
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: white; padding: 20px 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: 0.5px;">Financial records</span>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 4px 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-money-bill-wave" style="color: #2563eb;"></i>Expenses
                    </h1>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">Track business expenses including electricity bills, shop rent, and other costs</p>
                </div>
                <a href="<?= e(APP_URL) ?>" style="background: #f1f5f9; color: #475569; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #e2e8f0;">
                    <i class="fa-solid fa-arrow-left"></i> Dashboard
                </a>
            </header>

            <!-- Main Grid Section (Form & History Table) -->
            <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; margin-bottom: 30px;">
                
                <!-- Left: Record Expense Form Section -->
                <section style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; height: fit-content;">
                    <div style="margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        <span style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;">New entry</span>
                        <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 2px 0 0 0;">Record expense</h2>
                    </div>

                    <form method="post" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

                        <div style="grid-column: span 1;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Category</label>
                            <select name="category" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; background: white; box-sizing: border-box;" required>
                                <option value="Electricity Bill">Electricity Bill</option>
                                <option value="Shop Rent">Shop Rent</option>
                                <option value="Utilities">Utilities</option>
                                <option value="Supplies">Supplies</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Salary">Salary</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div style="grid-column: span 1;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Amount</label>
                            <input name="amount" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" type="number" min="0" step=".01" placeholder="0.00" required>
                        </div>
                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Date</label>
                            <input name="expense_date" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" type="date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Description</label>
                            <input name="description" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" placeholder="Optional details">
                        </div>
                        <div style="grid-column: span 2; margin-top: 5px;">
                            <button style="width: 100%; background: #2563eb; color: white; border: none; padding: 12px; border-radius: 8px; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                                <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Save expense record
                            </button>
                        </div>
                    </form>
                </section>

                <!-- Right: Recent Expenses History Table Section -->
                <section style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    <div style="margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        <span style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;">History</span>
                        <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 2px 0 0 0;">Recent expenses</h2>
                    </div>

                    <div style="max-height: 480px; overflow-y: auto; border: 1px solid #f1f5f9; border-radius: 8px;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                            <thead style="position: sticky; top: 0; background: #f8fafc; z-index: 10;">
                                <tr style="border-bottom: 2px solid #e2e8f0; color: #475569;">
                                    <th style="padding: 12px; font-weight: 600;">Category</th>
                                    <th style="padding: 12px; font-weight: 600;">Description</th>
                                    <th style="padding: 12px; font-weight: 600;">Amount</th>
                                    <th style="padding: 12px; font-weight: 600;">Date</th>
                                    <th style="padding: 12px; font-weight: 600;">Recorded by</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($expenseRecords)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 40px; color: #64748b; font-style: italic;">
                                            <i class="fa-solid fa-receipt" style="font-size: 2rem; margin-bottom: 10px; display: block; color: #cbd5e1;"></i>
                                            No expense records yet
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($expenseRecords as $record): ?>
                                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                            <td style="padding: 12px;"><strong style="color: #1e293b;"><?= e($record['category']) ?></strong></td>
                                            <td style="padding: 12px; color: #475569;"><?= e($record['description'] ?? '-') ?></td>
                                            <td style="padding: 12px; color: #059669; font-weight: 600;">৳ <?= number_format((float) $record['amount'], 2) ?></td>
                                            <td style="padding: 12px; color: #64748b;"><?= date('d M Y', strtotime($record['expense_date'])) ?></td>
                                            <td style="padding: 12px; color: #475569;"><?= e($record['full_name']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

        </main>
    </div>

</body>
</html>