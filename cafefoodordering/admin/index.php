<?php
    require('inc/config.php');
    require('inc/essentials.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=divice-width, initial-scale=1.0">
        <title>Cafe Staff - login</title>
        <?php require('inc/link.php')?>

        <style>
            div.login-form{
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 400px;
            }
        </style>
    </head>

    <body>
        <div class="login-form text-center rounded bg-white shadow overflow-hidden">
            <form method="POST">
               <h4 class="bg-dark text-white py-3">Cafe - Staff Panel Login</h4> 
               <div class="p-4">
                    <div class="mb-3">
                        <input name="admin_name" required type="text" class="form-control shadow-none text-center" placeholder="Staff Name">
                    </div>
                    <div class="mb-3">
                        <input name="admin_password" required type="password" class="form-control shadow-none text-center" placeholder="Password">
                    </div>
                    <button name="login" type="sumbit" class="btn text-white custom-bg shadow-none text-center">Login</button>
               </div>
            </form>
        </div>

        <?php
            if(isset($_POST['login'])){
                $frm_data = filteration($_POST);

                $query = "SELECT * FROM admin_cred WHERE admin_name=? AND admin_password = ?";
                $value = [$frm_data['admin_name'],$frm_data['admin_password']];

                $res = select($query, $value, "ss");
                if($res->num_rows==1){
                    $row = mysqli_fetch_assoc($res);
                    $_SESSION['adminLogin'] = true;
                    $_SESSION['adminId'] = $row['sr_no'];
                    redirect('dashboard.php');
                }else{
                    alert('error', 'Login failed - Invalid Credentials');
                }
            }
        ?>

        <?php require('inc/script.php')?>    
    </body>
</html>