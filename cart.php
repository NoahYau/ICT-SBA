<script>
        // Update quantity with buttons
        function updateQuantity(button, change) {
            const form = button.closest('form');
            const input = form.querySelector('.quantity-input');
            let value = parseInt(input.value) + change;
            
            if (value < 1) value = 1;
            input.value = value;
        }
        
        // Remove item from cart
        function removeItem(productId) {
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                window.location.href = 'cart.php?remove=' + productId;
            }
        }
        
        // Initialize quantity buttons
        document.querySelectorAll('.minus, .plus').forEach(button => {
            button.addEventListener('click', function() {
                const change = this.classList.contains('minus') ? -1 : 1;
                updateQuantity(this, change);
            });
        });
    </script>
<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add item to cart
function addToCart($productId, $name, $price, $image, $link, $brand) {
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
}

// Process add to cart requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    $image = $_POST['product_image'];
    $link = $_POST['product_link'];
    $brand = $_POST['product_brand'];
    
    addToCart($productId, $name, $price, $image, $link, $brand);
}

// Process remove item requests
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $removeId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    // Reindex array after removal
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Process update quantity requests
if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = (int)$_POST['quantity'];
    
    if ($newQuantity > 0) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] = $newQuantity;
                break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <link rel="stylesheet" href="./css&js/cart.css">
    <link rel="icon" href="photos/doge_logo_v2.png" type="image/png" />
    <title>DogeBee - Shopping Cart</title>
</head>
<?php
    include 'header.php';
?>
<body>
    <!-- Cart Page Content -->
    <div class="container_cart">
        <h1 class="page-title"><i class="fas fa-shopping-cart"></i> Your Shopping Cart</h1>
        
        <div class="cart-container">
            <div class="cart-items">
                <?php if (count($_SESSION['cart']) > 0): ?>
                    <div class="cart-header">
                        <div>Product</div>
                        <div>Total</div>
                    </div>
                    
                    <?php 
                    $subtotal = 0;
                    foreach ($_SESSION['cart'] as $item): 
                        $itemTotal = $item['price'] * $item['quantity'];
                        $subtotal += $itemTotal;
                    ?>
                    <div class="cart-item">
                        <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="cart-item-image">
                        <div class="cart-item-details">
                            <div class="cart-item-name"><?= $item['name'] ?></div>
                            <div class="cart-item-brand"><?= $item['brand'] ?></div>
                            <div class="cart-item-price"><?= number_format($item['price'], 1) ?> HKD</div>
                            
                            <form method="post" class="cart-item-quantity">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <button type="button" class="quantity-btn minus" onclick="updateQuantity(this, -1)">-</button>
                                <input type="number" name="quantity" class="quantity-input" value="<?= $item['quantity'] ?>" min="1" readonly>
                                <button type="button" class="quantity-btn plus" onclick="updateQuantity(this, 1)">+</button>
                                <button type="submit" name="update_quantity" class="update-btn">Update</button>
                            </form>
                        </div>
                        <button class="cart-item-remove" onclick="removeItem(<?= $item['id'] ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                        <div class="cart-item-total">
                            <?= number_format($itemTotal, 1) ?> HKD
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <h2>Your cart is empty</h2>
                        <p>Looks like you haven't added anything to your cart yet</p>
                        <a href="products.php" class="shop-btn">Browse Products</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (count($_SESSION['cart']) > 0): ?>
            <form class="cart-summary" action="checkout.php" method="POST">
                <h3 class="summary-title">Order Summary</h3>
                
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value"><?= number_format($subtotal, 1) ?> HKD</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Shipping</span>
                    <span class="summary-value">Free</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Tax</span>
                    <span class="summary-value"><?= number_format($subtotal * 0.1, 1) ?> HKD</span>
                </div>
                
                <div class="summary-row summary-total">
                    <span class="summary-label">Total</span>
                    <span class="summary-value"><?= number_format($subtotal * 1.1, 1) ?> HKD</span>
                </div>
                
                <button type="submit" class="checkout-btn">Proceed to Checkout</button>
                <a href="products.php" class="continue-btn">Continue Shopping</a>
            </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>