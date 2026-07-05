<?php
require "config/database.php";

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($name == "" || $email == "" || $password == "" || $confirm == "") {

        $message = "Please fill in all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Invalid email address.";

    } elseif ($password != $confirm) {

        $message = "Passwords do not match.";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";

    } else {

        $check = $pdo->prepare("SELECT id FROM users WHERE email=?");
        $check->execute([$email]);

        if ($check->fetch()) {

            $message = "Email already exists.";

        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users
                (full_name,email,password,role)
                VALUES (?,?,?,'customer')
            ");

            $stmt->execute([
                $name,
                $email,
                $hash
            ]);

            $id = $pdo->lastInsertId();

            $_SESSION['user'] = [
                "id" => $id,
                "full_name" => $name,
                "email" => $email,
                "role" => "customer"
            ];

            header("Location: index.php");
            exit;
        }
    }
}

include "includes/header.php";
include "includes/navbar.php";
?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h2 class="text-center mb-4">

Create Account

</h2>

<?php if($message!=""){ ?>

<div class="alert alert-danger">

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
required>

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Confirm Password</label>

<input
type="password"
name="confirm"
class="form-control"
required>

</div>

<button class="btn btn-success w-100">

Register

</button>

</form>

<div class="text-center mt-4">

Already have an account?

<a href="login.php">

Login

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<?php include "includes/footer.php"; ?>