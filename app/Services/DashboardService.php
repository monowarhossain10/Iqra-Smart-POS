<?php
declare(strict_types=1);

final class DashboardService
{
    public function __construct(private readonly PDO $db) {}

    public function summary(): array
    {
        return [
            'sales_today' => $this->scalar("SELECT COALESCE(SUM(total), 0) FROM sales WHERE status = 'paid' AND DATE(created_at) = CURDATE()"),
            'purchases_month' => $this->scalar("SELECT COALESCE(SUM(total), 0) FROM purchase_orders WHERE status != 'cancelled' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())"),
            'service_today' => $this->scalar("SELECT COALESCE(SUM(fee), 0) FROM operator_work_logs WHERE status = 'completed' AND DATE(created_at) = CURDATE()"),
            'utility_today' => $this->scalar("SELECT COALESCE(SUM(bill_amount + service_charge), 0) FROM utility_payments WHERE status = 'paid' AND DATE(created_at) = CURDATE()"),
            'float' => $this->scalar('SELECT COALESCE(SUM(current_float), 0) FROM mfs_accounts WHERE is_active = 1'),
            'low_stock' => (int) $this->scalar('SELECT COUNT(*) FROM products WHERE is_active = 1 AND stock_quantity <= low_stock_threshold'),
        ];
    }

    public function recentSales(): array
    {
        return $this->db->query("SELECT s.invoice_number, COALESCE(s.customer_name, 'Walk-in customer') customer_name, s.total, s.payment_method, s.created_at, u.full_name FROM sales s JOIN users u ON u.id = s.operator_id ORDER BY s.id DESC LIMIT 6")->fetchAll();
    }

    public function lowStockProducts(): array
    {
        return $this->db->query('SELECT name, sku, stock_quantity, low_stock_threshold FROM products WHERE is_active = 1 AND stock_quantity <= low_stock_threshold ORDER BY stock_quantity ASC LIMIT 6')->fetchAll();
    }

    private function scalar(string $query): float
    {
        return (float) $this->db->query($query)->fetchColumn();
    }
}