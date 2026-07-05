<?php
session_start();
require "config/database.php";
require "auth_check.php";

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$total = 0;

foreach ($_SESSION['cart'] as $id => $qty) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id=?");
    $stmt->execute([$id]);
    $price = $stmt->fetchColumn();

    $total += $price * $qty;
}
?>

<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<div class="container py-5 text-center">

<h2>Payment</h2>

<h3>Total: $<?= $total ?></h3>

<form method="POST" action="success.php">

<input type="hidden" name="total" value="<?= $total ?>">

<button class="btn btn-success btn-lg mt-3">
    Pay Now
</button>

</form>

</div>

<?php include "includes/footer.php"; ?>