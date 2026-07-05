<?php
require "auth.php";
require "config/database.php";

if ($_POST) {

    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("
        UPDATE orders SET status=? WHERE id=?
    ");

    $stmt->execute([$status, $order_id]);
}

header("Location: orders.php");
exit;