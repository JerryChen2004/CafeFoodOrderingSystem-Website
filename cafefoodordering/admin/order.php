<?php
    require('inc/config.php');
    require('inc/essentials.php');

    if (isset($_POST['complete_id'])) {
        $order_id = intval($_POST['complete_id']);
        $sql = "UPDATE orders SET status='complete' WHERE id=?";
        update($sql, [$order_id], "i");
    }

    if (isset($_POST['cancel_id'])) {
        $order_id = intval($_POST['cancel_id']);
        
        $sql = "DELETE FROM order_items WHERE order_id=?";
        delete($sql, [$order_id], "i");
        
        $sql = "DELETE FROM orders WHERE id=?";
        delete($sql, [$order_id], "i");
    }

    $query = "
        SELECT o.id, o.table_id, o.totalprice, o.status, o.time,
            GROUP_CONCAT(CONCAT(oi.item, ' x', oi.amount) SEPARATOR ', ') as items
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        GROUP BY o.id
        ORDER BY o.time DESC
    ";
    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=divice-width, initial-scale=1.0">
        <title>Cafe Staff - Order</title>
        <?php require('inc/link.php')?>

        <style>
            
        </style>
    </head>

    <body>
        <?php require('inc/header.php')?>

        <div class="container-fluid mt-5 ">
            <div class="col-lg-10 overflow-hidden ms-auto"> 
                <h2 class="mb-4 text-center text-white bg-dark">Cafe Order</h2>

                <div class="scrollable-table">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Table</th>
                                <th>Items</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) === 0): ?>
                                <tr>
                                    <td colspan="6" class="text-muted">No orders found</td>
                                </tr>
                            <?php else: ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td>Table <?= $row['table_id'] ?></td>
                                        <td><?= htmlspecialchars($row['items']) ?></td>
                                        <td>€ <?= number_format($row['totalprice'], 2) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $row['status'] === 'complete' ? 'success' : ($row['status'] === 'cancel' ? 'danger' : 'warning') ?>">
                                                <?= ucfirst($row['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= $row['time'] ?></td>
                                        <td>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="complete_id" value="<?= $row['id'] ?>">
                                                <button class="btn btn-success btn-sm" <?= $row['status'] === 'complete' ? 'disabled' : '' ?>>Complete</button>
                                            </form>
                                            <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                                <input type="hidden" name="cancel_id" value="<?= $row['id'] ?>">
                                                <button class="btn btn-danger btn-sm">Cancel</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php require('inc/script.php')?>    
    </body>
</html>