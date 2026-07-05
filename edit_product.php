
<?php
require "../config/database.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = (int)$_GET['id'];

/* Load Product */
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

/* Load Categories */
$categories = $pdo->query("SELECT * FROM categories");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category = $_POST["category"];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $stock = $_POST["stock"];

    $image = $product["image"];

    if (!empty($_FILES["image"]["name"])) {

        $image = time() . "_" . basename($_FILES["image"]["name"]);

        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            "../assets/images/" . $image
        );
    }

    $update = $pdo->prepare("
        UPDATE products
        SET category_id=?,
            name=?,
            description=?,
            price=?,
            stock=?,
            image=?
        WHERE id=?
    ");

    $update->execute([
        $category,
        $name,
        $description,
        $price,
        $stock,
        $image,
        $id
    ]);

    header("Location: products.php");
    exit;
}

include "../includes/header.php";
include "../includes/navbar.php";
?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow-lg border-0 rounded-4">

<div class="card-body p-5">

<h2>Edit Product</h2>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label>Category</label>

<select name="category" class="form-select">

<?php while($cat=$categories->fetch(PDO::FETCH_ASSOC)){ ?>

<option
value="<?= $cat['id'] ?>"
<?= $cat['id']==$product['category_id'] ? 'selected' : '' ?>>

<?= htmlspecialchars($cat['name']) ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label>Product Name</label>

<input
type="text"
name="name"
class="form-control"
value="<?= htmlspecialchars($product['name']) ?>"
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
rows="5"
class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>

</div>

<div class="row">

<div class="col-md-6">

<label>Price</label>

<input
type="number"
step="0.01"
name="price"
class="form-control"
value="<?= $product['price'] ?>"
required>

</div>

<div class="col-md-6">

<label>Stock</label>

<input
type="number"
name="stock"
class="form-control"
value="<?= $product['stock'] ?>"
required>

</div>

</div>

<div class="mt-3">

<label>Current Image</label>

<br>

<img
src="../assets/images/<?= htmlspecialchars($product['image']) ?>"
width="150"
class="rounded shadow">

</div>

<div class="mt-3">

<label>Change Image</label>

<input
type="file"
name="image"
class="form-control">

</div>

<button class="btn btn-primary mt-4 rounded-pill">

Save Changes

</button>

<a href="products.php" class="btn btn-secondary mt-4 rounded-pill">

Cancel

</a>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>