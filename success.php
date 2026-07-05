<?php
require "config/database.php";
include "includes/header.php";
include "includes/navbar.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$orderID = isset($_GET['id']) ? (int)$_GET['id'] : 0;
?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow text-center">

<div class="card-body">

<div class="mb-4">

<i class="bi bi-check-circle-fill text-success display-1"></i>

</div>

<h1 class="text-success">

Order Successful!

</h1>

<p class="lead">

Thank you for shopping with <strong>Nova Store</strong>.

</p>

<?php if($orderID){ ?>

<p>

Your Order ID is

<strong>#<?= $orderID ?></strong>

</p>

<?php } ?>

<hr>

<p>

A confirmation has been saved to your account.

You can view your order history anytime.

</p>

<div class="mt-4">

<a href="orders.php" class="btn btn-primary btn-lg">

My Orders

</a>

<a href="products.php" class="btn btn-outline-secondary btn-lg">

Continue Shopping

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<?php include "includes/footer.php"; ?>