<?php
require "config/database.php";
include "includes/header.php";
include "includes/navbar.php";

/* -----------------------------
   SEARCH & FILTERS
------------------------------*/

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? '';

$sql = "
SELECT products.*, categories.name AS category_name
FROM products
LEFT JOIN categories
ON products.category_id = categories.id
WHERE 1
";

$params = [];

if (!empty($search)) {
    $sql .= " AND products.name LIKE ?";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $sql .= " AND category_id = ?";
    $params[] = $category;
}

switch ($sort) {
    case "low":
        $sql .= " ORDER BY price ASC";
        break;

    case "high":
        $sql .= " ORDER BY price DESC";
        break;

    case "new":
        $sql .= " ORDER BY created_at DESC";
        break;

    default:
        $sql .= " ORDER BY name ASC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$categories = $pdo->query("SELECT * FROM categories");
?>

<div class="container py-5">

<h1 class="text-center mb-5">

Our Products

</h1>

<form method="GET" class="row g-3 mb-5">

<div class="col-md-4">

<input
type="text"
name="search"
class="form-control"
placeholder="Search products..."
value="<?= htmlspecialchars($search) ?>">

</div>

<div class="col-md-3">

<select name="category" class="form-select">

<option value="">All Categories</option>

<?php while($cat = $categories->fetch()){ ?>

<option
value="<?= $cat['id'] ?>"
<?= ($category == $cat['id']) ? 'selected' : '' ?>>

<?= htmlspecialchars($cat['name']) ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-3">

<select name="sort" class="form-select">

<option value="">Sort By</option>

<option value="low" <?= $sort=="low"?"selected":"" ?>>

Price: Low to High

</option>

<option value="high" <?= $sort=="high"?"selected":"" ?>>

Price: High to Low

</option>

<option value="new" <?= $sort=="new"?"selected":"" ?>>

Newest

</option>

</select>

</div>

<div class="col-md-2">

<button class="btn btn-primary w-100">

Filter

</button>

</div>

</form>

<div class="row">

<?php

if($stmt->rowCount() > 0){

while($product = $stmt->fetch()){

$image = !empty($product['image'])
? $product['image']
: "default.jpg";

?>

<div class="col-lg-4 col-md-6 mb-4">

<div class="product-card">

<img
src="assets/images/<?= htmlspecialchars($image) ?>"
alt="<?= htmlspecialchars($product['name']) ?>">

<div class="card-body">

<h4>

<?= htmlspecialchars($product['name']) ?>

</h4>

<p class="text-muted">

<?= htmlspecialchars($product['category_name']) ?>

</p>

<p>

<?= htmlspecialchars(substr($product['description'],0,90)) ?>...

</p>

<p class="price">

$<?= number_format($product['price'],2) ?>

</p>

<div class="d-grid gap-2">

<a
href="product.php?id=<?= $product['id'] ?>"
class="btn btn-primary">

View Details

</a>

<a
href="cart.php?action=add&id=<?= $product['id'] ?>"
class="btn btn-warning">

Add to Cart

</a>

</div>

</div>

</div>

</div>

<?php

}

}else{

?>

<div class="col-12">

<div class="alert alert-warning text-center p-5">

<h4>

No products found.

</h4>

<p>

Try changing your search or filter.

</p>

</div>

</div>

<?php } ?>

</div>

</div>

<?php include "includes/footer.php"; ?>