<?php
declare(strict_types=1);

final class ModuleController
{
    private array $permissions = [
        'pos' => 'process_sales', 'inventory' => 'manage_products', 'purchases' => 'manage_purchases',
        'services' => 'manage_services', 'mfs' => 'manage_mfs', 'utility' => 'manage_utility', 'utf' => 'manage_utility', 'reports' => 'view_financials',
        'returns' => 'view_financials', 'salaries' => 'view_financials', 'income' => 'view_financials', 'expenses' => 'view_financials',
        'users' => 'manage_users',
    ];

    public function handle(string $page): void
    {
        if (!isset($this->permissions[$page]) || !Auth::can($this->permissions[$page])) {
            http_response_code(403);
            $page = 'forbidden';
        }

        $service = new ModuleService(Database::connection());
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page !== 'forbidden') {
            if (!verify_csrf($_POST['_csrf'] ?? null)) { $error = 'Your session expired. Refresh and try again.'; }
            else {
                try { $voucherId = $this->mutate($service, $page); $_SESSION['flash'] = 'Saved successfully.'; redirect($voucherId ? '?route=voucher&id=' . $voucherId : '?route=' . $page); }
                catch (Throwable $exception) { $error = $exception->getMessage(); }
            }
        }

        if ($page === 'mfs') {
            $data = $this->data($service, $page);
            extract($data, EXTR_SKIP);
            $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
            require dirname(__DIR__, 2) . '/resources/views/modules/mfs.php';
            return;
        }

        if ($page === 'returns' || $page === 'salaries' || $page === 'income' || $page === 'expenses' || $page === 'inventory' || $page === 'users') {
            $data = $this->data($service, $page);
            extract($data, EXTR_SKIP);
            $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
            ob_start();
            require dirname(__DIR__, 2) . '/resources/views/modules/' . $page . '.php';
            $html = (string) ob_get_clean();
            echo str_replace('</body>', '<script>window.IQRA_APP_URL=' . json_encode(APP_URL) . ';</script><script src="' . e(APP_URL) . '/assets/js/app.js"></script></body>', $html);
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
            case 'purchases': return $_POST['action'] === 'supplier' ? null : (int) $service->savePurchase($_POST, $userId);
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
            'purchases' => ['products' => $service->products(), 'suppliers' => $service->suppliers(), 'purchases' => $service->purchases()],
            'services' => ['services' => $service->services(), 'logs' => $service->workLogs()],
            'mfs' => ['accounts' => $service->accounts(), 'transactions' => $service->mfsTransactions(), 'dailyAccounts' => $service->mfsDailySummary(date('Y-m-d')), 'reconciliations' => $service->mfsReconciliations()],
            'utility' => ['vendors' => $service->utilityVendors(), 'payments' => $service->utilityPayments()],
            'utf' => ['vendors' => $service->utilityVendors(), 'payments' => $service->utilityPayments()],
            'reports' => ['report' => $service->report($_GET['start_date'] ?? null, $_GET['end_date'] ?? null)],
            'returns' => ['returns' => $service->returns(), 'source' => !empty($_GET['voucher']) ? $service->returnSource((string) $_GET['voucher']) : null],
            'salaries' => ['employees' => $service->usersWithSalaries(), 'payments' => $service->salaryPayments()],
            'income' => ['incomeRecords' => $service->incomeRecords()],
            'expenses' => ['expenseRecords' => $service->expenseRecords()],
            'users' => ['users' => $service->users(), 'roles' => $service->roles(), 'editUser' => !empty($_GET['edit']) ? $service->user((int) $_GET['edit']) : null],
            default => [],
        };
    }
}