<?php
require "config/database.php";
include "includes/header.php";
include "includes/navbar.php";

$success = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$success="Your message has been sent successfully!";

}
?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow">

<div class="card-body">

<h2 class="mb-4 text-center">

Contact Us

</h2>

<?php if($success!=""){ ?>

<div class="alert alert-success">

<?= $success ?>

</div>

<?php } ?>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>Full Name</label>

<input
type="text"
name="name"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

</div>

<div class="mb-3">

<label>Subject</label>

<input
type="text"
name="subject"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Message</label>

<textarea
name="message"
rows="6"
class="form-control"
required></textarea>

</div>

<button class="btn btn-primary">

Send Message

</button>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include "includes/footer.php"; ?>