<?php
require "config/database.php";

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: orders.php");
    exit;
}

$orderID = (int)$_GET['id'];

$stmt = $pdo->prepare("
SELECT *
FROM orders
WHERE id=?
AND user_id=?
");

$stmt->execute([
    $orderID,
    $_SESSION['user']['id']
]);

$order = $stmt->fetch();

if(!$order){

die("Order not found.");

}

$stmt = $pdo->prepare("
SELECT
order_items.*,
products.name,
products.image
FROM order_items
JOIN products
ON products.id=order_items.product_id
WHERE order_id=?
");

$stmt->execute([$orderID]);

include "includes/header.php";
include "includes/navbar.php";
?>

<div class="container py-5">

<h2>

Order #<?= $orderID ?>

</h2>

<p>

Status:

<strong>

<?= ucfirst($order['status']) ?>

</strong>

</p>

<table class="table">

<thead>

<tr>

<th>Image</th>

<th>Product</th>

<th>Price</th>

<th>Qty</th>

<th>Total</th>

</tr>

</thead>

<tbody>

<?php while($item=$stmt->fetch()){ ?>

<tr>

<td>

<img
src="assets/images/<?= htmlspecialchars($item['image']) ?>"
width="80">

</td>

<td>

<?= htmlspecialchars($item['name']) ?>

</td>

<td>

$<?= number_format($item['price_at_purchase'],2) ?>

</td>

<td>

<?= $item['quantity'] ?>

</td>

<td>

$<?= number_format(
$item['quantity']*$item['price_at_purchase'],
2
) ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<a href="orders.php" class="btn btn-secondary">

Back

</a>

</div>

<?php include "includes/footer.php"; ?>