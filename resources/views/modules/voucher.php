<?php 
$payload = $voucher['payload']; 
$labels = [
    'sale'     => 'Sales Voucher', 
    'purchase' => 'Purchase Voucher', 
    'service'  => 'Service Voucher', 
    'mfs'      => 'MFS Transaction Voucher', 
    'utility'  => 'Utility Bill Voucher'
]; 
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($voucher['voucher_number']) ?> | <?= e(APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= e(APP_URL) ?>/assets/css/app.css" rel="stylesheet">
    <link href="<?= e(APP_URL) ?>/assets/css/modules.css" rel="stylesheet">
    <style>
        @page {
            size: 80mm auto;
            margin: 4mm;
        }
        .voucher-page {
            min-height: 100vh;
            background: #eef3f0;
            padding: 40px 20px;
            font-family: 'DM Sans', sans-serif;
        }
        .voucher {
            width: 360px;
            margin: 0 auto;
            background: #fff;
            padding: 24px;
            box-shadow: 0 15px 45px #173d3918;
            border-radius: 8px;
        }
        .voucher-brand {
            text-align: center;
            border-bottom: 1px dashed #aab9b4;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }
        .voucher-brand strong {
            font: 700 22px Outfit, sans-serif;
            color: #113d3b;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .voucher-brand small {
            font-size: 11px;
            color: #81918d;
        }
        .voucher-title {
            text-align: center;
            font: 700 16px Outfit, sans-serif;
            color: #087f73;
            margin: 10px 0;
            text-transform: uppercase;
        }
        .voucher-meta {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #71807c;
            margin-bottom: 15px;
            border-bottom: 1px solid #edf1ef;
            padding-bottom: 8px;
        }
        .voucher-table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }
        .voucher-table th {
            font-size: 10px;
            text-transform: uppercase;
            color: #84938f;
            border-bottom: 1px solid #dce7e2;
            text-align: left;
            padding: 7px 0;
        }
        .voucher-table td {
            padding: 8px 0;
            border-bottom: 1px solid #edf1ef;
            color: #1e293b;
        }
        .voucher-total {
            display: flex;
            justify-content: space-between;
            border-top: 2px solid #113d3b;
            margin-top: 14px;
            padding-top: 10px;
            font: 700 16px Outfit, sans-serif;
            color: #113d3b;
        }
        .voucher-note {
            text-align: center;
            color: #8c9996;
            font-size: 11px;
            margin: 23px 0 0;
            line-height: 1.4;
        }
        .voucher-actions {
            text-align: center;
            margin: 20px auto;
            width: 360px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .voucher-actions .btn {
            padding: 8px 16px;
            font-size: 0.9rem;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        @media print {
            .voucher-page {
                padding: 0;
                background: #fff;
            }
            .voucher {
                width: 100%;
                box-shadow: none;
                padding: 0;
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
            <header class="voucher-brand">
                <strong>iqra</strong>
                <small>Iqra Stationary Solutions</small>
            </header>

            <h1 class="voucher-title"><?= e($labels[$voucher['voucher_type']] ?? 'Transaction Voucher') ?></h1>
            
            <div class="voucher-meta">
                <span><?= e($voucher['voucher_number']) ?></span>
                <span><?= date('d M Y, h:i A', strtotime($voucher['created_at'])) ?></span>
            </div>

            <?php if ($voucher['voucher_type'] === 'sale'): ?>
                <p style="font-size: 11px; margin-bottom: 10px; color: #475569;">
                    Customer: <strong><?= e($payload['customer'] ?? 'Walk-in customer') ?></strong>
                </p>
                <table class="voucher-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (($payload['items'] ?? []) as $item): ?>
                            <tr>
                                <td><?= e($item[0]['name'] ?? '') ?></td>
                                <td><?= e($item[1] ?? '') ?></td>
                                <td class="text-end">৳ <?= number_format((float)($item[2] ?? 0), 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
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

            <div class="voucher-total">
                <span>Total</span>
                <span>৳ <?= number_format((float)$voucher['amount'], 2) ?></span>
            </div>

            <p class="voucher-note">
                Thank you for your business.<br>
                Prepared by <?= e($voucher['full_name']) ?>
            </p>
        </article>

        <div class="voucher-actions">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i> Print voucher
            </button>
            <a class="btn btn-soft" style="background: #e2e8f0; color: #334155; border: none;" href="<?= e(APP_URL) ?>/?route=<?= e($voucher['voucher_type'] === 'mfs' ? 'mfs' : ($voucher['voucher_type'] === 'sale' ? 'pos' : ($voucher['voucher_type'] === 'purchase' ? 'purchases' : ($voucher['voucher_type'] === 'service' ? 'services' : 'utility')))) ?>">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </main>
</body>
</html>