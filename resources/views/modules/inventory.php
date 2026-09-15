<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory | <?= e(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
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
                    <li><a href="?route=inventory" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; text-decoration: none; background: #2563eb; color: white; font-size: 0.9rem; font-weight: 500; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);"><i class="fa-solid fa-boxes-stacked" style="width: 20px; color: white;"></i>Inventory</a></li>
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
            
            <!-- Alerts -->
            <?php if (!empty($error)): ?>
                <div style="background-color: #f8d7da; color: #842029; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c2c7; font-weight: 500;"><?= e($error) ?></div>
            <?php elseif (!empty($flash)): ?>
                <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #badbcc; font-weight: 500;"><?= e($flash) ?></div>
            <?php endif; ?>

            <!-- Top Header -->
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: white; padding: 20px 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: 0.5px;">Catalog</span>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 4px 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-boxes-stacked" style="color: #2563eb;"></i>Inventory
                    </h1>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">Products and stock control</p>
                </div>
                <a href="<?= e(APP_URL) ?>" style="background: #f1f5f9; color: #475569; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #e2e8f0;">
                    <i class="fa-solid fa-arrow-left"></i> Dashboard
                </a>
            </header>

            <!-- Main Content Grid (Form & Product Catalog Table) -->
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-bottom: 30px;">
                
                <!-- Left: Add/Edit Product Form -->
                <section style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; height: fit-content;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;">Catalog</span>
                            <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 2px 0 0 0;"><?= !empty($editProduct) ? 'Edit product' : 'Add product' ?></h2>
                        </div>
                        <?php if (!empty($editProduct)): ?>
                            <a href="?route=inventory" style="background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600; border: 1px solid #e2e8f0;"><i class="fa-solid fa-times"></i> Cancel</a>
                        <?php endif; ?>
                    </div>

                    <form method="post" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                        <?php if (!empty($editProduct)): ?>
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
                        <?php endif; ?>

                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Product name</label>
                            <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="name" required placeholder="e.g. Premium ball pen" value="<?= e($editProduct['name'] ?? '') ?>">
                        </div>
                        <div style="grid-column: span 1;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">SKU</label>
                            <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="sku" required placeholder="PEN-001" value="<?= e($editProduct['sku'] ?? '') ?>">
                        </div>
                        <div style="grid-column: span 1;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Barcode</label>
                            <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="barcode" placeholder="Optional barcode" value="<?= e($editProduct['barcode'] ?? '') ?>">
                        </div>
                        <div style="grid-column: span 1;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Category</label>
                            <select style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; background: white; box-sizing: border-box;" name="category_id" required>
                                <?php foreach ($categories ?? [] as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= ($editProduct['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div style="grid-column: span 1;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Supplier</label>
                            <select style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; background: white; box-sizing: border-box;" name="supplier_id">
                                <option value="">Company / no supplier</option>
                                <?php foreach ($suppliers ?? [] as $supplier): ?>
                                    <option value="<?= $supplier['id'] ?>" <?= ($editProduct['supplier_id'] ?? '') == $supplier['id'] ? 'selected' : '' ?>><?= e($supplier['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div style="grid-column: span 1;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Unit</label>
                            <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="unit" value="<?= e($editProduct['unit'] ?? 'piece') ?>">
                        </div>
                        <div style="grid-column: span 1;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Buying price</label>
                            <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="buying_price" type="number" min="0" step=".01" required value="<?= number_format((float)($editProduct['buying_price'] ?? 0), 2, '.', '') ?>">
                        </div>
                        <div style="grid-column: span 1;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Selling price</label>
                            <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="selling_price" type="number" min="0" step=".01" required value="<?= number_format((float)($editProduct['selling_price'] ?? 0), 2, '.', '') ?>">
                        </div>
                        
                        <?php if (!empty($editProduct)): ?>
                            <div style="grid-column: span 2;">
                                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Stock adjustment</label>
                                <div style="display: flex; gap: 6px;">
                                    <input id="productBarcode" style="min-width: 0; flex: 1; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="barcode" placeholder="Editable barcode" value="<?= e($editProduct['barcode'] ?? '') ?>">
                                    <button type="button" class="generate-barcode btn btn-outline-secondary" data-target="#productBarcode" title="Generate barcode"><i class="fa-solid fa-wand-magic-sparkles"></i></button>
                                </div>
                                <small style="display: block; margin-top: 4px; color: #64748b;">Generate once, then edit freely before saving.</small>
                            </div>
                        <?php else: ?>
                            <div style="grid-column: span 2;">
                                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Opening stock</label>
                                <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="stock_quantity" type="number" min="0" step=".001" value="0">
                            </div>
                        <?php endif; ?>

                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Low stock alert at</label>
                            <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="low_stock_threshold" type="number" min="0" step=".001" value="<?= number_format((float)($editProduct['low_stock_threshold'] ?? 5), 3, '.', '') ?>">
                        </div>

                        <div style="grid-column: span 2; margin-top: 5px;">
                            <button style="width: 100%; background: #2563eb; color: white; border: none; padding: 12px; border-radius: 8px; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                                <i class="fa-solid fa-<?= !empty($editProduct) ? 'save' : 'plus' ?>" style="margin-right: 6px;"></i> 
                                <?= !empty($editProduct) ? 'Update product' : 'Add product' ?>
                            </button>
                        </div>
                    </form>
                </section>

                <!-- Right: Product Catalog Table -->
                <section style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    <div style="margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        <span style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;">Stock levels</span>
                        <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 2px 0 0 0;">Product catalog</h2>
                    </div>

                    <div style="max-height: 540px; overflow-y: auto; border: 1px solid #f1f5f9; border-radius: 8px;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                            <thead style="position: sticky; top: 0; background: #f8fafc; z-index: 10;">
                                <tr style="border-bottom: 2px solid #e2e8f0; color: #475569;">
                                    <th style="padding: 12px; font-weight: 600;">Product</th>
                                    <th style="padding: 12px; font-weight: 600;">SKU</th>
                                    <th style="padding: 12px; font-weight: 600;">Category</th>
                                    <th style="padding: 12px; font-weight: 600;">Stock</th>
                                    <th style="padding: 12px; font-weight: 600;">Buying</th>
                                    <th style="padding: 12px; font-weight: 600;">Selling</th>
                                    <th style="padding: 12px; font-weight: 600;">Barcode</th>
                                    <th style="padding: 12px; font-weight: 600;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 40px; color: #64748b; font-style: italic;">
                                            <i class="fa-solid fa-boxes-stacked" style="font-size: 2rem; margin-bottom: 10px; display: block; color: #cbd5e1;"></i>
                                            No products found in inventory.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                            <td style="padding: 12px;"><strong style="color: #1e293b;"><?= e($product['name']) ?></strong></td>
                                            <td style="padding: 12px; color: #64748b;"><?= e($product['sku']) ?></td>
                                            <td style="padding: 12px; color: #475569;"><?= e($product['category_name'] ?? 'Uncategorized') ?></td>
                                            <td style="padding: 12px;">
                                                <span style="<?= (float) $product['stock_quantity'] <= (float) $product['low_stock_threshold'] ? 'color: #dc2626; font-weight: 600;' : 'color: #059669; font-weight: 600;' ?>">
                                                    <?= number_format((float) $product['stock_quantity'], 3) ?>
                                                </span>
                                            </td>
                                            <td style="padding: 12px; color: #475569;">৳ <?= number_format((float) $product['buying_price'], 2) ?></td>
                                            <td style="padding: 12px; color: #475569;">৳ <?= number_format((float) $product['selling_price'], 2) ?></td>
                                            <td style="padding: 12px; color: #475569;"><code><?= e($product['barcode'] ?? 'Not set') ?></code></td>
                                            <td style="padding: 12px;">
                                                <button type="button" class="print-product-labels" data-label-product='<?= e(json_encode(['name' => $product['name'], 'price' => number_format((float) $product['selling_price'], 2), 'sku' => $product['sku'], 'barcode' => $product['barcode'] ?? '', 'supplier' => $product['supplier_name'] ?? APP_NAME])) ?>' style="background: #ecfdf5; color: #047857; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; border: 1px solid #a7f3d0; display: inline-flex; align-items: center; justify-content: center; margin-right: 4px;" title="Print labels">
                                                    <i class="fa-solid fa-tags"></i>
                                                </button>
                                                <a href="?route=inventory&edit=<?= $product['id'] ?>" style="background: #f1f5f9; color: #2563eb; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; justify-content: center;" title="Edit product">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- Footer -->
            <footer style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 0.85rem;">
                <span>© <?= date('Y') ?> Iqra Stationary Solutions</span>
                <span style="display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-circle" style="font-size: 0.5rem; color: #10b981;"></i> All systems operational</span>
            </footer>

        </main>
    </div>

    <script>window.IQRA_APP_URL = <?= json_encode(APP_URL) ?>;</script>
</body>
</html>