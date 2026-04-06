<?php
    if (!isset($_SESSION['tablenumber'])) {
        header("Location: index.php");
        exit;
    }

    $tablenumber = $_SESSION['tablenumber'];

    $stmt = $conn->prepare("SELECT status FROM cafetable WHERE tablenumber = ?");
    $stmt->bind_param("i", $tablenumber);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    if ($status == 0) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }
?>