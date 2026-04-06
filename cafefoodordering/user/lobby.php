<?php
    session_start();
    require('inc/config.php');
    require('inc/essentials.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=divice-width, initial-scale=1.0">
        <title>Cafe</title>
        <?php require('inc/link.php')?>

        <style>
            body {
                background-image: url('https://blogs.airbrickinfra.com/wp-content/uploads/2023/12/cafe-interior-design-1.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                font-family: Arial, sans-serif;
            }
            .title{
                position: center;
                display: flex;
                justify-content: center;
                margin-top: 10%;
                color: white;
            }

        </style>
    </head>

    <body>
        <h1 class="title">Cafe</h1>
    
        <div class="form text-center rounded bg-white shadow overflow-hidden">
            <form method="POST">
                <h4 class="bg-dark text-white py-3">
                    Table <?php echo isset($_SESSION['tablenumber']) ? str_pad($_SESSION['tablenumber'], 2, '0', STR_PAD_LEFT) : '00'; ?>
                </h4>

                <div class="p-4">
                        <div class="mb-3">
                            <button type="button" class="btn text-white custom-bg form-control shadow-none text-center fs-4" onclick="window.location.href='menu.php'">Menu</button>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn text-white custom-bg form-control shadow-none text-center fs-4" onclick="window.location.href='cart.php'">Cart</button>
                        </div>
                        
                </div>
            </form>
        </div> 

        <?php require('inc/script.php')?>
    </body>
</html>