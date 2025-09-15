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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $brand = mysqli_real_escape_string($connectdb, $_POST["brand"]);
    $name = mysqli_real_escape_string($connectdb, $_POST["name"]);
    $price = (float)$_POST["price"];
    $style = mysqli_real_escape_string($connectdb, $_POST["style"]);
    $remarks = mysqli_real_escape_string($connectdb, $_POST["remarks"]);
    $product_link = mysqli_real_escape_string($connectdb, $_POST["product_link"] ?? '');
    
    // Handle file upload
    $image_path = '';
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "photos/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Generate unique filename
            $new_filename = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            }
        }
    }
    
    // Insert into database - corrected to match the form fields and database columns
    $insertQuery = "INSERT INTO items (brand, products, price, style, remarks, pictures, link) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connectdb, $insertQuery);
    mysqli_stmt_bind_param($stmt, "ssdssss", $brand, $name, $price, $style, $remarks, $image_path, $product_link);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("Location: admin.php");
    exit();
}

mysqli_close($connectdb);
?>