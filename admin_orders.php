<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION["email"]) || $_SESSION["admin"] != 1) {
    header("Location: login.html");
    exit();
}

// Database connection
$connectdb = mysqli_connect("localhost", "root", "", "dogebee");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($connectdb, "utf8");

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    $update_stmt = mysqli_prepare($connectdb, "UPDATE orders SET status = ? WHERE order_id = ?");
    mysqli_stmt_bind_param($update_stmt, "si", $new_status, $order_id);
    mysqli_stmt_execute($update_stmt);
    mysqli_stmt_close($update_stmt);
    
    $update_message = "<div class='success-message'>Order status updated successfully!</div>";
}

// Handle search and filter
$search_term = "";
$status_filter = "";
$where_conditions = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = mysqli_real_escape_string($connectdb, $_GET['search']);
    $where_conditions[] = "(orders.order_id LIKE '%$search_term%' OR orders.email LIKE '%$search_term%' OR orders.item_name LIKE '%$search_term%')";
}

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status_filter = mysqli_real_escape_string($connectdb, $_GET['status']);
    $where_conditions[] = "orders.status = '$status_filter'";
}

// Build the WHERE clause
$where_clause = "";
if (!empty($where_conditions)) {
    $where_clause = "WHERE " . implode(" AND ", $where_conditions);
}

// Fetch all orders
$sql_orders = "
    SELECT 
        orders.order_id,
        orders.email,
        users.name as customer_name,
        orders.order_date,
        orders.amount,
        orders.status,
        GROUP_CONCAT(CONCAT(orders.item_name, ' (Qty: ', orders.quantity, ')') SEPARATOR ', ') as items,
        COUNT(*) as item_count
    FROM orders
    JOIN users ON orders.email = users.email
    $where_clause
    GROUP BY orders.order_id
    ORDER BY orders.order_date DESC, orders.order_id DESC
";

$result_orders = mysqli_query($connectdb, $sql_orders);
$orders = mysqli_fetch_all($result_orders, MYSQLI_ASSOC);

