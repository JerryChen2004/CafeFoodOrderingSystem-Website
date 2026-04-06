<?php  
    require('inc/config.php');
    require('inc/essentials.php');

    $result = $conn->query("SELECT * FROM cafetable");
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
                <h2 class="mb-4 text-center text-white bg-dark">Cafe Table</h2>

                <div class="scrollable-table">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Table</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="<?= $row['status'] == 0 ? 'table-success' : 'table-danger' ?>">
                                    <td class="fw-bold">Table <?= htmlspecialchars($row['tablenumber']) ?></td>
                                    <td>
                                        <?php if ($row['status'] == 0): ?>
                                            <span class="badge bg-success">Empty</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Taken</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 1): ?>
                                            <form method="POST" action="empty_table.php" class="d-inline">
                                                <input type="hidden" name="table_id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-warning btn-sm"
                                                        onclick="return confirm('Empty this table?')">
                                                    Empty
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled>—</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php require('inc/script.php')?>    
    </body>
</html>