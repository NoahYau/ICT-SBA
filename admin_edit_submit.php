<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION["email"]) || $_SESSION["admin"] != 1) {
    header("Location: login.html");
    exit();
}

$connectdb = mysqli_connect("localhost","root","","dogebee");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($connectdb, "utf8");

// Get product ID from session
$product_id = $_SESSION["productID-edit"] ?? 0;

// Get form data with proper validation
$brand = $_POST["brand"] ?? '';
$name = $_POST["name"] ?? '';
$price = (float)($_POST["price"] ?? 0);
$remarks = $_POST["remarks"] ?? 'over';
$product_link = $_POST["product_link"] ?? '';
$image_path = '';

// Handle image upload - using column index 6 for image path same as products.php
if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
    $target_dir = "./photos/"; // Match the directory used in products.php
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    
    // Get current image path first
    $sql_current_image = "SELECT * FROM items WHERE id = ?";
    $stmt = mysqli_prepare($connectdb, $sql_current_image);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_row($result);
    mysqli_stmt_close($stmt);
    
    $current_image = $row[6] ?? '';
    
    // Delete old image if it exists
    if (!empty($current_image) && file_exists($current_image)) {
        unlink($current_image);
    }
    
    // Upload new image
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = $target_file;
    }
} else {
    // Keep existing image if no new one uploaded
    $sql_current_image = "SELECT * FROM items WHERE id = ?";
    $stmt = mysqli_prepare($connectdb, $sql_current_image);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_row($result);
    mysqli_stmt_close($stmt);
    
    $image_path = $row[6] ?? '';
}

// Update product using prepared statement
$sql_edit = "UPDATE items SET 
             brand = ?, 
             products = ?, 
             price = ?, 
             remarks = ?, 
             pictures = ?,
             link = ?
             WHERE id = ?";

$stmt = mysqli_prepare($connectdb, $sql_edit);
mysqli_stmt_bind_param($stmt, "ssdsssi", 
    $brand, 
    $name, 
    $price, 
    $remarks, 
    $image_path,
    $product_link, 
    $product_id);

mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['edit_success'] = "Product updated successfully!";
} else {
    $_SESSION['edit_error'] = "Failed to update product or no changes made.";
}

mysqli_stmt_close($stmt);
mysqli_close($connectdb);

header("Location: admin.php");
exit();
?>