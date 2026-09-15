<?php
declare(strict_types=1);

final class ApiController
{
    public function products(): never
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!Auth::check() || !Auth::can('process_sales')) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $query = trim((string) ($_GET['q'] ?? ''));
        $statement = Database::connection()->prepare(
            'SELECT id, sku, barcode, name, selling_price, buying_price, stock_quantity FROM products WHERE is_active = 1 AND (name LIKE :query OR sku LIKE :query OR barcode LIKE :query) ORDER BY name LIMIT 12'
        );
        $statement->execute(['query' => '%' . $query . '%']);
        echo json_encode(['data' => $statement->fetchAll()], JSON_THROW_ON_ERROR);
        exit;
    }
}