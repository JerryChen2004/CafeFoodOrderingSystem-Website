<?php
    require('inc/config.php');
    require('inc/essentials.php');

    $sql = "SELECT p.id, p.table_id, p.total, p.date, c.tablenumber AS table_name
        FROM payments p
        LEFT JOIN cafetable c ON p.table_id = c.id
        ORDER BY p.date DESC";
    $res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=divice-width, initial-scale=1.0">
        <title>Cafe Staff - Payment History</title>
        <?php require('inc/link.php')?>

        <style>
            
        </style>
    </head>

    <body>
        <?php require('inc/header.php')?>

        <div class="container-fluid mt-5 ">
            <div class="col-lg-10 overflow-hidden ms-auto"> 
                <h2 class="mb-4 text-center text-white bg-dark">Cafe Table</h2>

                <div class="scrollable-table">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Table</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th>Items</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($res && $res->num_rows > 0): ?>
                                <?php while ($row = $res->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['table_name'] ?? 'Table '.$row['table_id']) ?></td>
                                        <td>€ <?= number_format($row['total'], 2) ?></td>
                                        <td><?= htmlspecialchars($row['date']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $items_res = $conn->query("SELECT item, amount FROM payment_items WHERE payment_id = " . intval($row['id']));
                                            while ($item = $items_res->fetch_assoc()) {
                                                echo htmlspecialchars($item['item']) . " (x" . $item['amount'] . ")<br>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No payment history found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                            
                    </table>
                </div>
            </div>
        </div>

        <?php require('inc/script.php')?>    
    </body>
</html>