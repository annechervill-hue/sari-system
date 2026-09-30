<?php
require_once "config.php";

function getProducts($conn) {
    $sql = "SELECT * FROM products ORDER BY name ASC";
    $result = $conn->query($sql);
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    return $products;
}

function getProductById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function insertSale($conn, $saleData) {
    $receiptNo = $saleData['receipt_no'];
    $subtotal = $saleData['subtotal'];
    $discount = $saleData['discount'];
    $total = $saleData['total'];

    $stmt = $conn->prepare("INSERT INTO sales (sale_date, subtotal, discount, total, receipt_no) VALUES (NOW(), ?, ?, ?, ?)");
    $stmt->bind_param("ddds", $subtotal, $discount, $total, $receiptNo);

    if (!$stmt->execute()) {
        return false;
    }

    $saleId = $conn->insert_id;
    return $saleId;
}

function insertSaleItems($conn, $saleId, $items) {
    $stmt = $conn->prepare("INSERT INTO sale_items (sale_id, product_id, product_name, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($items as $item) {
        $productId = $item['product_id'];
        $productName = $item['product_name'];
        $quantity = $item['quantity'];
        $unitPrice = $item['unit_price'];
        $total = $item['total'];

        $stmt->bind_param("iissdd", $saleId, $productId, $productName, $quantity, $unitPrice, $total);
        if (!$stmt->execute()) {
            return false;
        }
    }
    return true;
}

function updateInventoryAfterSale($conn, $items) {
    foreach ($items as $item) {
        $productId = $item['product_id'];
        $quantity = $item['quantity'];

        $stmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $productId);
        if (!$stmt->execute()) {
            return false;
        }
    }
    return true;
}

function getSalesReport($conn, $type, $startDate = null, $endDate = null) {
    $query = "SELECT * FROM sales WHERE 1=1";
    $params = [];
    $types = "";

    if ($startDate && $endDate) {
        $query .= " AND sale_date BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
        $types .= "ss";
    }

    $query .= " ORDER BY sale_date DESC";
    $stmt = $conn->prepare($query);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $sales = [];

    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }

    return $sales;
}