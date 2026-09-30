<?php
require 'config.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['items'])) {
        echo json_encode(["success" => false, "message" => "Invalid request"]);
        exit;
    }

    $items = $data['items'];
    $discountPercent = floatval($data['discount'] ?? 0);

    $subtotal = 0;
    foreach ($items as $item) {
        $productId = $item['product_id'];
        $qty = intval($item['quantity']);

        $productResult = $conn->query("SELECT * FROM products WHERE id = $productId");
        $product = $productResult->fetch_assoc();

        if (!$product) {
            echo json_encode(["success" => false, "message" => "Product not found"]);
            exit;
        }

        if ($product['quantity'] < $qty) {
            echo json_encode(["success" => false, "message" => "Not enough stock for " . $product['name']]);
            exit;
        }

        $subtotal += $product['price'] * $qty;
    }

    $discount = $subtotal * ($discountPercent / 100);
    $total = $subtotal - $discount;
    $receiptNo = "POS-" . date("YmdHis") . "-" . rand(100, 999);

    $stmt = $conn->prepare("INSERT INTO sales (sale_date, subtotal, discount, total, receipt_no) VALUES (NOW(), ?, ?, ?, ?)");
    $stmt->bind_param("ddds", $subtotal, $discount, $total, $receiptNo);
    $stmt->execute();

    $saleId = $conn->insert_id;

    foreach ($items as $item) {
        $productId = $item['product_id'];
        $qty = intval($item['quantity']);

        $productResult = $conn->query("SELECT * FROM products WHERE id = $productId");
        $product = $productResult->fetch_assoc();

        $lineTotal = $product['price'] * $qty;

        $itemStmt = $conn->prepare("INSERT INTO sale_items (sale_id, product_id, product_name, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?, ?)");
        $itemStmt->bind_param("iissdd", $saleId, $productId, $product['name'], $qty, $product['price'], $lineTotal);
        $itemStmt->execute();

        $conn->query("UPDATE products SET quantity = quantity - $qty WHERE id = $productId");
    }

    echo json_encode([
      "success" => true,
      "receipt_no" => $receiptNo,
      "subtotal" => number_format($subtotal, 2, ".", ""),
      "discount" => number_format($discount, 2, ".", ""),
      "total" => number_format($total, 2, ".", "")
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'products') {
        $result = $conn->query("SELECT * FROM products ORDER BY name ASC");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        exit;
    }

    if (isset($_GET['action']) && $_GET['action'] === 'report') {
        $start = $_GET['start_date'] ?? null;
        $end = $_GET['end_date'] ?? null;

        $query = "SELECT * FROM sales WHERE 1=1";
        if ($start) $query .= " AND sale_date >= '$start 00:00:00'";
        if ($end) $query .= " AND sale_date <= '$end 23:59:59'";
        $query .= " ORDER BY sale_date DESC";

        $result = $conn->query($query);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        exit;
    }
}