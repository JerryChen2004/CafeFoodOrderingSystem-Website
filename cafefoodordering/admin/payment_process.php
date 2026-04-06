<?php
require('inc/config.php');
require('inc/essentials.php');

if (!isset($_GET['table_id'])) {
    die("No table selected.");
}

$table_id = intval($_GET['table_id']);

$sql_orders = "
    SELECT * FROM orders
    WHERE table_id = $table_id AND status = 'complete'
";
$orders_res = $conn->query($sql_orders);

if ($orders_res->num_rows === 0) {
    die("No completed orders for this table.");
}

$total = 0;
$order_ids = [];

while ($order = $orders_res->fetch_assoc()) {
    $total += $order['totalprice'];
    $order_ids[] = $order['id'];
}

$stmt = $conn->prepare("INSERT INTO payments (table_id, total, date) VALUES (?, ?, NOW())");
$stmt->bind_param("id", $table_id, $total);
$stmt->execute();
$payment_id = $stmt->insert_id;
$stmt->close();

foreach ($order_ids as $oid) {
    $items_res = $conn->query("SELECT item, amount FROM order_items WHERE order_id = $oid");
    while ($item = $items_res->fetch_assoc()) {
        $stmt_item = $conn->prepare("INSERT INTO payment_items (payment_id, item, amount) VALUES (?, ?, ?)");
        $stmt_item->bind_param("isi", $payment_id, $item['item'], $item['amount']);
        $stmt_item->execute();
        $stmt_item->close();
    }
}

$all_orders_res = $conn->query("SELECT id FROM orders WHERE table_id = $table_id");
$all_order_ids = [];
while ($o = $all_orders_res->fetch_assoc()) {
    $all_order_ids[] = $o['id'];
}

if (!empty($all_order_ids)) {
    $conn->query("DELETE FROM order_items WHERE order_id IN (" . implode(",", $all_order_ids) . ")");
    $conn->query("DELETE FROM orders WHERE id IN (" . implode(",", $all_order_ids) . ")");
}

$conn->query("UPDATE cafetable SET status = 0 WHERE id = $table_id");

header("Location: payment.php");
exit;
?>