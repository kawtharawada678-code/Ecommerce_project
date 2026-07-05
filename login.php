<?php
require "config/database.php";

if(isset($_SESSION['user'])){
    header("Location: index.php");
    exit;
}

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$email=trim($_POST['email']);
$password=$_POST['password'];

$stmt=$pdo->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);

$user=$stmt->fetch();

if($user && password_verify($password,$user['password'])){

$_SESSION['user']=$user;

header("Location:index.php");
exit;

}else{

$message="Invalid email or password.";

}

}

include "includes/header.php";
include "includes/navbar.php";
?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card shadow">

<div class="card-body">

<h2 class="text-center mb-4">

Login

</h2>

<?php if($message!=""){ ?>

<div class="alert alert-danger">

<?= $message ?>

</div>

<?php } ?>

<form method="POST">

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

<button class="btn btn-primary w-100">

Login

</button>

</form>

<div class="text-center mt-4">

Don't have an account?

<a href="register.php">

Register

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<?php include "includes/footer.php"; ?>