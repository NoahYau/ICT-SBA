<?php
// search.php
session_start();
require_once 'search_config.php'; // Database connection

// Get search query from URL parameter
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Initialize variables
$products = [];
$message = '';

// If there's a search query, search the database
if (!empty($search_query)) {
    try {
        // Prepare SQL query to search products
        $sql = "SELECT * FROM items 
                WHERE products LIKE :query 
                OR brand LIKE :query 
                OR style LIKE :query 
                ORDER BY products";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':query' => "%$search_query%"]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($products)) {
            $message = "No products found for '$search_query'";
        }
    } catch (PDOException $e) {
        $message = "Error searching products: " . $e->getMessage();
    }
} else {
    $message = "Please enter a search term";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - DogeBee</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <link rel="stylesheet" href="./css&js/footer.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .result-container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .search-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .product-card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            margin: 30px;
            text-align: center;
            font-family: Arial, sans-serif;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
            position: relative;
            background: white;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .product-image {
            width: 100%;
            height: 250px;
            object-fit: contain;
            padding: 15px;
        }
        .product-info {
            padding: 15px;
        }
        .product-name {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 1.1em;
        }
        .product-brand {
            color: #666;
            margin-bottom: 8px;
        }
        .product-price {
            font-weight: bold;
            color: #3d1101;
            margin-bottom: 12px;
        }
        .btn {
            display: inline-block;
            background-color: #3d1101;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #5a1a03;
        }
        .message {
            text-align: center;
            padding: 30px;
            font-size: 1.2em;
            color: #666;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="result-container">
        <div class="search-header">
            <h2>Search Results</h2>
            <p style="margin-top:5px">&nbspShowing results for: <strong>"<?php echo htmlspecialchars($search_query); ?>"</strong></p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message">
                <?php echo $message; ?>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['pictures']); ?>" 
                             alt="<?php echo htmlspecialchars($product['products']); ?>" 
                             class="product-image">
                        <div class="product-info">
                            <div class="product-name"><?php echo htmlspecialchars($product['products']); ?></div>
                            <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                            <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                            <a href="<?php echo $product['link']; ?>" class="btn">View Details</a>
                            <?php if (isset($_SESSION["email"])): ?>
                                <a href="add_to_cart.php?id=<?php echo $product['id']; ?>" class="btn">Add to Cart</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.html'; ?>
</body>
</html>