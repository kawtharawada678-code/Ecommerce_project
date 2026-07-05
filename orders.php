<?php
require "config/database.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['user']['id'];

$stmt = $pdo->prepare("
SELECT *
FROM orders
WHERE user_id=?
ORDER BY created_at DESC
");

$stmt->execute([$userID]);

$orders = $stmt->fetchAll();

include "includes/header.php";
include "includes/navbar.php";
?>

<div class="container py-5">

<h2 class="mb-4">

My Orders

</h2>

<?php if(count($orders)==0){ ?>

<div class="alert alert-info">

You haven't placed any orders yet.

<br><br>

<a href="products.php" class="btn btn-primary">

Start Shopping

</a>

</div>

<?php } else { ?>

<div class="table-responsive">

<table class="table table-hover align-middle shadow">

<thead class="table-dark">

<tr>

<th>Order #</th>

<th>Date</th>

<th>Total</th>

<th>Status</th>

<th>Address</th>

<th></th>

</tr>

</thead>

<tbody>

<?php foreach($orders as $order){ ?>

<tr>

<td>

<strong>

#<?= $order['id'] ?>

</strong>

</td>

<td>

<?= date("d M Y", strtotime($order['created_at'])) ?>

</td>

<td>

$<?= number_format($order['total_amount'],2) ?>

</td>

<td>

<?php

$status = strtolower($order['status']);

$badge = "secondary";

if($status=="pending") $badge="warning";
if($status=="processing") $badge="info";
if($status=="shipped") $badge="primary";
if($status=="delivered") $badge="success";
if($status=="cancelled") $badge="danger";

?>

<span class="badge bg-<?= $badge ?>">

<?= ucfirst($status) ?>

</span>

</td>

<td>

<?= htmlspecialchars($order['shipping_address']) ?>

</td>

<td>

<a
href="order_details.php?id=<?= $order['id'] ?>"
class="btn btn-outline-primary btn-sm">

View

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<?php } ?>

</div>

<?php include "includes/footer.php"; ?>