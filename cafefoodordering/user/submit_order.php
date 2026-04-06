<?php
require('inc/config.php');

$data = json_decode(file_get_contents('php://input'), true);

$table_id = $data['table'];
$items = $data['items'];

if (!$table_id || empty($items)) {
    http_response_code(400);
    echo "Invalid order.";
    exit;
}

$total = 0;
foreach ($items as $item) {
    $total += floatval($item['subtotal']);
}

$stmt = $conn->prepare("INSERT INTO orders (table_id, totalprice) VALUES (?, ?)");
$stmt->bind_param("id", $table_id, $total);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

$itemStmt = $conn->prepare("INSERT INTO order_items (order_id, item, amount, price) VALUES (?, ?, ?, ?)");
foreach ($items as $item) {
    $itemStmt->bind_param(
        "isid",
        $order_id,
        $item['item'],
        $item['amount'],
        $item['price'],
    );
    $itemStmt->execute();
}
$itemStmt->close();

echo "Order submitted successfully.";
?>