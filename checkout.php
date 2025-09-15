<?php
session_start();

// 1. Security Check: User must be logged in to check out.
if (!isset($_SESSION["email"])) {
    header("Location: login.html");
    exit();
}

// 2. Security Check: Cart cannot be empty.
if (empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

// Store cart contents and total before clearing the session cart
$processed_cart = $_SESSION['cart'];
$subtotal = 0;
foreach ($processed_cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total_amount = $subtotal * 1.1; // Assuming 10% tax as in cart.php

// Database Connection
$connectdb = mysqli_connect("localhost", "root", "", "dogebee");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($connectdb, "utf8");

// Prepare data for insertion
$email = $_SESSION["email"];
$order_id = uniqid('ORD-'); // Generate a unique order ID, e.g., "ORD-631a0b3de8e7b"
$order_date = date('Y-m-d H:i:s'); // Current timestamp
$status = 'Processing';

// Insert each item into the 'orders' table
$insertQuery = "INSERT INTO orders 
    (order_id, email, order_date, amount, status, quantity, item_name, image_url, price_per_item) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connectdb, $insertQuery);

foreach ($processed_cart as $item) {
    $itemName = $item['brand'] . ' ' . $item['name'];
    $itemPrice = $item['price'];
    $itemQuantity = $item['quantity'];
    $itemImage = $item['image'];

    mysqli_stmt_bind_param(
        $stmt, 
        "ssssdisss",
        $order_id,
        $email,
        $order_date,
        $total_amount,
        $status,
        $itemQuantity,
        $itemName,
        $itemImage,
        $itemPrice
    );
    mysqli_stmt_execute($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($connectdb);

// Clear the shopping cart after successful checkout
unset($_SESSION['cart']);

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <link rel="stylesheet" href="./css&js/cart.css">
    <link rel="icon" href="photos/doge_logo_v2.png" type="image/png" />
    <title>DogeBee - Order Confirmation</title>
    <style>
        .confirmation-container { text-align: center; max-width: 600px; margin: 40px auto; padding: 40px; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .confirmation-icon { font-size: 50px; color: #28a745; margin-bottom: 20px; }
        .confirmation-container h1 { color: #3D1101; margin-bottom: 15px; }
        .confirmation-container p { color: #555; font-size: 1.1em; line-height: 1.6; }
        .order-id { font-weight: bold; color: #3D1101; background: #efe9e7; padding: 5px 10px; border-radius: 4px; display: inline-block; margin-top: 10px; }
        .actions { margin-top: 30px; }
        .actions a { text-decoration: none; color: white; background: #3D1101; padding: 12px 25px; border-radius: 5px; margin: 30px 10px; transition: background 0.3s; }
        .actions a:hover { background: #5a1a03; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container_cart">
        <div class="confirmation-container">
            <i class="fas fa-check-circle confirmation-icon"></i>
            <h1>Thank You For Your Order!</h1>
            <p>Your order has been placed successfully. A confirmation has been sent to your email.</p>
            <p>Your Order ID is:</p>
            <div class="order-id"><?= htmlspecialchars($order_id) ?></div>
            
            <div class="actions">
                <a href="products.php">Continue Shopping</a>
                <a href="account.php">View Order History</a>
            </div>
        </div>
    </div>
</body>
</html>