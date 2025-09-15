<?php
session_start();

// 1. Check if user is logged in.
if (!isset($_SESSION["email"])) {
    header("Location: login.html");
    exit();
}

// 2. Establish Database Connections
$connectdb = mysqli_connect("localhost", "root", "", "dogebee");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($connectdb, "utf8");


$email = $_SESSION["email"];
$update_message = ""; // Variable for feedback messages.

// 3. Handle Account Update Form Submission (uses 'dogebee' database)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $name = mysqli_real_escape_string($connectdb, $_POST['name']);
    $address = mysqli_real_escape_string($connectdb, $_POST['address']);
    $contact = mysqli_real_escape_string($connectdb, $_POST['contact']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $params = [$name, $address, $contact];
    $types = "sss";
    $updateQuery = "UPDATE users SET name = ?, address = ?, contact = ? WHERE email = ?";

    // Handle password change
    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE users SET name = ?, address = ?, contact = ?, password = ? WHERE email = ?";
            $params[] = $hashed_password;
            $types .= "s";
        } else {
            $update_message = "<div class='error-message'>Error: New passwords do not match.</div>";
        }
    }
    
    $params[] = $email;
    $types .= "s";

    // Proceed with the update if there are no errors
    if (empty($update_message)) {
        $stmt = mysqli_prepare($connectdb, $updateQuery);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        if (mysqli_stmt_execute($stmt)) {
            $update_message = "<div class='success-message'>Account information updated successfully!</div>";
        } else {
            $update_message = "<div class='error-message'>An error occurred. Please try again.</div>";
        }
        mysqli_stmt_close($stmt);
    }
}

// 4. Fetch Current User Data for the form (uses 'dogebee' database)
$sql_fetch_user = "SELECT name, email, address, contact FROM users WHERE email = ?";
$stmt_user = mysqli_prepare($connectdb, $sql_fetch_user);
mysqli_stmt_bind_param($stmt_user, "s", $email);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$user = mysqli_fetch_assoc($result_user);
mysqli_stmt_close($stmt_user);

if (!$user) {
    session_destroy();
    header("Location: login.html");
    exit();
}

// 5. Fetch Order History from the 'orders' database
$orders = [];
$sql_fetch_orders = "
    SELECT
    order_id, order_date, amount, status, quantity, item_name, image_url, price_per_item
    FROM orders
    JOIN users
    ON orders.email = users.email
    WHERE users.email = ?
    ORDER BY order_date DESC, order_id DESC";

$stmt_orders = mysqli_prepare($connectdb, $sql_fetch_orders);
mysqli_stmt_bind_param($stmt_orders, "s", $email);
mysqli_stmt_execute($stmt_orders);
$result_orders = mysqli_stmt_get_result($stmt_orders);

