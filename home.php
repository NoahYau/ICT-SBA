<!DOCTYPE html>

<?php
    session_start();
    $connectdb = mysqli_connect("localhost","root","","dogebee");
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>

<html>
<head>
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="./css&js/product.css">
    <link rel="stylesheet" href="./css&js/footer.css">
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
                <p id="quotes" style="font-family: zh;"></p>
                <script src="./css&js/random_quotes.js"></script>
            </div>
        </nav>
    </div>
    <div class="navbar">
        <a href="#home">Home</a>
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
              	<a href="cart.php" class="cart-icon" aria-label="Shopping Cart">
                	<i class="fa-solid fa-cart-shopping"></i>
                	<!-- <span class="cart-count">0</span> -->
              	</a>
            	<a href="login.html" class="user-icon" aria-label="User Account">
                <i class="fa-solid fa-user"></i>
              	</a>
            </div>
        </div>
    </div>

    <div class="slideshow-container">
        <div class="mySlides fade">
          	<div class="numbertext">1 / 3</div>
          	<img src="./photos/doge_banner.png" style="width:100%">
          	<!-- <div class="text">Caption Text</div> -->
        </div>
      
        <div class="mySlides fade">
          	<div class="numbertext">2 / 3</div>
          	<img src="./photos/earpod_banner.jpg" style="width:100%">
          	<div class="text">Promotion</div>
        </div>
      
        <div class="mySlides fade">
          	<div class="numbertext">3 / 3</div>
          	<img src="./photos/wallpaper_flowergirl.png" style="width:100%">
          	<div class="text">Wallpaper</div>
        </div>
      
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>
    <br>
      
    <div style="text-align:center">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
    </div>

    <script src="./css&js/slideshow.js"></script>
	<h2>
		Latest Products
	</h2>
	<div class="row_home">
        <a href="products.php?remarks=in">
		    <div class="card">
			    <img src="./photos/products/ie800s.png" alt="IE800s" style="width:100%">
			    <h1>In Ear Earphones</h1>
			    <p><button>Explore</button></p>
		    </div>
        </a>
        <a href="products.php?remarks=half-in">
		    <div class="card">
			    <img src="./photos/products/bluecap.png" alt="Bluecap" style="width:100%">
			    <h1>Half-In Earphones</h1>
			    <p><button>Discover</button></p>
		    </div>
        </a>
        <a href="products.php?remarks=over">
		    <div class="card">
			    <img src="./photos/products/k701.png" alt="K701" style="width:100%">
			    <h1>Headphones</h1>
			    <p><button>Experience</button></p>
		    </div>
        </a>
	</div>

        <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About DogeBee</h3>
                <p>Your premier destination for high-quality audio equipment. We offer the best selection of headphones, earphones, and audio accessories.</p>
                <div class="footer-social">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <div class="footer-links">
                    <a href="home.php">Home</a>
                    <a href="products.php">Products</a>
                    <a href="about.html">About Us</a>
                    <a href="#">Contact</a>
                    <a href="#">FAQ</a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Customer Service</h3>
                <div class="footer-links">
                    <a href="#">Shipping Policy</a>
                    <a href="#">Return Policy</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms & Conditions</a>
                    <a href="#">Track Your Order</a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Contact Us</h3>
                <div class="footer-contact">
                    <p><i class="fas fa-map-marker-alt"></i> 123 Audio Street, Hong Kong</p>
                    <p><i class="fas fa-phone"></i> +852 1234 5678</p>
                    <p><i class="fas fa-envelope"></i> info@dogebee.com</p>
                    <p><i class="fas fa-clock"></i> Mon-Fri: 9:00 AM - 6:00 PM</p>
                </div>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; 2025 DogeBee All rights reserved.</p>
        </div>
    </footer>