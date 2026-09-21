<?php
declare(strict_types=1);

final class ModuleController
{
    private array $permissions = [
        'pos' => 'process_sales', 'sales' => 'process_sales', 'inventory' => 'manage_products', 'purchases' => 'manage_purchases',
        'services' => 'manage_services', 'mfs' => 'manage_mfs', 'utility' => 'manage_utility', 'utf' => 'manage_utility', 'reports' => 'view_financials', 'accounts' => 'view_financials',
        'returns' => 'view_financials', 'salaries' => 'view_financials', 'income' => 'view_financials', 'expenses' => 'view_financials',
        'users' => 'manage_users', 'vouchers' => 'view_financials',
    ];

    public function handle(string $page): void
    {
        if (!isset($this->permissions[$page]) || !Auth::can($this->permissions[$page])) {
            http_response_code(403);
            $page = 'forbidden';
        }

        try {
            $service = new ModuleService(Database::connection());
        } catch (Throwable $exception) {
            error_log("Database connection failed: " . $exception->getMessage());
            http_response_code(500);
            echo "<h1>Database Error</h1><p>Unable to connect to the database. Please check your configuration.</p>";
            return;
        }

        if ($page === 'reports' && ($_GET['export'] ?? '') === 'excel') {
            $this->exportReportWorkbook($this->data($service, 'reports')['report']);
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page !== 'forbidden') {
            if (!verify_csrf($_POST['_csrf'] ?? null)) { $error = 'Your session expired. Refresh and try again.'; }
            else {
                try { $voucherId = $this->mutate($service, $page); $_SESSION['flash'] = 'Saved successfully.'; redirect($voucherId ? '?route=voucher&id=' . $voucherId : '?route=' . $page); }
                catch (Throwable $exception) { $error = $exception->getMessage(); error_log("Mutation error: " . $exception->getMessage()); }
            }
        }

        if ($page === 'mfs') {
            $data = $this->data($service, $page);
            $data['page'] = $page;
            $data['error'] = $error;
            extract($data, EXTR_SKIP);
            $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
            require dirname(__DIR__, 2) . '/resources/views/layouts/module.php';
            return;
        }

        if ($page === 'returns' || $page === 'salaries' || $page === 'income' || $page === 'expenses' || $page === 'inventory' || $page === 'users' || $page === 'vouchers') {
            $data = $this->data($service, $page);
            $data['page'] = $page;
            $data['error'] = $error;
            extract($data, EXTR_SKIP);
            $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
            require dirname(__DIR__, 2) . '/resources/views/layouts/module.php';
            return;
        }

        $data = $this->data($service, $page);
        $data['page'] = $page;
        $data['error'] = $error;
        extract($data, EXTR_SKIP);
        $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
        require dirname(__DIR__, 2) . '/resources/views/layouts/module.php';
    }

    private function mutate(ModuleService $service, string $page): ?int
    {
        $userId = (int) Auth::user()['id'];
        switch ($page) {
            case 'inventory':
                if (($_POST['action'] ?? '') === 'edit') { $service->updateProduct($_POST); return null; }
                $service->saveProduct($_POST); return null;
            case 'pos':
                $items = json_decode((string) ($_POST['cart'] ?? '[]'), true, 512, JSON_THROW_ON_ERROR);
                if (($_POST['action'] ?? '') === 'hold') { $service->holdBill($_POST, $items, $userId); return null; }
                if (($_POST['action'] ?? '') === 'delete_hold') { $service->deleteHeldBill((int) ($_POST['hold_id'] ?? 0), $userId); return null; }
                return (int) $service->saveSale($_POST, $items, $userId);
            case 'purchases':
                if (($_POST['action'] ?? '') === 'supplier') { $service->saveSupplier($_POST); return null; }
                return (int) $service->savePurchase($_POST, $userId);
            case 'services': return (int) $service->saveService($_POST, $userId);
            case 'utility':
            case 'utf': return (int) $service->saveUtility($_POST, $userId);
            case 'mfs':
                if (($_POST['action'] ?? '') === 'account') { $service->updateMfsAccount($_POST); return null; }
                if (($_POST['action'] ?? '') === 'reconcile') { $service->reconcileMfs($_POST, $userId); return null; }
                return (int) $service->saveMfs($_POST, $userId);
            case 'returns': return (int) $service->processReturn($_POST, $userId);
            case 'salaries':
                if (($_POST['action'] ?? '') === 'profile') { $service->saveSalaryProfile($_POST); return null; }
                return (int) $service->paySalary($_POST, $userId);
            case 'income':
                return (int) $service->saveIncome($_POST, $userId);
            case 'expenses':
                return (int) $service->saveExpense($_POST, $userId);
            case 'users':
                if (($_POST['action'] ?? '') === 'edit') { $service->updateUser($_POST); return null; }
                if (($_POST['action'] ?? '') === 'toggle') { $service->toggleUserStatus((int) $_POST['user_id']); return null; }
                $service->saveUser($_POST); return null;
        }
        return null;
    }

