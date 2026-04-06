<?php
    require('inc/config.php');
    require('inc/essentials.php');

    $sql = "SELECT id, status FROM cafetable ORDER BY id ASC";
    $result = $conn->query($sql);

    $pendingOrders = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'preparing'")->fetch_assoc()['total'];

    $completedOrders = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'complete'")->fetch_assoc()['total'];

    $pendingPayments = $conn->query("
        SELECT COUNT(DISTINCT table_id) AS total 
        FROM orders 
        WHERE status = 'complete'
    ")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=divice-width, initial-scale=1.0">
        <title>Cafe Staff - Dashboard</title>
        <?php require('inc/link.php')?>

        <style>
            .table-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 50px;
                margin-top: 20px;
            }
            .table-box {
                padding: 20px;
                color: white;
                text-align: center;
                font-size: 1.2em;
                border-radius: 10px;
                font-weight: bold;
            }
            .available {
                background-color: green;
            }
            .taken {
                background-color: red;
            }
        </style>
    </head>

    <body>
        <?php require('inc/header.php')?>

        <div class="container-fluid mt-5 ">
            <div class="col-lg-10 overflow-hidden ms-auto"> 
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-12 mb-3 p-4 card text-center shadow">
                            <h3 class="mb-4 text-center">Table Status</h3>
                            <div class="table-grid">
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <div class="table-box <?= $row['status'] == 1 ? 'taken' : 'available' ?>">
                                        Table <?= htmlspecialchars($row['id']) ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="row">           
                            <div class="col-md-12 mb-3 p-4 card text-center shadow">
                                <h3 class="mb-4 text-center">Pending Orders</h3>
                                <h1><?= $pendingOrders ?></h1>   
                            </div>

                            <div class="col-md-12 mb-3 p-4 card text-center shadow">
                                <h3 class="mb-4 text-center">Complete Orders</h3>
                                <h1><?= $completedOrders ?></h1>    
                            </div>

                            <div class="col-md-12 mb-3 p-4 card text-center shadow">
                                <h3 class="mb-4 text-center">Pending Payment</h3>
                                <h1><?= $pendingPayments ?></h1>    
                            </div>
                        </div> 
                    </div>
                
                </div>
            </div>  
        </div>

        </div>

        <?php require('inc/script.php')?>    
    </body>
</html>