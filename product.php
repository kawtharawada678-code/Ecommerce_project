<?php
require "config/database.php";
include "includes/header.php";
include "includes/navbar.php";

/* -----------------------------
   VALIDATE PRODUCT ID
------------------------------*/
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = (int)$_GET['id'];

/* -----------------------------
   GET PRODUCT
------------------------------*/
$stmt = $pdo->prepare("
SELECT products.*, categories.name AS category_name
FROM products
LEFT JOIN categories
ON products.category_id = categories.id
WHERE products.id = ?
");

$stmt->execute([$id]);

$product = $stmt->fetch();

if (!$product) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Product not found.</div></div>";
    include "includes/footer.php";
    exit;
}

$image = !empty($product['image']) ? $product['image'] : "default.jpg";
?>

<div class="container py-5">

<div class="row g-5">

<!-- PRODUCT IMAGE -->

<div class="col-lg-6">

<img
src="assets/images/<?= htmlspecialchars($image) ?>"
class="img-fluid rounded shadow product-image"
alt="<?= htmlspecialchars($product['name']) ?>">

</div>

<!-- PRODUCT DETAILS -->

<div class="col-lg-6">

<span class="badge bg-primary mb-3">

<?= htmlspecialchars($product['category_name']) ?>

</span>

<h1 class="mb-3">

<?= htmlspecialchars($product['name']) ?>

</h1>

<div class="mb-3">

<span class="text-warning fs-4">

★★★★★

</span>

<span class="text-muted">

(4.9 Reviews)

</span>

</div>

<h2 class="text-primary mb-4">

$<?= number_format($product['price'],2) ?>

</h2>

<p class="lead">

<?= nl2br(htmlspecialchars($product['description'])) ?>

</p>

<hr>

<?php if($product['stock']>0){ ?>

<p>

<span class="badge bg-success">

In Stock (<?= $product['stock'] ?> Available)

</span>

</p>

<form action="cart.php" method="GET">

<input type="hidden" name="action" value="add">

<input type="hidden" name="id" value="<?= $product['id'] ?>">

<div class="row">

<div class="col-4">

<input
type="number"
name="qty"
value="1"
min="1"
max="<?= $product['stock'] ?>"
class="form-control">

</div>

<div class="col-8">

<button class="btn btn-warning btn-lg w-100">

<i class="bi bi-cart-plus"></i>

Add To Cart

</button>

</div>

</div>

</form>

<?php }else{ ?>

<span class="badge bg-danger">

Out of Stock

</span>

<?php } ?>

<div class="mt-4">

<a
href="products.php"
class="btn btn-outline-secondary">

← Back to Products

</a>

</div>

</div>

</div>

<!-- RELATED PRODUCTS -->

<hr class="my-5">

<h2 class="mb-4">

Related Products

</h2>

<div class="row">

<?php

$related = $pdo->prepare("
SELECT *
FROM products
WHERE category_id=?
AND id!=?
LIMIT 3
");

$related->execute([
$product['category_id'],
$product['id']
]);

while($item=$related->fetch()){

?>

<div class="col-md-4 mb-4">

<div class="product-card">

<img
src="assets/images/<?= htmlspecialchars($item['image']) ?>"
class="card-img-top">

<div class="card-body">

<h5>

<?= htmlspecialchars($item['name']) ?>

</h5>

<p class="price">

$<?= number_format($item['price'],2) ?>

</p>

<a
href="product.php?id=<?= $item['id'] ?>"
class="btn btn-primary w-100">

View Product

</a>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

<?php include "includes/footer.php"; ?>