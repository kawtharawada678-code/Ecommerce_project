<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartCount = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cartCount += $qty;
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-dark custom-navbar shadow">

<div class="container">

<a class="navbar-brand fw-bold" href="index.php">

<i class="bi bi-bag-heart-fill"></i>

Nova Store

</a>

<button
class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarNav">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="navbarNav">

<ul class="navbar-nav me-auto">

<li class="nav-item">

<a class="nav-link" href="index.php">

<i class="bi bi-house-door"></i>

Home

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="products.php">

<i class="bi bi-grid"></i>

Products

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="about.php">

<i class="bi bi-info-circle"></i>

About

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="contact.php">

<i class="bi bi-envelope"></i>

Contact

</a>

</li>

</ul>

<form
class="d-flex me-3"
action="products.php"
method="GET">

<input
class="form-control me-2"
type="search"
name="search"
placeholder="Search products">

<button class="btn btn-warning">

<i class="bi bi-search"></i>

</button>

</form>

<ul class="navbar-nav align-items-center">

<li class="nav-item me-3">

<a class="nav-link position-relative" href="cart.php">

<i class="bi bi-cart3 fs-5"></i>

<?php if($cartCount>0){ ?>

<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

<?= $cartCount ?>

</span>

<?php } ?>

</a>

</li>

<?php if(isset($_SESSION['user'])){ ?>

<li class="nav-item dropdown">

<a
class="nav-link dropdown-toggle"
href="#"
data-bs-toggle="dropdown">

<i class="bi bi-person-circle"></i>

<?= htmlspecialchars($_SESSION['user']['full_name']) ?>

</a>

<ul class="dropdown-menu dropdown-menu-end">

<li>

<a class="dropdown-item" href="profile.php">

My Profile

</a>

</li>

<li>

<a class="dropdown-item" href="orders.php">

My Orders

</a>

</li>

<li><hr class="dropdown-divider"></li>

<li>

<a class="dropdown-item text-danger" href="logout.php">

Logout

</a>

</li>

</ul>

</li>

<?php }else{ ?>

<li class="nav-item">

<a class="nav-link" href="login.php">

Login

</a>

</li>

<li class="nav-item ms-2">

<a class="btn btn-warning px-4 rounded-pill" href="register.php">

Register

</a>

</li>

<?php } ?>

</ul>

</div>

</div>

</nav>