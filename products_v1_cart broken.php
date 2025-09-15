<?php
session_start();
$connectdb = mysqli_connect("localhost","root","","dogebee");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Initialize filter parameters
$remarks = $_GET["remarks"] ?? "";
$brand = $_GET["brand"] ?? "";
$brand_filter = $_GET["brand_filter"] ?? [];

// Convert single brand to array for consistent handling
if ($brand && empty($brand_filter)) {
    $brand_filter = [$brand];
}

mysqli_set_charset($connectdb,"utf8");

// Get distinct brands from database
$brands = [];
$brandQuery = "SELECT DISTINCT brand FROM items";
$brandResult = mysqli_query($connectdb, $brandQuery);
while ($row = mysqli_fetch_assoc($brandResult)) {
    $brands[] = $row['brand'];
}

// Get category counts
$over_count = 0;
$in_count = 0;
$sql_over = "SELECT count(*) FROM items WHERE remarks = 'over'";
$result_over = mysqli_query($connectdb, $sql_over);
if ($result_over) {
    $row = mysqli_fetch_row($result_over);
    $over_count = $row[0];
}

$sql_in = "SELECT count(*) FROM items WHERE remarks = 'in' OR remarks = 'half-in'";
$result_in = mysqli_query($connectdb, $sql_in);
if ($result_in) {
    $row = mysqli_fetch_row($result_in);
    $in_count = $row[0];
}

// Build the SQL query based on filters
$sql = "SELECT * FROM items";
$conditions = [];

// Remarks conditions
if ($remarks == "in") {
    $conditions[] = "(remarks = 'in' OR remarks = 'half-in')";
} else if ($remarks == "over") {
    $conditions[] = "remarks = 'over'";
} else if ($remarks == "half-in") {
    $conditions[] = "remarks = 'half-in'";
}

// Brand conditions
if (!empty($brand_filter)) {
    $escaped_brands = array_map(function($b) use ($connectdb) {
        return "'" . mysqli_real_escape_string($connectdb, $b) . "'";
    }, $brand_filter);
    $brand_list = implode(",", $escaped_brands);
    $conditions[] = "brand IN ($brand_list)";
}

// Combine conditions
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Execute query
$productCount = 0;
$result = mysqli_query($connectdb, $sql);
if ($result) {
    $productCount = mysqli_num_rows($result);
}