// Process results and group items by order_id
while ($row = mysqli_fetch_assoc($result_orders)) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'details' => [
                'order_date' => $row['order_date'],
                'amount' => $row['amount'],
                'status' => $row['status']
            ],
            'items' => []
        ];
    }
    $orders[$order_id]['items'][] = [
        'name' => $row['item_name'],
        'quantity' => $row['quantity'],
        'price' => $row['price_per_item'],
        'image_url' => $row['image_url']
    ];
}
mysqli_stmt_close($stmt_orders);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="photos/doge_logo_v2.png" type="image/png" />
    <title>My Account - DogeBee</title>
    <style>
        /* General page layout styles */
        .admin-container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        .admin-header { text-align: center; margin-bottom: 30px; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        .admin-header h1 { color: #3D1101; margin-bottom: 10px; }
        .admin-header p { color: #777; }

        /* Account form styles */
        .account-form-container { background: white; padding: 30px 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); margin-bottom: 40px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #3D1101; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 16px; color: #333; }
        .form-group input[readonly] { background-color: #e9ecef; cursor: not-allowed; color: #777; }
        .password-note { font-size: 14px; color: #777; margin-top: 5px; }
        .update-btn { width: 100%; padding: 15px; background-color: #3D1101; color: white; border: none; border-radius: 4px; font-size: 18px; cursor: pointer; transition: background-color 0.3s; font-weight: bold; }
        .update-btn:hover { background-color: #5a1a03; }

        /* Order history styles */
        .order-card { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); margin-bottom: 25px; overflow: hidden; }
        .order-header { background-color: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .order-header-info span { margin-right: 20px; color: #3D1101; font-size: 14px; }
        .order-header-info strong { color: #000; }
        .order-status { font-weight: bold; padding: 5px 12px; border-radius: 20px; font-size: 12px; text-transform: uppercase; }
        .status-processing { background-color: #ffc107; color: #333; }
        .status-shipped { background-color: #17a2b8; color: white; }
        .status-delivered { background-color: #28a745; color: white; }
        .order-body { padding: 20px; }
        .order-item { display: flex; align-items: center; margin-bottom: 15px; }
        .order-item:last-child { margin-bottom: 0; }
        .order-item-thumbnail { width: 60px; height: 60px; border-radius: 4px; margin-right: 15px; object-fit: cover; border: 1px solid #ddd; }
        .order-item-details { flex-grow: 1; }
        .order-item-details .item-name { font-weight: bold; color: #3D1101; }
        .order-item-details .item-qty-price { color: #777; font-size: 14px; }
        .no-orders-message { text-align: center; padding: 40px; background: white; border-radius: 8px; color: #777; }
        
        /* Feedback message styles */
        .success-message, .error-message { padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: center; }
        .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error-message { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
<?php
    include 'header.php';
?>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-user-edit"></i> Account Information</h1>
            <p>View and update your personal details below.</p>
        </div>
        <div class="account-form-container">
            <?php if (!empty($update_message)) echo $update_message; ?>
            <form action="account.php" method="POST">
                <div class="form-group"><label for="email">Email Address</label><input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly></div>
                <div class="form-group"><label for="name">Full Name</label><input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required></div>
                <div class="form-group"><label for="address">Shipping Address</label><textarea id="address" name="address" rows="3"><?= htmlspecialchars($user['address']) ?></textarea></div>
                <div class="form-group"><label for="contact">Contact Number</label><input type="text" id="contact" name="contact" value="<?= htmlspecialchars($user['contact']) ?>"></div>
                <hr style="margin: 30px 0; border: 1px solid #eee;">
                <div class="form-group"><label for="new_password">New Password</label><input type="password" id="new_password" name="new_password"><p class="password-note">Leave blank if you don't want to change your password.</p></div>
                <div class="form-group"><label for="confirm_password">Confirm New Password</label><input type="password" id="confirm_password" name="confirm_password"></div>
                <button type="submit" class="update-btn"><i class="fas fa-save"></i> Save Changes</button>
            </form>
        </div>

        <div class="admin-header">
            <h1><i class="fas fa-history"></i> Order History</h1>
            <p>Review your past purchases.</p>
        </div>
        <div class="order-history-container">
            <?php if (empty($orders)): ?>
                <div class="no-orders-message"><p>You have not placed any orders yet.</p></div>
            <?php else: ?>
                <?php foreach ($orders as $order_id => $order_data): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-header-info">
                                <span>Order ID: <strong>#<?= htmlspecialchars($order_id) ?></strong></span>
                                <span>Date: <strong><?= date('F j, Y', strtotime($order_data['details']['order_date'])) ?></strong></span>
                            </div>
                            <span class="order-status status-<?= strtolower(htmlspecialchars($order_data['details']['status'])) ?>">
                                <?= htmlspecialchars($order_data['details']['status']) ?>
                            </span>
                        </div>
                        <div class="order-body">
                            <?php foreach ($order_data['items'] as $item): ?>
                                <div class="order-item">
                                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="order-item-thumbnail">
                                    <div class="order-item-details">
                                        <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                                        <div class="item-qty-price">Quantity: <?= htmlspecialchars($item['quantity']) ?> &nbsp;&bull;&nbsp; Price: HK$<?= number_format($item['price'], 2) ?> each</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div style="text-align: right; margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                                <strong>Total: HK$<?= number_format($order_data['details']['amount'], 2) ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
// Close both database connections
mysqli_close($connectdb);
?>
```