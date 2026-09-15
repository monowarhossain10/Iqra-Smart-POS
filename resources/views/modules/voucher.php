<?php 
$payload = $voucher['payload'] ?? []; 
$labels = [
    'sale'     => 'Sales Voucher', 
    'purchase' => 'Purchase Voucher', 
    'service'  => 'Service Voucher', 
    'mfs'      => 'MFS Transaction Voucher', 
    'utility'  => 'Utility Bill Voucher',
    'return'   => 'Product Return Voucher',
    'salary'   => 'Salary Payment Voucher',
    'income'   => 'Income Voucher',
    'expense'  => 'Expense Voucher'
]; 
$details = $voucher['details'] ?? [];
$saleItems = [];
foreach (($payload['items'] ?? []) as $item) {
    if (is_array($item) && array_key_exists('product', $item)) {
        $saleItems[] = ['name' => $item['product'], 'quantity' => $item['quantity'] ?? 0, 'unit_price' => $item['unit_price'] ?? $item['unit_cost'] ?? 0, 'discount' => $item['discount'] ?? 0, 'total' => $item['total'] ?? 0];
    } elseif (is_array($item) && isset($item[0])) {
        $saleItems[] = ['name' => $item[0]['name'] ?? '', 'quantity' => $item[1] ?? 0, 'unit_price' => $item[0]['selling_price'] ?? 0, 'discount' => 0, 'total' => $item[2] ?? 0];
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($voucher['voucher_number']) ?> | <?= e(APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #087f73;
            --primary-dark: #0f3332;
            --soft-bg: #f5f8f6;
            --border-line: #e2e8f0;
            --text-main: #182c2b;
            --text-muted: #64748b;
        }

        @page {
            size: 80mm auto;
            margin: 2mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--soft-bg);
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
        }

        .voucher-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .voucher {
            width: 360px;
            background: #ffffff;
            padding: 20px 16px;
            box-shadow: 0 10px 30px rgba(15, 51, 50, 0.08);
            border-radius: 12px;
            position: relative;
            border: 1px solid #edf2f0;
        }

        /* Modern Brand Header */
        .voucher-brand {
            text-align: center;
            border-bottom: 2px dashed #e2e8f0;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }

        .brand-logo-icon {
            width: 34px;
            height: 34px;
            background: var(--primary-teal);
            color: #fff;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            margin-bottom: 4px;
            box-shadow: 0 4px 10px rgba(8, 127, 115, 0.2);
        }

        .voucher-brand strong {
            font-family: 'Outfit', sans-serif;
            font-size: 19px;
            font-weight: 700;
            color: var(--primary-dark);
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .voucher-brand small {
            font-size: 10.5px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Voucher Title & Meta */
        .voucher-title-badge {
            background: #e4f3ef;
            color: var(--primary-teal);
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 4px 8px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 10px;
        }

        .voucher-meta {
            display: flex;
            justify-content: space-between;
            font-size: 10.5px;
            color: var(--text-muted);
            background: #f8fafc;
            padding: 6px 8px;
            border-radius: 6px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .party-info {
            font-size: 11px;
            margin-bottom: 10px;
            color: #1e293b;
            background: #f1f5f9;
            padding: 6px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        /* Tables */
        .voucher-table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .voucher-table th {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            border-bottom: 1.5px solid #cbd5e1;
            text-align: left;
            padding: 5px 3px;
        }

        .voucher-table td {
            padding: 6px 3px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            vertical-align: middle;
            font-weight: 500;
        }

        .text-end {
            text-align: right;
        }

        /* Summary Section (Reduced Spacing & Bolder) */
        .summary-box {
            border-top: 1.5px dashed #cbd5e1;
            margin-top: 6px;
            padding-top: 4px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            padding: 2.5px 0;
            color: #1e293b;
            font-weight: 600;
        }

        .summary-row.grand-total {
            border-top: 2px solid var(--primary-dark);
            margin-top: 4px;
            padding-top: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--primary-dark);
        }

        /* Footer Notes */
        .voucher-note {
            text-align: center;
            color: var(--text-muted);
            font-size: 10px;
            margin-top: 14px;
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
            line-height: 1.3;
        }

        /* Action Buttons */
        .voucher-actions {
            margin-top: 16px;
            width: 360px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 9px 16px;
            font-size: 12.5px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background-color: var(--primary-teal);
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #076d63;
        }

        .btn-soft {
            background-color: #e2e8f0;
            color: #334155;
        }

        .btn-soft:hover {
            background-color: #cbd5e1;
        }

        /* Print Media Query */
        @media print {
            body {
                background: #fff;
            }
            .voucher-page {
                padding: 0;
                min-height: auto;
            }
            .voucher {
                width: 100%;
                box-shadow: none;
                padding: 4px;
                border: none;
                border-radius: 0;
            }
            .voucher-actions {
                display: none;
            }
        }
    </style>
</head>
<body>
    <main class="voucher-page">
        <article class="voucher">
            <!-- Brand Header -->
            <header class="voucher-brand">
                <div class="brand-logo-icon">
                    <i class="fa-solid fa-store"></i>
                </div>
                <strong>iqra</strong>
                <small>Iqra Stationary Solutions</small>
            </header>

            <!-- Voucher Type Badge -->
            <div class="voucher-title-badge">
                <?= e($labels[$voucher['voucher_type']] ?? 'Transaction Voucher') ?>
            </div>
            
            <!-- Metadata: Number & Date -->
            <div class="voucher-meta">
                <span><strong>No:</strong> <?= e($voucher['voucher_number']) ?></span>
                <span><i class="fa-regular fa-clock" style="font-size: 10px;"></i> <?= date('d M Y, h:i A', strtotime($voucher['created_at'])) ?></span>
            </div>

            <!-- Items Table for Sale/Purchase -->
            <?php if (in_array($voucher['voucher_type'], ['sale', 'purchase'], true)): ?>
                <div class="party-info">
                    <strong><?= $voucher['voucher_type'] === 'sale' ? 'Customer' : 'Supplier' ?>:</strong> 
                    <?= e($details[$voucher['voucher_type'] === 'sale' ? 'customer_name' : 'supplier_name'] ?? $payload['customer'] ?? 'Walk-in Customer') ?>
                </div>
                <table class="voucher-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Disc</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($saleItems as $item): ?>
                            <tr>
                                <td>
                                    <strong><?= e($item['name']) ?></strong>
                                    <small style="display:block; color:#64748b; font-size: 9px;">৳ <?= number_format((float)$item['unit_price'], 2) ?> / unit</small>
                                </td>
                                <td><?= number_format((float)$item['quantity'], 2) ?></td>
                                <td>৳ <?= number_format((float)$item['discount'], 2) ?></td>
                                <td class="text-end"><strong>৳ <?= number_format((float)$item['total'], 2) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <!-- Generic Payload Table -->
                <table class="voucher-table">
                    <tbody>
                        <?php foreach ($payload as $key => $value): ?>
                            <?php if (is_scalar($value) && $value !== ''): ?>
                                <tr>
                                    <td><?= e(ucwords(str_replace('_', ' ', (string)$key))) ?></td>
                                    <td class="text-end"><strong><?= e((string)$value) ?></strong></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <!-- Breakdown Details & Summary -->
            <?php if ($details): ?>
                <div class="summary-box">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>৳ <?= number_format((float)($details['subtotal'] ?? $voucher['amount']), 2) ?></span>
                    </div>
                    <?php if (!empty($details['discount']) && (float)$details['discount'] > 0): ?>
                    <div class="summary-row">
                        <span>Discount</span>
                        <span>- ৳ <?= number_format((float)$details['discount'], 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="summary-row">
                        <span>Paid Amount</span>
                        <span>৳ <?= number_format((float)($details['paid_amount'] ?? 0), 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span><?= $voucher['voucher_type'] === 'sale' ? 'Change Return' : 'Balance Due' ?></span>
                        <span><strong>৳ <?= number_format($voucher['voucher_type'] === 'sale' ? (float)($payload['change_amount'] ?? max(0, (float)($details['paid_amount'] ?? 0) - (float)($details['total'] ?? 0))) : max(0, (float)($details['total'] ?? 0) - (float)($details['paid_amount'] ?? 0)), 2) ?></strong></span>
                    </div>
                    <div class="summary-row">
                        <span>Payment Method</span>
                        <span><strong><?= e(ucfirst($details['payment_method'] ?? '-')) ?></strong></span>
                    </div>
                    <?php if (!empty($details['payment_reference'])): ?>
                    <div class="summary-row">
                        <span>Reference</span>
                        <span><?= e($details['payment_reference']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Grand Total -->
            <div class="summary-row grand-total">
                <span>Total Amount</span>
                <span>৳ <?= number_format((float)($details['total'] ?? $voucher['amount']), 2) ?></span>
            </div>

            <!-- Note / Footer -->
            <p class="voucher-note">
                Thank you for your business!<br>
                Prepared by: <strong><?= e($voucher['full_name']) ?></strong>
            </p>
        </article>

        <!-- Action Controls -->
        <div class="voucher-actions">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Print Voucher
            </button>
            <a class="btn btn-soft" href="<?= e(APP_URL) ?>/?route=<?= e($voucher['voucher_type'] === 'mfs' ? 'mfs' : ($voucher['voucher_type'] === 'sale' ? 'pos' : ($voucher['voucher_type'] === 'purchase' ? 'purchases' : ($voucher['voucher_type'] === 'service' ? 'services' : 'utility')))) ?>">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </main>
</body>
</html>