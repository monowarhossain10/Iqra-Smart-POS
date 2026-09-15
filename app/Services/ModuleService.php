<?php
declare(strict_types=1);

final class ModuleService
{
    public function __construct(private readonly PDO $db) {}

    public function products(): array
    {
        return $this->db->query('SELECT p.*, c.name category_name, s.name supplier_name FROM products p LEFT JOIN categories c ON c.id = p.category_id LEFT JOIN suppliers s ON s.id = p.supplier_id WHERE p.is_active = 1 ORDER BY p.name')->fetchAll();
    }

    public function categories(): array { return $this->db->query('SELECT * FROM categories WHERE is_active = 1 ORDER BY name')->fetchAll(); }
    public function suppliers(): array { return $this->db->query('SELECT * FROM suppliers WHERE is_active = 1 ORDER BY name')->fetchAll(); }
    public function services(): array { return $this->db->query('SELECT * FROM service_types WHERE is_active = 1 ORDER BY name')->fetchAll(); }
    public function accounts(): array { return $this->db->query('SELECT * FROM mfs_accounts WHERE is_active = 1 ORDER BY provider')->fetchAll(); }
    public function mfsReconciliations(): array { return $this->db->query("SELECT r.*, a.provider, a.account_number, u.full_name FROM mfs_daily_reconciliations r JOIN mfs_accounts a ON a.id = r.account_id JOIN users u ON u.id = r.reconciled_by ORDER BY r.business_date DESC, a.provider LIMIT 60")->fetchAll(); }
    public function mfsDailySummary(string $date): array
    {
        $accounts = $this->accounts();
        foreach ($accounts as &$account) {
            $statement = $this->db->prepare("SELECT COALESCE(SUM(CASE WHEN transaction_type = 'cash_in' THEN amount ELSE -amount END), 0) FROM mfs_transactions WHERE account_id = ? AND DATE(created_at) = ?");
            $statement->execute([$account['id'], $date]);
            $account['daily_net'] = (float) $statement->fetchColumn();
            $account['opening_float'] = (float) $account['current_float'] - $account['daily_net'];
            $account['expected_closing'] = (float) $account['current_float'];
        }
        return $accounts;
    }
    public function updateMfsAccount(array $input): void { $this->db->prepare('UPDATE mfs_accounts SET account_number = ? WHERE id = ?')->execute([trim($input['account_number']), (int) $input['account_id']]); }
    public function reconcileMfs(array $input, int $userId): void
    {
        $opening = (float) $input['opening_float']; $expected = (float) $input['expected_closing']; $actual = (float) $input['actual_closing'];
        $this->db->prepare('INSERT INTO mfs_daily_reconciliations (account_id, business_date, opening_float, expected_closing, actual_closing, difference, notes, reconciled_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE opening_float=VALUES(opening_float), expected_closing=VALUES(expected_closing), actual_closing=VALUES(actual_closing), difference=VALUES(difference), notes=VALUES(notes), reconciled_by=VALUES(reconciled_by), reconciled_at=NOW()')->execute([(int) $input['account_id'], $input['business_date'], $opening, $expected, $actual, $actual - $expected, trim($input['notes'] ?? '') ?: null, $userId]);
    }
    public function utilityVendors(): array { return $this->db->query('SELECT * FROM utility_vendors WHERE is_active = 1 ORDER BY name')->fetchAll(); }
    public function saveSupplier(array $input): void { $this->db->prepare('INSERT INTO suppliers (name, contact_person, phone, email, address) VALUES (?, ?, ?, ?, ?)')->execute([trim($input['name']), trim($input['contact_person'] ?? '') ?: null, trim($input['phone'] ?? '') ?: null, trim($input['email'] ?? '') ?: null, trim($input['address'] ?? '') ?: null]); }

    public function sales(): array
    {
        return $this->db->query("SELECT s.*, u.full_name FROM sales s JOIN users u ON u.id = s.operator_id ORDER BY s.id DESC LIMIT 50")->fetchAll();
    }

