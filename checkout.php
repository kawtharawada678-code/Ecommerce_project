<?php
require "config/database.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$user = $_SESSION['user'];
$total = 0;

foreach ($_SESSION['cart'] as $id => $qty) {

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$id]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $total += $product['price'] * $qty;
    }
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $address = trim($_POST["address"]);

    if (!empty($address)) {

        $pdo->beginTransaction();

        try {

            $stmt = $pdo->prepare("
            INSERT INTO orders
            (user_id,total_amount,shipping_address,status)
            VALUES(?,?,?,'pending')
            ");

            $stmt->execute([
                $user["id"],
                $total,
                $address
            ]);

            $orderID = $pdo->lastInsertId();

            foreach ($_SESSION['cart'] as $id => $qty) {

                $stmt = $pdo->prepare("
                SELECT price
                FROM products
                WHERE id=?
                ");

                $stmt->execute([$id]);

                $product = $stmt->fetch();

                $stmt = $pdo->prepare("
                INSERT INTO order_items
                (order_id,product_id,quantity,price_at_purchase)
                VALUES(?,?,?,?)
                ");

                $stmt->execute([
                    $orderID,
                    $id,
                    $qty,
                    $product["price"]
                ]);

                $update = $pdo->prepare("
                UPDATE products
                SET stock=stock-?
                WHERE id=?
                ");

                $update->execute([$qty,$id]);

            }

            $pdo->commit();

            unset($_SESSION["cart"]);

            header("Location: success.php?id=".$orderID);

            exit;

        } catch(Exception $e){

            $pdo->rollBack();

            $message="Something went wrong.";

        }

    }

}

include "includes/header.php";
include "includes/navbar.php";
?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow-lg border-0">

<div class="card-body p-5">

<h2>Checkout</h2>

<?php if($message!=""){ ?>

<div class="alert alert-danger">

<?= $message ?>

</div>

<?php } ?>

<h4 class="mt-4">

Order Total

<strong>

$<?= number_format($total,2) ?>

</strong>

</h4>

<form method="POST">

<div class="mt-4">

<label>

Shipping Address

</label>

<textarea
name="address"
rows="5"
class="form-control"
required></textarea>

</div>

<button
class="btn btn-success btn-lg mt-4 rounded-pill">

Place Order

</button>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include "includes/footer.php"; ?>