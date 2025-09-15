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
<?php
    include 'header.php';
?>
<body>
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

<?php
    include 'footer.html';
?>