    public function heldBills(int $userId): array
    {
        $statement = $this->db->prepare('SELECT * FROM pos_held_bills WHERE user_id = ? ORDER BY updated_at DESC LIMIT 20');
        $statement->execute([$userId]);
        $bills = $statement->fetchAll();
        foreach ($bills as &$bill) { $bill['cart'] = json_decode($bill['cart'], true, 512, JSON_THROW_ON_ERROR); }
        return $bills;
    }

    public function heldBill(int $id, int $userId): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM pos_held_bills WHERE id = ? AND user_id = ?');
        $statement->execute([$id, $userId]);
        $bill = $statement->fetch();
        if (!$bill) return null;
        $bill['cart'] = json_decode($bill['cart'], true, 512, JSON_THROW_ON_ERROR);
        return $bill;
    }

    public function holdBill(array $input, array $items, int $userId): void
    {
        if (!$items) throw new RuntimeException('Add at least one product before holding the bill.');
        $payload = [];
        foreach ($items as $item) {
            $product = $this->product((int) ($item['product_id'] ?? 0));
            $quantity = (float) ($item['quantity'] ?? 0);
            if (!$product || $quantity <= 0 || (float) $product['stock_quantity'] < $quantity) throw new RuntimeException('A held item is no longer available in the requested quantity.');
            $payload[] = ['product_id' => (int) $product['id'], 'name' => $product['name'], 'sku' => $product['sku'], 'price' => (float) $product['selling_price'], 'stock' => (float) $product['stock_quantity'], 'quantity' => $quantity];
        }
        $values = [$userId, trim($input['customer_name'] ?? '') ?: null, max(0, (float) ($input['discount'] ?? 0)), $input['payment_method'] ?? 'cash', max(0, (float) ($input['paid_amount'] ?? 0)), json_encode($payload, JSON_THROW_ON_ERROR), trim($input['hold_note'] ?? '') ?: null];
        if (!empty($input['hold_id'])) {
            $this->db->prepare('UPDATE pos_held_bills SET customer_name = ?, discount = ?, payment_method = ?, paid_amount = ?, cart = ?, note = ? WHERE id = ? AND user_id = ?')->execute([$values[1], $values[2], $values[3], $values[4], $values[5], $values[6], (int) $input['hold_id'], $userId]);
            return;
        }
        $this->db->prepare('INSERT INTO pos_held_bills (user_id, customer_name, discount, payment_method, paid_amount, cart, note) VALUES (?, ?, ?, ?, ?, ?, ?)')->execute($values);
    }

    public function deleteHeldBill(int $id, int $userId): void
    {
        $this->db->prepare('DELETE FROM pos_held_bills WHERE id = ? AND user_id = ?')->execute([$id, $userId]);
    }

    public function purchases(): array
    {
        return $this->db->query("SELECT po.*, s.name supplier_name, u.full_name FROM purchase_orders po JOIN suppliers s ON s.id = po.supplier_id JOIN users u ON u.id = po.created_by ORDER BY po.id DESC LIMIT 50")->fetchAll();
    }

    public function workLogs(): array
    {
        return $this->db->query("SELECT w.*, u.full_name, st.name service_name FROM operator_work_logs w JOIN users u ON u.id = w.operator_id LEFT JOIN service_types st ON st.id = w.service_type_id ORDER BY w.id DESC LIMIT 50")->fetchAll();
    }

    public function mfsTransactions(): array
    {
        return $this->db->query("SELECT t.*, a.provider, u.full_name FROM mfs_transactions t JOIN mfs_accounts a ON a.id = t.account_id JOIN users u ON u.id = t.user_id ORDER BY t.id DESC LIMIT 50")->fetchAll();
    }

    public function utilityPayments(): array
    {
        return $this->db->query("SELECT p.*, v.name vendor_name, v.service_type, u.full_name FROM utility_payments p JOIN utility_vendors v ON v.id = p.vendor_id JOIN users u ON u.id = p.user_id ORDER BY p.id DESC LIMIT 50")->fetchAll();
    }

    public function voucherByNumber(string $number): ?array { $statement = $this->db->prepare('SELECT v.*, u.full_name FROM vouchers v JOIN users u ON u.id = v.created_by WHERE v.voucher_number = ?'); $statement->execute([trim($number)]); return $statement->fetch() ?: null; }
    public function returnSource(string $number): array
    {
        $voucher = $this->voucherByNumber($number);
        if (!$voucher || !in_array($voucher['voucher_type'], ['sale', 'purchase'], true)) { throw new RuntimeException('Enter a valid sales or purchase voucher number.'); }
        $table = $voucher['voucher_type'] === 'sale' ? 'sale_items' : 'purchase_order_items';
        $foreign = $voucher['voucher_type'] === 'sale' ? 'sale_id' : 'purchase_order_id';
        $price = $voucher['voucher_type'] === 'sale' ? 'unit_price' : 'unit_cost';
        $statement = $this->db->prepare("SELECT i.product_id, p.name, p.sku, i.quantity original_quantity, i.{$price} unit_price, COALESCE((SELECT SUM(ri.quantity) FROM product_return_items ri JOIN product_returns r ON r.id = ri.return_id WHERE r.source_voucher_id = ? AND ri.product_id = i.product_id), 0) returned_quantity FROM {$table} i JOIN products p ON p.id = i.product_id WHERE i.{$foreign} = ? ORDER BY i.id");
        $statement->execute([(int) $voucher['id'], (int) $voucher['reference_id']]);
        $items = $statement->fetchAll();
        foreach ($items as &$item) { $item['remaining_quantity'] = (float) $item['original_quantity'] - (float) $item['returned_quantity']; }
        return ['voucher' => $voucher, 'items' => $items];
    }
    public function returns(): array { return $this->db->query("SELECT r.*, v.voucher_number source_voucher, u.full_name FROM product_returns r JOIN vouchers v ON v.id = r.source_voucher_id JOIN users u ON u.id = r.processed_by ORDER BY r.id DESC LIMIT 50")->fetchAll(); }
    public function processReturn(array $input, int $userId): string
    {
        $source = $this->returnSource((string) $input['voucher_number']); $voucher = $source['voucher']; $productId = (int) $input['product_id']; $quantity = (float) $input['quantity'];
        $item = null; foreach ($source['items'] as $candidate) { if ((int) $candidate['product_id'] === $productId) { $item = $candidate; break; } }
        if (!$item || $quantity <= 0 || $quantity > (float) $item['remaining_quantity']) { throw new RuntimeException('Return quantity exceeds the remaining voucher quantity.'); }
        $this->db->beginTransaction();
        try {
            if ($voucher['voucher_type'] === 'purchase') { $stock = $this->db->prepare('SELECT stock_quantity FROM products WHERE id = ? FOR UPDATE'); $stock->execute([$productId]); if ((float) $stock->fetchColumn() < $quantity) { throw new RuntimeException('Current stock is lower than the purchase return quantity.'); } }
            $total = $quantity * (float) $item['unit_price']; $returnNumber = 'RET-' . date('YmdHis') . '-' . random_int(100, 999);
            $this->db->prepare('INSERT INTO product_returns (return_number, source_voucher_id, return_type, processed_by, total, reason) VALUES (?, ?, ?, ?, ?, ?)')->execute([$returnNumber, $voucher['id'], $voucher['voucher_type'], $userId, $total, trim($input['reason'] ?? '') ?: null]);
            $returnId = (int) $this->db->lastInsertId(); $this->db->prepare('INSERT INTO product_return_items (return_id, product_id, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?)')->execute([$returnId, $productId, $quantity, $item['unit_price'], $total]);
            $delta = $voucher['voucher_type'] === 'sale' ? $quantity : -$quantity; $this->db->prepare('UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?')->execute([$delta, $productId]);
            $voucherId = $this->createVoucher('return', $returnId, $total, $userId, ['return_number' => $returnNumber, 'source_voucher' => $voucher['voucher_number'], 'return_type' => $voucher['voucher_type'], 'product' => $item['name'], 'quantity' => $quantity, 'reason' => $input['reason'] ?? '']);
            $this->db->commit(); return (string) $voucherId;
        } catch (Throwable $error) { $this->db->rollBack(); throw $error; }
    }
    public function usersWithSalaries(): array { return $this->db->query("SELECT u.id, u.full_name, u.email, r.name role_name, COALESCE(sp.monthly_salary, 0) monthly_salary, COALESCE(sp.allowance, 0) allowance, sp.effective_from FROM users u JOIN roles r ON r.id = u.role_id LEFT JOIN employee_salary_profiles sp ON sp.user_id = u.id AND sp.is_active = 1 WHERE u.is_active = 1 ORDER BY u.full_name")->fetchAll(); }
    public function salaryPayments(): array { return $this->db->query("SELECT sp.*, u.full_name FROM salary_payments sp JOIN users u ON u.id = sp.user_id ORDER BY sp.id DESC LIMIT 50")->fetchAll(); }
    public function saveSalaryProfile(array $input): void { $this->db->prepare('INSERT INTO employee_salary_profiles (user_id, monthly_salary, allowance, effective_from) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE monthly_salary=VALUES(monthly_salary), allowance=VALUES(allowance), effective_from=VALUES(effective_from), is_active=1')->execute([(int) $input['user_id'], (float) $input['monthly_salary'], (float) $input['allowance'], $input['effective_from']]); }
    public function paySalary(array $input, int $paidBy): string
    {
        $profile = $this->db->prepare('SELECT u.full_name, sp.monthly_salary, sp.allowance FROM employee_salary_profiles sp JOIN users u ON u.id = sp.user_id WHERE sp.user_id = ? AND sp.is_active = 1'); $profile->execute([(int) $input['user_id']]); $employee = $profile->fetch(); if (!$employee) throw new RuntimeException('Set an active salary profile before paying this employee.');
        $base = (float) $employee['monthly_salary']; $allowance = (float) $employee['allowance']; $deductions = max(0, (float) $input['deductions']); $net = max(0, $base + $allowance - $deductions); $month = $input['salary_month'] . '-01';
        $this->db->beginTransaction();
        try { $this->db->prepare('INSERT INTO salary_payments (user_id, salary_month, base_salary, allowance, deductions, net_amount, payment_method, paid_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')->execute([(int) $input['user_id'], $month, $base, $allowance, $deductions, $net, $input['payment_method'], $paidBy]); $paymentId = (int) $this->db->lastInsertId(); $this->db->prepare('INSERT INTO expenses (user_id, category, description, amount, expense_date) VALUES (?, ?, ?, ?, CURDATE())')->execute([$paidBy, 'Salary', 'Salary payment - ' . $employee['full_name'] . ' - ' . $input['salary_month'], $net]); $voucherId = $this->createVoucher('salary', $paymentId, $net, $paidBy, ['employee' => $employee['full_name'], 'salary_month' => $input['salary_month'], 'base_salary' => $base, 'allowance' => $allowance, 'deductions' => $deductions, 'payment_method' => $input['payment_method']]); $this->db->commit(); return (string) $voucherId; } catch (Throwable $error) { $this->db->rollBack(); throw $error; }
    }

    public function voucher(int $id): ?array { $statement = $this->db->prepare('SELECT v.*, u.full_name FROM vouchers v JOIN users u ON u.id = v.created_by WHERE v.id = ?'); $statement->execute([$id]); $voucher = $statement->fetch(); if (!$voucher) return null; $voucher['payload'] = json_decode($voucher['payload'], true, 512, JSON_THROW_ON_ERROR); return $voucher; }

    public function report(?string $startDate = null, ?string $endDate = null): array
    {
        $endDate ??= date('Y-m-d');
        $startDate ??= date('Y-m-d', strtotime('-29 days'));
        $start = DateTimeImmutable::createFromFormat('!Y-m-d', $startDate);
        $end = DateTimeImmutable::createFromFormat('!Y-m-d', $endDate);
        if (!$start || !$end || $start > $end) {
            $start = new DateTimeImmutable('today -29 days');
            $end = new DateTimeImmutable('today');
        }
        $startDate = $start->format('Y-m-d');
        $endDate = $end->format('Y-m-d');
        $salesWindow = "s.created_at >= ? AND s.created_at < DATE_ADD(?, INTERVAL 1 DAY)";
        $dateWindow = "created_at >= ? AND created_at < DATE_ADD(?, INTERVAL 1 DAY)";

        $scalar = function (string $query, array $params): float {
            $statement = $this->db->prepare($query);
            $statement->execute($params);
            return (float) $statement->fetchColumn();
        };
        $summary = [
            'sales' => $scalar("SELECT COALESCE(SUM(total), 0) FROM sales WHERE status = 'paid' AND {$dateWindow}", [$startDate, $endDate]),
            'purchases' => $scalar("SELECT COALESCE(SUM(total), 0) FROM purchase_orders WHERE status != 'cancelled' AND {$dateWindow}", [$startDate, $endDate]),
            'profit' => $scalar("SELECT COALESCE(SUM(si.total - (si.quantity * si.unit_cost)), 0) FROM sale_items si JOIN sales s ON s.id = si.sale_id WHERE s.status = 'paid' AND {$salesWindow}", [$startDate, $endDate]),
            'services' => $scalar("SELECT COALESCE(SUM(fee), 0) FROM operator_work_logs WHERE status = 'completed' AND {$dateWindow}", [$startDate, $endDate]),
            'utility' => $scalar("SELECT COALESCE(SUM(bill_amount + service_charge), 0) FROM utility_payments WHERE status = 'paid' AND {$dateWindow}", [$startDate, $endDate]),
            'income' => $scalar("SELECT COALESCE(SUM(amount), 0) FROM income_records WHERE income_date BETWEEN ? AND ?", [$startDate, $endDate]),
            'expenses' => $scalar("SELECT COALESCE(SUM(amount), 0) FROM expenses WHERE expense_date BETWEEN ? AND ?", [$startDate, $endDate]),
        ];
        $summary['net'] = $summary['profit'] + $summary['services'] + $summary['utility'] + $summary['income'] - $summary['expenses'];

        $statement = $this->db->prepare("SELECT p.name, SUM(si.quantity) quantity, SUM(si.total) revenue FROM sale_items si JOIN products p ON p.id = si.product_id JOIN sales s ON s.id = si.sale_id WHERE s.status = 'paid' AND {$salesWindow} GROUP BY p.id ORDER BY revenue DESC LIMIT 8");
        $statement->execute([$startDate, $endDate]);
        $summary['top_products'] = $statement->fetchAll();

        $statement = $this->db->prepare("SELECT payment_method, COUNT(*) transactions, SUM(total) amount FROM sales s WHERE status = 'paid' AND {$dateWindow} GROUP BY payment_method ORDER BY amount DESC");
        $statement->execute([$startDate, $endDate]);
        $summary['payment_mix'] = $statement->fetchAll();

        $statement = $this->db->prepare("SELECT u.full_name, COUNT(*) transactions, SUM(s.total) revenue FROM sales s JOIN users u ON u.id = s.operator_id WHERE s.status = 'paid' AND {$salesWindow} GROUP BY u.id ORDER BY revenue DESC LIMIT 8");
        $statement->execute([$startDate, $endDate]);
        $summary['staff_activity'] = $statement->fetchAll();
        $summary['low_stock'] = $this->db->query('SELECT name, sku, stock_quantity, low_stock_threshold FROM products WHERE is_active = 1 AND stock_quantity <= low_stock_threshold ORDER BY stock_quantity ASC LIMIT 8')->fetchAll();
        $summary['start_date'] = $startDate;
        $summary['end_date'] = $endDate;
        return $summary;
    }

    public function saveProduct(array $input): void
    {
        $statement = $this->db->prepare('INSERT INTO products (category_id, supplier_id, sku, barcode, name, unit, buying_price, selling_price, stock_quantity, low_stock_threshold) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([(int) $input['category_id'], !empty($input['supplier_id']) ? (int) $input['supplier_id'] : null, trim($input['sku']), trim($input['barcode'] ?? '') ?: null, trim($input['name']), trim($input['unit'] ?: 'piece'), (float) $input['buying_price'], (float) $input['selling_price'], (float) $input['stock_quantity'], (float) $input['low_stock_threshold']]);
    }

    public function updateProduct(array $input): void
    {
        $statement = $this->db->prepare('UPDATE products SET category_id = ?, supplier_id = ?, sku = ?, barcode = ?, name = ?, unit = ?, buying_price = ?, selling_price = ?, stock_quantity = stock_quantity + ?, low_stock_threshold = ? WHERE id = ?');
        $statement->execute([(int) $input['category_id'], !empty($input['supplier_id']) ? (int) $input['supplier_id'] : null, trim($input['sku']), trim($input['barcode'] ?? '') ?: null, trim($input['name']), trim($input['unit'] ?: 'piece'), (float) $input['buying_price'], (float) $input['selling_price'], (float) ($input['stock_adjustment'] ?? 0), (float) $input['low_stock_threshold'], (int) $input['product_id']]);
    }

    public function product(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT p.*, c.name category_name, s.name supplier_name FROM products p LEFT JOIN categories c ON c.id = p.category_id LEFT JOIN suppliers s ON s.id = p.supplier_id WHERE p.id = ?');
        $statement->execute([$id]);
        return $statement->fetch() ?: null;
    }

    public function saveSale(array $input, array $items, int $userId): string
    {
        if (!$items) { throw new RuntimeException('Add at least one product to the cart.'); }
        $this->db->beginTransaction();
        try {
            $subtotal = 0; $normalized = [];
            foreach ($items as $item) {
                $product = $this->product((int) $item['product_id']);
                $quantity = (float) $item['quantity'];
                if (!$product || $quantity <= 0 || (float) $product['stock_quantity'] < $quantity) { throw new RuntimeException('One product has insufficient stock.'); }
                $total = $quantity * (float) $product['selling_price']; $subtotal += $total;
                $normalized[] = [$product, $quantity, $total];
            }
            $discount = max(0, (float) ($input['discount'] ?? 0)); $total = max(0, $subtotal - $discount); $invoice = 'INV-' . date('YmdHis') . '-' . random_int(10, 99);
            $sale = $this->db->prepare('INSERT INTO sales (invoice_number, customer_name, operator_id, subtotal, discount, total, paid_amount, payment_method, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $sale->execute([$invoice, trim($input['customer_name'] ?? '') ?: null, $userId, $subtotal, $discount, $total, (float) ($input['paid_amount'] ?? $total), $input['payment_method'] ?? 'cash', 'paid']);
            $saleId = (int) $this->db->lastInsertId();
            $line = $this->db->prepare('INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, unit_cost, total) VALUES (?, ?, ?, ?, ?, ?)');
            $stock = $this->db->prepare('UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?');
            foreach ($normalized as [$product, $quantity, $lineTotal]) { $line->execute([$saleId, $product['id'], $quantity, $product['selling_price'], $product['buying_price'], $lineTotal]); $stock->execute([$quantity, $product['id']]); }
            $voucherId = $this->createVoucher('sale', $saleId, $total, $userId, ['invoice' => $invoice, 'customer' => $input['customer_name'] ?? 'Walk-in customer', 'items' => $normalized, 'payment_method' => $input['payment_method'] ?? 'cash']);
            if (!empty($input['hold_id'])) { $this->db->prepare('DELETE FROM pos_held_bills WHERE id = ? AND user_id = ?')->execute([(int) $input['hold_id'], $userId]); }
            $this->db->commit(); return (string) $voucherId;
        } catch (Throwable $error) { $this->db->rollBack(); throw $error; }
    }

    public function savePurchase(array $input, int $userId): string
    {
        $product = $this->product((int) $input['product_id']); $quantity = (float) $input['quantity']; $cost = (float) $input['unit_cost'];
        if (!$product || $quantity <= 0) { throw new RuntimeException('Select a valid product and quantity.'); }
        $this->db->beginTransaction();
        try { $number = 'PO-' . date('YmdHis') . '-' . random_int(10, 99); $total = $quantity * $cost; $po = $this->db->prepare("INSERT INTO purchase_orders (supplier_id, created_by, order_number, status, subtotal, total, ordered_at, received_at) VALUES (?, ?, ?, 'received', ?, ?, NOW(), NOW())"); $po->execute([(int) $input['supplier_id'], $userId, $number, $total, $total]); $id = $this->db->lastInsertId(); $this->db->prepare('INSERT INTO purchase_order_items (purchase_order_id, product_id, quantity, received_quantity, unit_cost) VALUES (?, ?, ?, ?, ?)')->execute([$id, $product['id'], $quantity, $quantity, $cost]); $this->db->prepare('UPDATE products SET stock_quantity = stock_quantity + ?, buying_price = ? WHERE id = ?')->execute([$quantity, $cost, $product['id']]); $voucherId = $this->createVoucher('purchase', (int) $id, $total, $userId, ['order_number' => $number, 'quantity' => $quantity, 'product' => $product['name'], 'unit_cost' => $cost]); $this->db->commit(); return (string) $voucherId; } catch (Throwable $error) { $this->db->rollBack(); throw $error; }
    }

    public function saveService(array $input, int $userId): string { $this->db->prepare('INSERT INTO operator_work_logs (operator_id, service_type_id, customer_name, customer_phone, task_description, fee, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)')->execute([$userId, (int) $input['service_type_id'], trim($input['customer_name'] ?? '') ?: null, trim($input['customer_phone'] ?? '') ?: null, trim($input['task_description']), (float) $input['fee'], $input['payment_method'] ?? 'cash']); $id = (int) $this->db->lastInsertId(); return (string) $this->createVoucher('service', $id, (float) $input['fee'], $userId, ['customer' => $input['customer_name'] ?? 'Walk-in customer', 'description' => $input['task_description'], 'payment_method' => $input['payment_method'] ?? 'cash']); }

    public function saveMfs(array $input, int $userId): string
    {
        $amount = (float) $input['amount']; if ($amount <= 0) throw new RuntimeException('Enter a valid amount.');
        $this->db->beginTransaction();
        try { $type = $input['transaction_type']; $delta = in_array($type, ['cash_in'], true) ? $amount : -$amount; $balance = $this->db->prepare('SELECT current_float FROM mfs_accounts WHERE id = ? FOR UPDATE'); $balance->execute([(int) $input['account_id']]); $balanceRow = $balance->fetch(); if (!$balanceRow || ($delta < 0 && (float) $balanceRow['current_float'] < abs($delta))) { throw new RuntimeException('Insufficient provider float for this transaction.'); } $this->db->prepare('INSERT INTO mfs_transactions (account_id, user_id, transaction_type, reference_number, customer_name, amount, commission, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')->execute([(int) $input['account_id'], $userId, $type, trim($input['reference_number'] ?? '') ?: null, trim($input['customer_name'] ?? '') ?: null, $amount, (float) ($input['commission'] ?? 0), trim($input['note'] ?? '') ?: null]); $transactionId = (int) $this->db->lastInsertId(); $this->db->prepare('UPDATE mfs_accounts SET current_float = current_float + ? WHERE id = ?')->execute([$delta, (int) $input['account_id']]); $voucherId = $this->createVoucher('mfs', $transactionId, $amount, $userId, ['provider' => $input['account_id'], 'type' => $type, 'reference' => $input['reference_number'] ?? '', 'commission' => $input['commission'] ?? 0]); $this->db->commit(); return (string) $voucherId; } catch (Throwable $error) { $this->db->rollBack(); throw $error; }
    }

    public function saveUtility(array $input, int $userId): string { $bill = (float) $input['bill_amount']; $charge = (float) $input['service_charge']; $this->db->prepare("INSERT INTO utility_payments (vendor_id, user_id, customer_name, account_number, bill_amount, service_charge, status, reference_number, paid_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")->execute([(int) $input['vendor_id'], $userId, trim($input['customer_name'] ?? '') ?: null, trim($input['account_number']), $bill, $charge, $input['status'] ?? 'paid', trim($input['reference_number'] ?? '') ?: null, ($input['status'] ?? 'paid') === 'paid' ? date('Y-m-d H:i:s') : null]); return (string) $this->createVoucher('utility', (int) $this->db->lastInsertId(), $bill + $charge, $userId, ['customer' => $input['customer_name'] ?? 'Walk-in customer', 'account_number' => $input['account_number'], 'bill_amount' => $bill, 'service_charge' => $charge, 'status' => $input['status'] ?? 'paid']); }

    public function incomeRecords(): array
    {
        return $this->db->query("SELECT ir.*, u.full_name FROM income_records ir JOIN users u ON u.id = ir.user_id ORDER BY ir.income_date DESC, ir.id DESC LIMIT 50")->fetchAll();
    }

    public function expenseRecords(): array
    {
        return $this->db->query("SELECT e.*, u.full_name FROM expenses e JOIN users u ON u.id = e.user_id ORDER BY e.expense_date DESC, e.id DESC LIMIT 50")->fetchAll();
    }

    public function saveIncome(array $input, int $userId): string
    {
        $amount = (float) $input['amount'];
        if ($amount <= 0) throw new RuntimeException('Enter a valid amount.');
        $this->db->prepare('INSERT INTO income_records (user_id, category, description, amount, income_date, payment_method) VALUES (?, ?, ?, ?, ?, ?)')->execute([$userId, trim($input['category']), trim($input['description'] ?? '') ?: null, $amount, $input['income_date'] ?? date('Y-m-d'), $input['payment_method'] ?? 'cash']);
        $id = (int) $this->db->lastInsertId();
        return (string) $this->createVoucher('income', $id, $amount, $userId, ['category' => $input['category'], 'description' => $input['description'] ?? '', 'payment_method' => $input['payment_method'] ?? 'cash']);
    }

    public function saveExpense(array $input, int $userId): string
    {
        $amount = (float) $input['amount'];
        if ($amount <= 0) throw new RuntimeException('Enter a valid amount.');
        $this->db->prepare('INSERT INTO expenses (user_id, category, description, amount, expense_date) VALUES (?, ?, ?, ?, ?)')->execute([$userId, trim($input['category']), trim($input['description'] ?? '') ?: null, $amount, $input['expense_date'] ?? date('Y-m-d')]);
        $id = (int) $this->db->lastInsertId();
        return (string) $this->createVoucher('expense', $id, $amount, $userId, ['category' => $input['category'], 'description' => $input['description'] ?? '']);
    }

    private function createVoucher(string $type, int $referenceId, float $amount, int $userId, array $payload): int { $number = strtoupper($type) . '-' . date('YmdHis') . '-' . random_int(100, 999); $statement = $this->db->prepare('INSERT INTO vouchers (voucher_number, voucher_type, reference_id, created_by, amount, payload) VALUES (?, ?, ?, ?, ?, ?)'); $statement->execute([$number, $type, $referenceId, $userId, $amount, json_encode($payload, JSON_THROW_ON_ERROR)]); return (int) $this->db->lastInsertId(); }

    public function users(): array
    {
        return $this->db->query("SELECT u.*, r.name role_name FROM users u JOIN roles r ON r.id = u.role_id ORDER BY u.full_name")->fetchAll();
    }

    public function roles(): array
    {
        return $this->db->query('SELECT * FROM roles ORDER BY name')->fetchAll();
    }

    public function saveUser(array $input): void
    {
        $hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);
        $this->db->prepare('INSERT INTO users (role_id, full_name, email, password_hash, phone) VALUES (?, ?, ?, ?, ?)')->execute([(int) $input['role_id'], trim($input['full_name']), trim($input['email']), $hashedPassword, trim($input['phone'] ?? '') ?: null]);
    }

    public function updateUser(array $input): void
    {
        $params = [(int) $input['role_id'], trim($input['full_name']), trim($input['email']), trim($input['phone'] ?? '') ?: null, (int) $input['user_id']];
        $query = 'UPDATE users SET role_id = ?, full_name = ?, email = ?, phone = ? WHERE id = ?';
        
        if (!empty($input['password'])) {
            $hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);
            $query = 'UPDATE users SET role_id = ?, full_name = ?, email = ?, password_hash = ?, phone = ? WHERE id = ?';
            $params = [(int) $input['role_id'], trim($input['full_name']), trim($input['email']), $hashedPassword, trim($input['phone'] ?? '') ?: null, (int) $input['user_id']];
        }
        
        $this->db->prepare($query)->execute($params);
    }

    public function toggleUserStatus(int $userId): void
    {
        $this->db->prepare('UPDATE users SET is_active = NOT is_active WHERE id = ?')->execute([$userId]);
    }

    public function user(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT u.*, r.name role_name FROM users u JOIN roles r ON r.id = u.role_id WHERE u.id = ?');
        $statement->execute([$id]);
        return $statement->fetch() ?: null;
    }
}