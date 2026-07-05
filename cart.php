<?php
require "config/database.php";
include "includes/header.php";
include "includes/navbar.php";

/* -----------------------------
   INITIALIZE CART
------------------------------*/
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* -----------------------------
   ADD PRODUCT
------------------------------*/
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {

    $id = (int)$_GET['id'];
    $qty = isset($_GET['qty']) ? max(1, (int)$_GET['qty']) : 1;

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }

    header("Location: cart.php");
    exit;
}

/* -----------------------------
   REMOVE PRODUCT
------------------------------*/
if (isset($_GET['remove'])) {

    $id = (int)$_GET['remove'];

    unset($_SESSION['cart'][$id]);

    header("Location: cart.php");
    exit;
}

/* -----------------------------
   UPDATE QUANTITIES
------------------------------*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    foreach ($_POST['qty'] as $id => $qty) {

        $qty = (int)$qty;

        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }

    header("Location: cart.php");
    exit;
}
?>

<div class="container py-5">

<h1 class="mb-5 text-center">

Shopping Cart

</h1>

<?php

if (empty($_SESSION['cart'])) {

?>

<div class="alert alert-info text-center">

<h4>Your cart is empty.</h4>

<a href="products.php" class="btn btn-primary mt-3">

Continue Shopping

</a>

</div>

<?php

} else {

$total = 0;

?>

<form method="POST">

<table class="table table-bordered align-middle">

<thead class="table-dark">

<tr>

<th>Image</th>

<th>Product</th>

<th>Price</th>

<th width="120">Quantity</th>

<th>Total</th>

<th>Remove</th>

</tr>

</thead>

<tbody>

<?php

foreach ($_SESSION['cart'] as $id => $qty) {

$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);

$product = $stmt->fetch();

if (!$product) continue;

$subtotal = $product['price'] * $qty;
$total += $subtotal;

?>

<tr>

<td width="120">

<img
src="assets/images/<?= htmlspecialchars($product['image']) ?>"
class="img-fluid rounded">

</td>

<td>

<?= htmlspecialchars($product['name']) ?>

</td>

<td>

$<?= number_format($product['price'],2) ?>

</td>

<td>

<input
type="number"
name="qty[<?= $id ?>]"
value="<?= $qty ?>"
min="1"
class="form-control">

</td>

<td>

$<?= number_format($subtotal,2) ?>

</td>

<td>

<a
href="cart.php?remove=<?= $id ?>"
class="btn btn-danger">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<div class="row mt-4">

<div class="col-md-6">

<a
href="products.php"
class="btn btn-outline-primary">

Continue Shopping

</a>

<button
class="btn btn-secondary">

Update Cart

</button>

</div>

<div class="col-md-6 text-end">

<h3>

Grand Total

<span class="text-primary">

$<?= number_format($total,2) ?>

</span>

</h3>

<a
href="checkout.php"
class="btn btn-success btn-lg mt-3">

Proceed to Checkout

</a>

</div>

</div>

</form>

<?php } ?>

</div>

<?php include "includes/footer.php"; ?>