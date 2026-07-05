<?php
require "config/database.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['user']['id'];

$message = "";

/* UPDATE PROFILE */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);

    if ($name != "") {

        $stmt = $pdo->prepare("
            UPDATE users
            SET full_name=?
            WHERE id=?
        ");

        $stmt->execute([$name, $userID]);

        $_SESSION['user']['full_name'] = $name;

        $message = "Profile updated successfully.";

    }

}

/* GET USER */

$stmt = $pdo->prepare("
SELECT *
FROM users
WHERE id=?
");

$stmt->execute([$userID]);

$user = $stmt->fetch();

/* COUNT ORDERS */

$stmt = $pdo->prepare("
SELECT COUNT(*) AS total
FROM orders
WHERE user_id=?
");

$stmt->execute([$userID]);

$orderCount = $stmt->fetch()['total'];

include "includes/header.php";
include "includes/navbar.php";
?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-7">

<div class="card shadow">

<div class="card-body">

<h2 class="mb-4">

My Profile

</h2>

<?php if($message!=""){ ?>

<div class="alert alert-success">

<?= $message ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label>Full Name</label>

<input
type="text"
name="name"
class="form-control"
value="<?= htmlspecialchars($user['full_name']) ?>"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
class="form-control"
value="<?= htmlspecialchars($user['email']) ?>"
readonly>

</div>

<div class="row">

<div class="col-md-6">

<label>Orders</label>

<input
type="text"
class="form-control"
value="<?= $orderCount ?>"
readonly>

</div>

<div class="col-md-6">

<label>Member Since</label>

<input
type="text"
class="form-control"
value="<?= date('d M Y', strtotime($user['created_at'])) ?>"
readonly>

</div>

</div>

<button class="btn btn-primary mt-4">

Save Changes

</button>

<a href="orders.php" class="btn btn-outline-success mt-4">

My Orders

</a>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include "includes/footer.php"; ?>