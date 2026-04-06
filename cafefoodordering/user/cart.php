<?php
    session_start();
    require('inc/config.php');
    require('inc/essentials.php');

    $orders = $conn->query("
        SELECT id AS order_id, totalprice
        FROM orders
        WHERE table_id = $tablenumber
        ORDER BY time DESC
    ");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=divice-width, initial-scale=1.0">
        <title>Cafe - Menu</title>
        <?php require('inc/link.php')?>

        <style>
            body {
                background-image: url('https://mir-s3-cdn-cf.behance.net/projects/808/5ee6c6228818771.Y3JvcCwyMjY1LDE3NzEsMTg1LDA.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                font-family: Arial, sans-serif;
            }

            .pop:hover{
                border-top-color: var(--teal) !important;
                transform: scale(1.03);
                transition: all 0.3s;
            }

        </style>
    </head>

    <body>
        <script>
            
        </script>

        <div class="container my-5 px-4 bg-white shadow rounded p-2">
            <h2 class="fw-bold h-font text-center">
                Cart - Table <?= isset($_SESSION['tablenumber']) ? str_pad($_SESSION['tablenumber'], 2, '0', STR_PAD_LEFT) : '00'; ?>
            </h2>
            <hr>
            <p class="text-center mt-3">Display of all your selection</p>
        </div>

        <div class="container bg-white shadow rounded p-4">
            <?php if ($orders->num_rows > 0): ?>
                <?php while ($order = $orders->fetch_assoc()): ?>
                    <div class="order-box">

                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Amount</th>
                                    <th>Price (€)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $order_id = $order['order_id'];
                                $items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
                                while ($item = $items->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['item']) ?></td>
                                        <td><?= $item['amount'] ?></td>
                                        <td><?= number_format($item['price'], 2) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                        <p class="text-end fw-bold">Total: €<?= number_format($order['totalprice'], 2) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">You have not placed any orders yet.</p>
            <?php endif; ?>

            <div class="mb-1">
                <button type="button" class="btn text-white custom-bg form-control shadow-none text-center fs-4" onclick="window.location.href='lobby.php'">Return</button>
            </div>

        </div>

            

        <?php require('inc/script.php') ?>
    </body>
</html>