// Get unique status values for filter dropdown
$status_result = mysqli_query($connectdb, "SELECT DISTINCT status FROM orders ORDER BY status");
$status_options = [];
while ($row = mysqli_fetch_assoc($status_result)) {
    $status_options[] = $row['status'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="photos/doge_logo_v2.png" type="image/png" />
    <title>Admin Order Management - DogeBee</title>
    <style>
        /* General page layout styles */
        .admin-container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .admin-header { text-align: center; margin-bottom: 30px; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        .admin-header h1 { color: #3D1101; margin-bottom: 10px; }
        .admin-header p { color: #777; }
        
        /* Filter and search styles */
        .filters-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); margin-bottom: 20px; }
        .filter-form { display: flex; gap: 15px; flex-wrap: wrap; align-items: end; }
        .form-group { flex: 1; min-width: 200px; }
        .form-group label { display: block; margin-bottom: 8px; color: #3D1101; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .filter-btn { padding: 10px 20px; background-color: #3D1101; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .filter-btn:hover { background-color: #5a1a03; }
        
        /* Orders table styles */
        .orders-table-container { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .orders-table { width: 100%; border-collapse: collapse; }
        .orders-table th, .orders-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .orders-table th { background-color: #f8f9fa; font-weight: bold; color: #3D1101; }
        .orders-table tr:hover { background-color: #f8f9fa; }
        
        /* Status badges */
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .status-processing { background-color: #ffc107; color: #333; }
        .status-shipped { background-color: #17a2b8; color: white; }
        .status-delivered { background-color: #28a745; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
        .status-pending { background-color: #6c757d; color: white; }
        
        /* Action buttons */
        .action-btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; margin: 2px; }
        .view-btn { background-color: #17a2b8; color: white; }
        .edit-btn { background-color: #ffc107; color: #333; }
        
        /* Modal styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 700px; border-radius: 8px; position: relative; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; position: absolute; top: 10px; right: 15px; }
        .close:hover { color: #000; }
        
        /* Order details styles */
        .order-details { margin-top: 20px; }
        .order-item { display: flex; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .order-item:last-child { border-bottom: none; }
        .order-item-thumbnail { width: 60px; height: 60px; border-radius: 4px; margin-right: 15px; object-fit: cover; border: 1px solid #ddd; }
        .order-item-details { flex-grow: 1; }
        .order-item-details .item-name { font-weight: bold; color: #3D1101; }
        .order-item-details .item-qty-price { color: #777; font-size: 14px; }
        
        /* Status form */
        .status-form { display: flex; gap: 10px; margin-top: 20px; }
        .status-form select { flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .status-form button { padding: 8px 15px; background-color: #3D1101; color: white; border: none; border-radius: 4px; cursor: pointer; }
        
        /* Feedback message styles */
        .success-message, .error-message { padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: center; }
        .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error-message { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        /* Empty state */
        .no-orders-message { text-align: center; padding: 40px; color: #777; }
        
        /* Loading indicator */
        .loading { display: inline-block; width: 20px; height: 20px; border: 3px solid #f3f3f3; border-top: 3px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite; margin-left: 10px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
<?php
    include 'header.php';
?>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-clipboard-list"></i> Order Management</h1>
            <p>View and manage all customer orders</p>
        </div>
        
        <?php if (isset($update_message)) echo $update_message; ?>
        
        <div class="filters-container">
            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label for="search">Search Orders</label>
                    <input type="text" id="search" name="search" placeholder="Order ID, Email, or Product" value="<?= htmlspecialchars($search_term) ?>">
                </div>
                <div class="form-group">
                    <label for="status">Filter by Status</label>
                    <select id="status" name="status">
                        <option value="">All Statuses</option>
                        <?php foreach ($status_options as $status): ?>
                            <option value="<?= $status ?>" <?= $status_filter == $status ? 'selected' : '' ?>><?= $status ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="filter-btn">Apply Filters</button>
                <?php if (!empty($search_term) || !empty($status_filter)): ?>
                    <a href="admin_orders.php" class="filter-btn" style="background-color: #6c757d;">Clear Filters</a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="orders-table-container">
            <?php if (empty($orders)): ?>
                <div class="no-orders-message">
                    <p>No orders found matching your criteria.</p>
                </div>
            <?php else: ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= $order['order_id'] ?></td>
                                <td>
                                    <div><?= htmlspecialchars($order['customer_name']) ?></div>
                                    <div style="font-size: 12px; color: #777;"><?= htmlspecialchars($order['email']) ?></div>
                                </td>
                                <td><?= date('M j, Y', strtotime($order['order_date'])) ?></td>
                                <td>
                                    <div><?= $order['item_count'] ?> item(s)</div>
                                    <div style="font-size: 12px; color: #777;"><?= htmlspecialchars(substr($order['items'], 0, 50)) ?>...</div>
                                </td>
                                <td>HK$<?= number_format($order['amount'], 2) ?></td>
                                <td>
                                    <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                        <?= $order['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="action-btn view-btn" onclick="viewOrder(<?= $order['order_id'] ?>)">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="action-btn edit-btn" onclick="editOrder(<?= $order['order_id'] ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Order Details Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Order Details</h2>
            <div id="orderDetails">
                <div style="text-align: center; padding: 20px;">
                    <div class="loading"></div>
                    <p>Loading order details...</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Status Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Update Order Status</h2>
            <form method="POST" id="statusForm">
                <input type="hidden" name="order_id" id="editOrderId">
                <div class="form-group">
                    <label for="statusSelect">Status</label>
                    <select name="status" id="statusSelect" class="form-control">
                        <?php foreach ($status_options as $status): ?>
                            <option value="<?= $status ?>"><?= $status ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="update_status" class="filter-btn">Update Status</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
// Close database connection
mysqli_close($connectdb);
?>