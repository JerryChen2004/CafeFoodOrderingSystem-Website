<?php
    session_start();
    require('inc/config.php');
    $hasTable = isset($_SESSION['tablenumber']);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tablenumber"])) {
        $tablenumber = $_POST["tablenumber"];

        if (isset($_SESSION['tablenumber']) && $_SESSION['tablenumber'] == $tablenumber) {
            header("Location: lobby.php");
            exit;
        }

        $stmt = $conn->prepare("UPDATE cafetable SET status = 1 WHERE tablenumber = ? AND status = 0");
        $stmt->bind_param("i", $tablenumber);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['tablenumber'] = $tablenumber;    
            header("Location: lobby.php");
            exit;
        } else {
            if (isset($_SESSION['tablenumber']) && $_SESSION['tablenumber'] == $tablenumber) {
                header("Location: lobby.php");
                exit;
            }

            echo "<script>alert('This table is already in use. Please select another.');</script>";
        }
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=divice-width, initial-scale=1.0">
        <title>Cafe - Select Table</title>
        <?php require('inc/link.php')?>

        <style>
            div.form{
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 400px;
            }
            body {
                background-image: url('https://static1.squarespace.com/static/58cfd41c17bffcb09bd654f0/5cbf251f6e9a7f5fc69ac2e6/6075c80456f31e1fb98f94a8/1737220693192/unsplash-image-8IKf54pc3qk.jpg?format=1500w');
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
                background-color: black;
            }

        </style>
    </head>

    <body>
        <h1 class="title">Cafe Food Ordering System</h1>
    
        <div class="form text-center rounded bg-white shadow overflow-hidden">
            <form method="POST">
               <div class="p-4">
                    <div class="mb-3">
                        <select name="tablenumber" class="form-control shadow-none text-center" <?php if ($hasTable) echo 'disabled'; ?>>
                            <option disabled selected hidden>
                                <?php echo $hasTable ? 'You already have Table ' . $_SESSION['tablenumber'] : 'Select Your Table'; ?>
                            </option>
                            <?php
                                if (!$hasTable) {
                                    $tables = $conn->query("SELECT tablenumber FROM cafetable WHERE status = 0 ORDER BY tablenumber ASC");
                                    while ($row = $tables->fetch_assoc()) {
                                        echo '<option value="' . $row['tablenumber'] . '">Table ' . $row['tablenumber'] . '</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                        <?php if ($hasTable): ?>
                            <a href="lobby.php" class="btn text-white custom-bg shadow-none text-center">Back to Table</a>
                        <?php else: ?>
                            <button type="submit" class="btn text-white custom-bg shadow-none text-center">Select</button>
                        <?php endif; ?>

               </div>
            </form>
        </div>   

        <?php require('inc/script.php')?>
    </body>
</html>