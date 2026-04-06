<?php  
    require('inc/config.php');
    require('inc/essentials.php');

    $sql = "
        SELECT table_id, SUM(totalprice) AS total_amount
        FROM orders
        WHERE status = 'complete'
        GROUP BY table_id
        ORDER BY table_id ASC
    ";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=divice-width, initial-scale=1.0">
        <title>Cafe Staff - Table</title>
        <?php require('inc/link.php')?>

        <style>
            
        </style>
    </head>

    <body>
        <?php require('inc/header.php')?>

        <div class="container-fluid mt-5 ">
            <div class="col-lg-10 overflow-hidden ms-auto"> 
                <h2 class="mb-4 text-center text-white bg-dark">Cafe Payment</h2>

                <div class="scrollable-table">
                    <?php if ($result->num_rows > 0): ?>
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Table</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>Table <?= htmlspecialchars($row['table_id']) ?></td>
                                        <td>€ <?= number_format($row['total_amount'], 2) ?></td>
                                        <td>
                                            <a href="payment_process.php?table_id=<?= $row['table_id'] ?>" 
                                            class="btn btn-success btn-sm">Pay</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-warning">No tables with completed orders.</div>
                    <?php endif; ?>                    
                </div>
            </div>
        </div>

        <?php require('inc/script.php')?>    
    </body>
</html>