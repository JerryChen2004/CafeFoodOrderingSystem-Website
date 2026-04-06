<?php
    $hname = 'localhost';
    $uname = 'root';
    $pass = '';
    $db = 'cafefoodordering';

    $conn = mysqli_connect($hname, $uname, $pass,$db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>