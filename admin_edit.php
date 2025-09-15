<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION["email"]) || $_SESSION["admin"] != 1) {
    header("Location: login.html");
    exit();
}

$connectdb = mysqli_connect("localhost","root","","dogebee");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($connectdb,"utf8");

// Get product ID safely
$product_id = isset($_GET["productID"]) ? (int)$_GET["productID"] : 0;
$_SESSION["productID-edit"] = $product_id;

// Get product data using prepared statement
$sql_check = "SELECT * FROM items WHERE id = ?";
$stmt = mysqli_prepare($connectdb, $sql_check);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_row($result);
mysqli_stmt_close($stmt);

// If product not found, redirect
if (!$row) {
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- <link rel="stylesheet" href="./css&js/product.css"> -->
    <link rel="icon" href="photos/doge_logo_v2.png" type="image/png" />
    <link rel="stylesheet" href="./css&js/admin.css">
    <title>DogeBee Admin - Edit Product</title>
</head>
<body>

<?php include "header.php"; ?>

    <!-- Edit Form -->
    <div class="edit-container">
        <div class="edit-header">
            <h1><i class="fas fa-edit"></i> Edit Product</h1>
            <p>Update product information below</p>
        </div>
        
        <form method="POST" action="admin_edit_submit.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" class="form-control" name="brand" placeholder="Enter Brand" 
                       value="<?= htmlspecialchars($row[1] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Product Name" 
                       value="<?= htmlspecialchars($row[2] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="price">Price (HKD)</label>
                <input type="number" step="1" class="form-control" name="price" 
                       placeholder="Enter Price" value="<?= htmlspecialchars($row[3] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="remarks">Category</label>
                <select name="remarks" class="form-control" required>
                    <option value="over" <?= ($row[5] ?? '') == 'over' ? 'selected' : '' ?>>Headphones</option>
                    <option value="in" <?= ($row[5] ?? '') == 'in' ? 'selected' : '' ?>>Earphones (In-ear)</option>
                    <option value="half-in" <?= ($row[5] ?? '') == 'half-in' ? 'selected' : '' ?>>Earphones (Half-in)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="product_link">Product Link</label>
                <input type="text" class="form-control" name="product_link" placeholder="Enter Product URL" 
                       value="<?= htmlspecialchars($row[7] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Current Image</label>
                <?php if (!empty($row[6])): ?>
                    <img src="<?= htmlspecialchars($row[6]) ?>" class="product-image" 
                         alt="<?= htmlspecialchars($row[1] ?? '') ?> <?= htmlspecialchars($row[2] ?? '') ?>">
                <?php else: ?>
                    <p>No image currently set</p>
                <?php endif; ?>
                <label for="image">Change Image</label>
                <input type="file" class="form-control" name="image">
            </div>
            
            <button type="submit" class="btn-primary">Update Product</button>
            <a href="admin.php" class="btn-primary" style="background-color: #777; text-decoration: none; display: inline-block; margin-left: 10px;">Cancel</a>
        </form>
    </div>
    
    <script>
        // form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const price = document.querySelector('input[name="price"]');
            if (price.value <= 0) {
                alert('Price must be greater than 0');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>

<?php
mysqli_close($connectdb);
?>