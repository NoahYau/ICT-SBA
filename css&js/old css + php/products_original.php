<!DOCTYPE html>

<?php
    session_start();
    $connectdb = mysqli_connect("localhost","root","","dogebee");
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    if (isset($_GET["remarks"])) {
        $remarks=$_GET["remarks"];
    } else {
        $remarks= "";
    }

    if (isset($_GET["brand"])) {
        $brand=$_GET["brand"];
    } else {
        $brand= "";
    }
    mysqli_set_charset($connectdb,"utf8");
?>

<html>
<head>
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="./css&js/product.css">
    <link rel="stylesheet" href="./css&js/product_page_ai_v2.css">
    <link rel="icon" href="photos/doge_logo_v2.png" type="image/png" />
    <title>DogeBee Earphone Store</title>
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
                <a href="products.php?brand=Sennheiser">Sennheiser</a>
                <a href="products.php?brand=Audio-Technica">Audio Technica</a>
                <a href="products.php?brand=Shure">Shure</a>
            </div>
        </div>
		<a href="products.php?remarks=headphones">Headphones</a>
		<a href="about.html">About</a>
        <div class="nav-utilities">
            <!-- Search Bar -->
            <div class="search-container">
              <input type="text" placeholder="Search..." class="search-input">
              <button class="search-btn"><i class="fa fa-search"></i></button>
            </div>
            
            <!-- Icons -->
            <div class="nav-icons">
              	<a href="#cart" class="cart-icon" aria-label="Shopping Cart">
                	<i class="fa-solid fa-cart-shopping"></i>
                	<!-- <span class="cart-count">0</span> -->
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
    <div class="container_product">
        <!-- Filter Sidebar -->
        <div class="filter-sidebar">
            <div class="filter-header">
                <h2>Filter</h2>
                <button class="reset-btn">Reset</button>
            </div>
            <div class="product-count">3 Products</div>
        <form action="/filter_submit.php" method="GET">
            <div class="filter-group">
                <h3>Catagories</h3>
                <ul class="filter-options">
            <?php
                $sql_over =  "SELECT count(*) FROM items WHERE remarks = 'over'";
                $sql_in =  "SELECT count(*) FROM items WHERE remarks = 'in' OR remarks = 'half-in'";
                if ($result = mysqli_query($connectdb, $sql_over)) {
                while ($row = mysqli_fetch_row($result)) {
            ?>
                    <li>
                        <input type="checkbox" id="category1" checked>
                        <label for="category1">Headphones (<?php echo $row[0]?>)</label>
                    </li>
            <?php
                }
            }
            ?>
            <?php
                if ($result = mysqli_query($connectdb, $sql_in)) {
                while ($row = mysqli_fetch_row($result)) {
            ?>
                    <li>
                        <input type="checkbox" id="category2">
                        <label for="category2">Earphones (<?php echo $row[0]?>)</label>
                    </li>
            <?php
                }
            }
            ?>
                </ul>
            </div>
            
            <div class="filter-group">
                <h3>Brand</h3>
                <ul class="filter-options">
                    <li>
                        <input type="checkbox" id="brand1" checked>
                        <label for="brand1">Sennhiser</label>
                    </li>
                    <li>
                     <input type="checkbox" id="brand2">
                        <label for="brand2">Beyerdynamic</label>
                    </li>
                    <li>
                     <input type="checkbox" id="brand3">
                        <label for="brand3">Audio Technica</label>
                    </li>
                    <li>
                     <input type="checkbox" id="brand4">
                        <label for="brand4">Moondrop</label>
                    </li>
                    <li>
                     <input type="checkbox" id="brand5">
                        <label for="brand5">Shure</label>
                    </li>
                    <li>
                     <input type="checkbox" id="brand6">
                        <label for="brand6">Sony</label>
                    </li>
                    <li>
                     <input type="checkbox" id="brand7">
                        <label for="brand7">Audeze</label>
                    </li>
                    <li>
                     <input type="checkbox" id="brand8">
                        <label for="brand8">AKG</label>
                    </li>
                    <li>
                     <input type="checkbox" id="brand9">
                        <label for="brand9">Philips</label>
                    </li>
                    <li>
                     <input type="checkbox" id="brand10">
                        <label for="brand10">NiceHCK</label>
                    </li>
                    <li>
                     <input type="checkbox" id="brand11">
                        <label for="brand11">Final</label>
                    </li>
                </ul>
            </div>
            <button type="submit">Confirm</button>
        </form>
        </div>
        
        <!-- Products Section -->
        <div class="products-section">
            <!-- Sort Options -->
            <div class="sort-options">
                <a href="#" class="active">Popular</a>
                <a href="#">Latest</a>
                <a href="#">Price ↑↓</a>
                <a href="#">Highest Rating</a>
            </div>
            
            <!-- Product Grid -->
        <div class="row">
        <?php
            if ($remarks == "") {
                $sql = "SELECT * from items"; # before was added to finish the task requirment (WHERE price > 1500)
            } else if ($remarks == "in") {
                $sql = "SELECT * from items WHERE remarks = 'in'";
            } else if ($remarks == "over") {
                $sql = "SELECT * from items WHERE remarks = 'over'";
            } else if ($remarks == "half-in") {
                $sql = "SELECT * from items WHERE remarks = 'half-in'";
            }
            if ($result = mysqli_query($connectdb, $sql)) { #Step 2, Query
                while ($row = mysqli_fetch_row($result)) { #Step 3, Fetch 1 row
        ?>
                <a href="<?php echo $row[7] ?>">
                    <div class="card">
                        <img src="<?php echo $row[6] ?>" alt="<?php echo $row[2] ?>" style="width:100%">
                        <h1><?php echo $row[1]." ".$row[2] ?></h1>
                        <p class="price"><?php echo $row[3] ?> HKD</p>
                        <p>A Lovely Product</p>
                        <p><button>Add to Cart</button></p>
                    </div>
                </a>
        <?php
                }
            }
        ?>
</body>
</html>