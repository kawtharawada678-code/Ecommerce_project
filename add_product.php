
<?php
require "../config/database.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = "";

/* Load categories */
$categories = $pdo->query("SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category = $_POST["category"];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $stock = $_POST["stock"];

    $image = "default.jpg";

    if (!empty($_FILES["image"]["name"])) {

        $image = time() . "_" . basename($_FILES["image"]["name"]);

        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            "../assets/images/" . $image
        );
    }

    $stmt = $pdo->prepare("
        INSERT INTO products
        (category_id,name,description,price,stock,image)
        VALUES (?,?,?,?,?,?)
    ");

    $stmt->execute([
        $category,
        $name,
        $description,
        $price,
        $stock,
        $image
    ]);

    $message = "Product added successfully!";
}

include "../includes/header.php";
include "../includes/navbar.php";
?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow border-0 rounded-4">

<div class="card-body p-5">

<h2 class="mb-4">

Add New Product

</h2>

<?php if($message!=""){ ?>

<div class="alert alert-success">

<?= $message ?>

</div>

<?php } ?>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">

Category

</label>

<select name="category" class="form-select">

<?php while($cat=$categories->fetch(PDO::FETCH_ASSOC)){ ?>

<option value="<?= $cat['id']; ?>">

<?= htmlspecialchars($cat['name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Product Name

</label>

<input
type="text"
name="name"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Description

</label>

<textarea
name="description"
rows="5"
class="form-control"
required></textarea>

</div>

<div class="row">

<div class="col-md-6">

<label class="form-label">

Price

</label>

<input
type="number"
step="0.01"
name="price"
class="form-control"
required>

</div>

<div class="col-md-6">

<label class="form-label">

Stock

</label>

<input
type="number"
name="stock"
class="form-control"
required>

</div>

</div>

<div class="mt-3">

<label class="form-label">

Product Image

</label>

<input
type="file"
name="image"
class="form-control">

</div>

<button
class="btn btn-success btn-lg mt-4 rounded-pill">

Add Product

</button>

<a
href="products.php"
class="btn btn-secondary btn-lg mt-4 rounded-pill">

Back

</a>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>