    private function data(ModuleService $service, string $page): array
    {
        return match ($page) {
            'inventory' => ['products' => $service->products(), 'categories' => $service->categories(), 'suppliers' => $service->suppliers(), 'editProduct' => !empty($_GET['edit']) ? $service->product((int) $_GET['edit']) : null],
            'pos' => ['products' => $service->products(), 'sales' => $service->sales(), 'heldBills' => $service->heldBills((int) Auth::user()['id']), 'resumeHold' => !empty($_GET['resume_hold']) ? $service->heldBill((int) $_GET['resume_hold'], (int) Auth::user()['id']) : null],
            'sales' => ['sales' => $service->sales($_GET['search'] ?? null, $_GET['from'] ?? null, $_GET['to'] ?? null, $_GET['status'] ?? null)],
            'purchases' => ['products' => $service->products(), 'suppliers' => $service->suppliers(), 'purchases' => $service->purchases()],
            'services' => ['services' => $service->services(), 'logs' => $service->workLogs()],
            'mfs' => ['accounts' => $service->accounts(), 'transactions' => $service->mfsTransactions(), 'dailyAccounts' => $service->mfsDailySummary(date('Y-m-d')), 'reconciliations' => $service->mfsReconciliations()],
            'utility' => ['vendors' => $service->utilityVendors(), 'payments' => $service->utilityPayments()],
            'utf' => ['vendors' => $service->utilityVendors(), 'payments' => $service->utilityPayments()],
            'reports' => ['report' => $service->report($_GET['start_date'] ?? null, $_GET['end_date'] ?? null)],
            'accounts' => ['accountsOverview' => $service->accountsOverview()],
            'returns' => $this->returnsData($service),
            'salaries' => ['employees' => $service->usersWithSalaries(), 'payments' => $service->salaryPayments()],
            'income' => ['incomeRecords' => $service->incomeRecords()],
            'expenses' => ['expenseRecords' => $service->expenseRecords()],
            'users' => ['users' => $service->users(), 'roles' => $service->roles(), 'editUser' => !empty($_GET['edit']) ? $service->user((int) $_GET['edit']) : null],
            'vouchers' => ['allVouchers' => $service->allVouchers()],
            default => [],
        };
    }

    private function returnsData(ModuleService $service): array
    {
        $data = ['returns' => $service->returns(), 'source' => null, 'returnError' => null];
        $voucherNumber = trim((string) ($_GET['voucher'] ?? ''));
        if ($voucherNumber === '') return $data;

        try {
            $data['source'] = $service->returnSource($voucherNumber);
        } catch (RuntimeException $exception) {
            $data['returnError'] = $exception->getMessage();
        }
        return $data;
    }

    /**
     * Writes an Excel 2003 XML workbook. Excel opens this format directly,
     * without requiring a server-side spreadsheet library.
     */
    private function exportReportWorkbook(array $report): never
    {
        $filename = sprintf('iqra-report-%s-to-%s.xml', $report['start_date'], $report['end_date']);
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-store, no-cache, must-revalidate');

        $cell = static function (string|int|float $value, bool $number = false): string {
            if (!$number && is_string($value) && preg_match('/^[=+@-]/', $value)) {
                $value = "'" . $value;
            }
            $type = $number ? 'Number' : 'String';
            return '<Cell><Data ss:Type="' . $type . '">' . htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</Data></Cell>';
        };
        $row = static function (array $cells, bool $header = false) use ($cell): string {
            return '<Row>' . implode('', array_map(static fn ($value): string => str_replace('<Cell>', $header ? '<Cell ss:StyleID="header">' : '<Cell>', $cell($value, is_int($value) || is_float($value))), $cells)) . '</Row>';
        };

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
            . '<Styles><Style ss:ID="header"><Font ss:Bold="1"/><Interior ss:Color="#DDEFEA" ss:Pattern="Solid"/></Style></Styles>'
            . '<Worksheet ss:Name="Financial Report"><Table>';
        $xml .= $row(['Iqra Stationary Solutions — Financial Report'])
            . $row(['Period', $report['start_date'] . ' to ' . $report['end_date']])
            . $row([])
            . $row(['Summary', 'Amount (BDT)'], true);
        foreach (['Sales' => 'sales', 'Purchases' => 'purchases', 'Gross profit' => 'profit', 'Services' => 'services', 'Utility' => 'utility', 'Income' => 'income', 'Expenses' => 'expenses', 'Net operating result' => 'net'] as $label => $key) {
            $xml .= $row([$label, (float) ($report[$key] ?? 0)]);
        }
        $xml .= $row([]) . $row(['Top products'], true) . $row(['Product', 'Units', 'Revenue (BDT)'], true);
        foreach ($report['top_products'] ?? [] as $product) {
            $xml .= $row([(string) $product['name'], (float) $product['quantity'], (float) $product['revenue']]);
        }
        $xml .= $row([]) . $row(['Sales by payment method'], true) . $row(['Method', 'Transactions', 'Amount (BDT)'], true);
        foreach ($report['payment_mix'] ?? [] as $payment) {
            $xml .= $row([ucfirst((string) $payment['payment_method']), (int) $payment['transactions'], (float) $payment['amount']]);
        }
        $xml .= $row([]) . $row(['Sales by operator'], true) . $row(['Staff member', 'Transactions', 'Revenue (BDT)'], true);
        foreach ($report['staff_activity'] ?? [] as $staff) {
            $xml .= $row([(string) $staff['full_name'], (int) $staff['transactions'], (float) $staff['revenue']]);
        }
        $xml .= $row([]) . $row(['Low-stock watchlist'], true) . $row(['Product', 'SKU', 'On hand', 'Low-stock level'], true);
        foreach ($report['low_stock'] ?? [] as $item) {
            $xml .= $row([(string) $item['name'], (string) $item['sku'], (float) $item['stock_quantity'], (float) $item['low_stock_threshold']]);
        }
        echo $xml . '</Table></Worksheet></Workbook>';
        exit;
    }
}
