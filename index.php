<?php
require "config/database.php";
include "includes/header.php";
include "includes/navbar.php";

/* Featured Products */
$stmt = $pdo->query("
SELECT *
FROM products
ORDER BY created_at DESC
LIMIT 6
");
?>

<!-- HERO -->

<section class="hero">

<div class="container">

<h1>Welcome to Nova Store</h1>

<p>
Discover amazing electronics, fashion, and home products at unbeatable prices.
</p>

<a href="products.php" class="btn btn-warning btn-lg">

Shop Now

</a>

</div>

</section>

<!-- FEATURES -->

<section class="container py-5">

<div class="row text-center">

<div class="col-md-4 mb-4">

<div class="card h-100 shadow-sm">

<div class="card-body">

<i class="bi bi-truck display-4 text-primary"></i>

<h4 class="mt-3">

Fast Delivery

</h4>

<p>

Quick and reliable shipping to your doorstep.

</p>

</div>

</div>

</div>

<div class="col-md-4 mb-4">

<div class="card h-100 shadow-sm">

<div class="card-body">

<i class="bi bi-shield-check display-4 text-success"></i>

<h4 class="mt-3">

Secure Shopping

</h4>

<p>

Your payments and personal data are always protected.

</p>

</div>

</div>

</div>

<div class="col-md-4 mb-4">

<div class="card h-100 shadow-sm">

<div class="card-body">

<i class="bi bi-star-fill display-4 text-warning"></i>

<h4 class="mt-3">

Premium Quality

</h4>

<p>

We carefully select products to ensure high quality.

</p>

</div>

</div>

</div>

</div>

</section>

<!-- PRODUCTS -->

<section class="container pb-5">

<h2 class="section-title">

Featured Products

</h2>

<div class="row">

<?php while($product = $stmt->fetch()){ ?>

<div class="col-lg-4 col-md-6 mb-4">

<div class="product-card">

<img
src="assets/images/<?=
htmlspecialchars($product['image'])
?>"
alt="<?= htmlspecialchars($product['name']) ?>">

<div class="card-body">

<h4>

<?= htmlspecialchars($product['name']) ?>

</h4>

<p>

<?= htmlspecialchars(substr($product['description'],0,80)) ?>...

</p>

<p class="price">

$<?= number_format($product['price'],2) ?>

</p>

<div class="d-grid gap-2">

<a
href="product.php?id=<?= $product['id'] ?>"
class="btn btn-primary">

View Details

</a>

<a
href="cart.php?action=add&id=<?= $product['id'] ?>"
class="btn btn-warning">

Add to Cart

</a>

</div>

</div>

</div>

</div>

<?php } ?>

</div>

</section>

<!-- NEWSLETTER -->

<section class="bg-dark text-white py-5">

<div class="container text-center">

<h2>

Stay Updated

</h2>

<p>

Subscribe to receive the latest offers and new arrivals.

</p>

<form class="row justify-content-center">

<div class="col-md-6">

<input
type="email"
class="form-control"
placeholder="Enter your email">

</div>

<div class="col-md-2 mt-3 mt-md-0">

<button class="btn btn-warning w-100">

Subscribe

</button>

</div>

</form>

</div>

</section>

<?php include "includes/footer.php"; ?>