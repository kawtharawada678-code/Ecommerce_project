<?php
session_start();
require "config/database.php";
require "auth_check.php";

$user_id = $_SESSION['user_id'];

$total = 0;

// calculate total
foreach ($_SESSION['cart'] as $id => $qty) {

    $stmt = $pdo->prepare("SELECT price FROM products WHERE id=?");
    $stmt->execute([$id]);
    $price = $stmt->fetchColumn();

    $total += $price * $qty;
}

// insert order (NOW USING user_id)
$stmt = $pdo->prepare("INSERT INTO orders (user_id, name, address, phone, total_price)
VALUES (?, ?, ?, ?, ?)");

$stmt->execute([
    $user_id,
    $_POST['name'],
    $_POST['address'],
    $_POST['phone'],
    $total
]);

$order_id = $pdo->lastInsertId();

// insert items
foreach ($_SESSION['cart'] as $id => $qty) {

    $stmt = $pdo->prepare("SELECT price FROM products WHERE id=?");
    $stmt->execute([$id]);
    $price = $stmt->fetchColumn();

    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price)
    VALUES (?, ?, ?, ?)");

    $stmt->execute([$order_id, $id, $qty, $price]);
}

// clear cart
unset($_SESSION['cart']);

echo "<h2>Order placed successfully!</h2>";
echo "<a href='profile.php'>Go to My Orders</a>";