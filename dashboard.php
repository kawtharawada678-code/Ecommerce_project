<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require "config/database.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

include "includes/header.php";
include "includes/navbar.php";

$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalUsers    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalOrders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalSales    = $pdo->query("SELECT IFNULL(SUM(total_amount),0) FROM orders")->fetchColumn();
?>

<div class="container py-5">

<h1 class="mb-5">Admin Dashboard</h1>

<div class="row">

<div class="col-md-3 mb-4">
<div class="dashboard-card text-center">
<i class="bi bi-box-seam"></i>
<h2><?= $totalProducts ?></h2>
<p>Products</p>
</div>
</div>

<div class="col-md-3 mb-4">
<div class="dashboard-card text-center">
<i class="bi bi-people"></i>
<h2><?= $totalUsers ?></h2>
<p>Users</p>
</div>
</div>

<div class="col-md-3 mb-4">
<div class="dashboard-card text-center">
<i class="bi bi-cart-check"></i>
<h2><?= $totalOrders ?></h2>
<p>Orders</p>
</div>
</div>

<div class="col-md-3 mb-4">
<div class="dashboard-card text-center">
<i class="bi bi-currency-dollar"></i>
<h2>$<?= number_format($totalSales,2) ?></h2>
<p>Revenue</p>
</div>
</div>

</div>

<div class="row mt-4">
<div class="col-md-3">

<a href="admin/products.php" class="btn btn-primary w-100 mb-3">Manage Products</a>
<a href="admin/orders.php" class="btn btn-success w-100 mb-3">Manage Orders</a>
<a href="admin/users.php" class="btn btn-warning w-100">Manage Users</a>

</div>
</div>

</div>

<?php include "includes/footer.php"; ?>