// Add to cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    $image = $_POST['product_image'];
    $link = $_POST['product_link'];
    $brand = $_POST['product_brand'];
    
    // Initialize cart if needed
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if product already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }
    
    // If not found, add new item
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $productId,
            'name' => $name,
            'price' => $price,
            'image' => $image,
            'link' => $link,
            'brand' => $brand,
            'quantity' => 1
        ];
    }
    
    // If this is an AJAX request, return a JSON response
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="./css&js/product.css">
    <link rel="stylesheet" href="./css&js/product_page_ai_v2.css">
    <link rel="icon" href="photos/doge_logo_v2.png" type="image/png" />
    <link rel="stylesheet" href="./liquidglass.css">
    <link rel="stylesheet" href="./css&js/footer.css">
    <title>DogeBee Earphone Store</title>
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3D1101;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .cart-notification {
            position: fixed;
            top: 100px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.5s, fadeOut 0.5s 2.5s forwards;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    </style>
</head>
<?php
    include 'header.php';
?>
<body>
    <div class="loading-overlay">
        <div class="spinner"></div>
    </div>
    
    <video width="1080" height="720" autoplay muted class="promote_video">
        <source src="New_arrivals.mp4" type="video/mp4">
    </video>

    <div class="container_product">
        <!-- Filter Sidebar -->
        <div class="filter-sidebar">
            <div class="filter-header">
                <h2>Filter</h2>
                <a href="products.php"><button class="reset-btn">Reset</button></a>
            </div>
            <div class="product-count" id="product-count"><?= $productCount ?> Products Found</div>
            <form id="filter-form" action="products.php" method="GET">
                <div class="filter-group">
                    <h3>Categories</h3>
                    <ul class="filter-options">
                        <li>
                            <input type="radio" id="category3" name="remarks" value="" 
                                <?= empty($remarks) ? 'checked' : '' ?>>
                            <label for="category3">All Categories</label>
                        </li>
                        <li>
                            <input type="radio" id="category1" name="remarks" value="over" 
                                <?= ($remarks == 'over') ? 'checked' : '' ?>>
                            <label for="category1">Headphones (<?= $over_count ?>)</label>
                        </li>
                        <li>
                            <input type="radio" id="category2" name="remarks" value="in" 
                                <?= ($remarks == 'in' || $remarks == 'half-in') ? 'checked' : '' ?>>
                            <label for="category2">Earphones (<?= $in_count ?>)</label>
                        </li>
                    </ul>
                </div>
                
                <div class="filter-group">
                    <h3>Brand</h3>
                    <ul class="filter-options">
                        <?php foreach ($brands as $index => $b): ?>
                            <li>
                                <input type="checkbox" id="brand<?= $index ?>" name="brand_filter[]" 
                                    value="<?= htmlspecialchars($b) ?>"
                                    <?= in_array($b, $brand_filter) ? 'checked' : '' ?>>
                                <label for="brand<?= $index ?>"><?= htmlspecialchars($b) ?></label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <button type="submit" class="filter-btn" id="filter-submit">
                    <div class="container_glass">
                    <div class="glass-container glass-container--rounded glass-container--large">
                        <div class="glass-filter"></div>
                        <div class="glass-overlay"></div>
                        <div class="glass-specular"></div>
                        <div class="glass-content">
                            <div class="player">
                                <div class="player__legend">
                                    <p class="player__legend__title">Apply Filter</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </button>
            </form>
        </div>
        
        <!-- Products Section -->
        <div class="products-section">
            <div class="sort-options">
                <a href="#" class="active">Popular</a>
                <a href="#">Latest</a>
                <a href="#">Price ↑↓</a>
                <a href="#">Highest Rating</a>
            </div>
            
            <div class="row" id="products-container">
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_row($result)): ?>
                        <a href="<?= htmlspecialchars($row[7]) ?>">
                            <div class="card">
                                <img src="<?= htmlspecialchars($row[6]) ?>" alt="<?= htmlspecialchars($row[2]) ?>" style="width:100%">
                                <h1><?= htmlspecialchars($row[1]) . " " . htmlspecialchars($row[2]) ?></h1>
                                <p class="price"><?= number_format($row[3], 1) ?> HKD</p>
                                <p>A Lovely Product</p>
                                <form method="post" class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?= $row[0] ?>">
                                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($row[1] . " " . htmlspecialchars($row[2])) ?>">
                                    <input type="hidden" name="product_price" value="<?= $row[3] ?>">
                                    <input type="hidden" name="product_image" value="<?= htmlspecialchars($row[6]) ?>">
                                    <input type="hidden" name="product_link" value="<?= htmlspecialchars($row[7]) ?>">
                                    <input type="hidden" name="product_brand" value="<?= htmlspecialchars($row[4]) ?>">
                                    <p><button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button></p>
                                </form>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-products">
                        <h3>No products found</h3>
                        <p>Try adjusting your filters</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart']) && empty($_SERVER['HTTP_X_REQUESTED_WITH'])): ?>
        <div class="cart-notification">
            <i class="fas fa-check-circle"></i>
            <span>Item added to cart!</span>
        </div>
    <?php endif; ?>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // AJAX form submission for filtering
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                var formData = $(this).serialize();
                
                // Send AJAX request
                $.ajax({
                    url: 'products.php',
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        // Extract the products container from the response
                        var productsHtml = $(response).find('#products-container').html();
                        var productCount = $(response).find('#product-count').text();
                        
                        // Update the products container and product count
                        $('#products-container').html(productsHtml);
                        $('#product-count').text(productCount);
                        
                        // Reattach event handlers to the new add to cart buttons
                        attachAddToCartHandlers();
                        
                        // Hide loading overlay
                        $('.loading-overlay').hide();
                    },
                    error: function() {
                        alert('Error loading products. Please try again.');
                        $('.loading-overlay').hide();
                    }
                });
            });
            
            // Function to attach event handlers to add to cart buttons
            function attachAddToCartHandlers() {
                $('.add-to-cart-form').on('submit', function(e) {
                    e.preventDefault();
                    
                    var form = $(this);
                    var button = form.find('.add-to-cart-btn');
                    
                    // Store original button text
                    var originalText = button.html();
                    
                    // Show loading state on button
                    button.html('<i class="fas fa-spinner fa-spin"></i> Adding...');
                    button.prop('disabled', true);
                    
                    // Send AJAX request
                    $.ajax({
                        url: 'products.php',
                        type: 'POST',
                        data: form.serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                // Show success notification
                                $('<div class="cart-notification"><i class="fas fa-check-circle"></i><span>Item added to cart!</span></div>')
                                    .appendTo('body')
                                    .delay(2500)
                                    .fadeOut(500, function() {
                                        $(this).remove();
                                    });
                                
                                // Update cart count in header if it exists
                                var cartCount = $('.cart-count');
                                if (cartCount.length) {
                                    cartCount.text(response.cart_count);
                                }
                                
                                // Show success state on button
                                button.html('<i class="fas fa-check"></i> Added!');
                                setTimeout(function() {
                                    button.html(originalText);
                                    button.prop('disabled', false);
                                }, 2000);
                            }
                        },
                        error: function() {
                            alert('Error adding item to cart. Please try again.');
                            button.html(originalText);
                            button.prop('disabled', false);
                        }
                    });
                });
            }
            
            // Attach event handlers to initial add to cart buttons
            attachAddToCartHandlers();
            
            // Add to cart animation for non-AJAX requests
            $(document).on('click', '.add-to-cart-btn', function() {
                var button = $(this);
                var originalText = button.html();
                
                button.html('<i class="fas fa-check"></i> Added!');
                button.css('background-color', '#4CAF50');
                
                setTimeout(function() {
                    button.html(originalText);
                    button.css('background-color', '');
                }, 2000);
            });
        });
    </script>
</body>
</html>
<?php
    include 'footer.html';
?>