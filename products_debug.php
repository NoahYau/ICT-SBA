<!DOCTYPE html>

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
?>

<html>
<head>
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="./css&js/product.css">
    <link rel="stylesheet" href="./css&js/product_page_ai_v2.css">
    <link rel="icon" href="photos/doge_logo_v2.png" type="image/png" />
    <title>DogeBee Earphone Store</title>
    <style>
        .brand-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px auto;
            max-width: 1200px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .brand-btn {
            padding: 8px 15px;
            background: #e0e0e0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .brand-btn:hover {
            background: #d0d0d0;
        }
        .brand-btn.active {
            background: #4CAF50;
            color: white;
            font-weight: bold;
        }
        .filter-sidebar form {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .filter-btn {
            margin-top: auto;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="topnav">
        <nav class="navbar bg-body-tertiary">
            <div class="container">
                <a class="navbar-brand" href="#">
                <img src="./photos/doge_logo_v2.png" alt="Bootstrap" width="60" height="70">
                </a>
            </div>
            <div class="header" style="color:rgb(255, 255, 255);">
                <h1>DogeBee</h1>
                <p class="tagline">Welcome to DogeBee Earphone Store!</p>
            </div>
        </nav>
    </div>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="products.php">Products</a>
        <div class="dropdown">
            <button class="dropbtn">Browse by Brand
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <a href="products.php?brand=Sennhiser">Sennhiser</a>
                <a href="products.php?brand=Audio-Technica">Audio Technica</a>
                <a href="products.php?brand=Shure">Shure</a>
            </div>
        </div>
		<a href="products.php?remarks=over">Headphones</a>
		<a href="about.html">About</a>
        <div class="nav-utilities">
            <div class="search-container">
              <input type="text" placeholder="Search..." class="search-input">
              <button class="search-btn"><i class="fa fa-search"></i></button>
            </div>
            <div class="nav-icons">
              	<a href="#cart" class="cart-icon" aria-label="Shopping Cart">
                	<i class="fa-solid fa-cart-shopping"></i>
              	</a>
            	<a href="login.html" class="user-icon" aria-label="User Account">
                <i class="fa-solid fa-user"></i>
              	</a>
            </div>
        </div>
    </div>

    <video width="1080" height="720" autoplay muted class="promote_video">
        <source src="New_arrivals.mp4" type="video/mp4">
    </video>
    
    <!-- Brand Filter Buttons -->
    <div class="brand-buttons">
        <a href="products.php">
            <button class="brand-btn <?= empty($brand_filter) ? 'active' : '' ?>">All Brands</button>
        </a>
        <?php foreach ($brands as $b): ?>
            <a href="products.php?brand=<?= urlencode($b) ?>">
                <button class="brand-btn <?= (in_array($b, $brand_filter)) ? 'active' : '' ?>">
                    <?= htmlspecialchars($b) ?>
                </button>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="container_product">
        <!-- Filter Sidebar -->
        <div class="filter-sidebar">
            <div class="filter-header">
                <h2>Filter</h2>
                <a href="products.php"><button class="reset-btn">Reset</button></a>
            </div>
            <div class="product-count"><?= $productCount ?> Products</div>
            <form action="products.php" method="GET">
                <div class="filter-group">
                    <h3>Categories</h3>
                    <ul class="filter-options">
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
                        <li>
                            <input type="radio" id="category3" name="remarks" value="" 
                                <?= empty($remarks) ? 'checked' : '' ?>>
                            <label for="category3">All Categories</label>
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
                <button type="submit" class="filter-btn">Apply Filters</button>
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
            
            <div class="row">
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_row($result)): ?>
                        <a href="<?= htmlspecialchars($row[7]) ?>">
                            <div class="card">
                                <img src="<?= htmlspecialchars($row[6]) ?>" alt="<?= htmlspecialchars($row[2]) ?>" style="width:100%">
                                <h1><?= htmlspecialchars($row[1]) . " " . htmlspecialchars($row[2]) ?></h1>
                                <p class="price"><?= number_format($row[3], 2) ?> HKD</p>
                                <p>A Lovely Product</p>
                                <p><button>Add to Cart</button></p>
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
</body>
</html>