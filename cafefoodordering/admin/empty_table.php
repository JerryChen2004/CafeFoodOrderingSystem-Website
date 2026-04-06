<?php
require('inc/config.php');

if (isset($_POST['table_id'])) {
    $table_id = (int)$_POST['table_id'];

    $stmt = $conn->prepare("SELECT id FROM orders WHERE table_id = ?");
    $stmt->bind_param("i", $table_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($order = $result->fetch_assoc()) {
        $order_id = (int)$order['id'];

        $del_items = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        $del_items->bind_param("i", $order_id);
        $del_items->execute();

        $del_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $del_order->bind_param("i", $order_id);
        $del_order->execute();
    }

    $stmt = $conn->prepare("UPDATE cafetable SET status = 0 WHERE id = ?");
    $stmt->bind_param("i", $table_id);
    $stmt->execute();

    header("Location: table.php");
    exit;
} else {
    echo "Invalid request.";
}