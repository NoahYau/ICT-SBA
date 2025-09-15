<body>
    <div class="topnav">
        <nav class="navbar bg-body-tertiary">
            <div class="container">
                <a class="navbar-brand" href="index.php">
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
        <a href="index.php">Home</a>
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
        <a href="products.php?remarks=over">Headphones</a>
        <a href="about.php">About</a>
        <a href="sound_preview.php">Find your earphone</a>
        <div class="nav-utilities">
			<div class="search-container">
				<form method="GET" action="search.php">
				<input type="text" name="q" placeholder="Search..." class="search-input"><button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
				</form>
			</div>
            <div class="nav-icons">
                <?php if (isset($_SESSION["email"])){  ?>
					<?php if ($_SESSION["admin"] == 1) { ?>
						<a href="admin.php"><i class="fa-solid fa-toolbox"></i> Admin</a>
                        <a href="admin_orders.php"><i class="fa-solid fa-list"></i> Orders</a>
					<?php } ?>
                <a href="cart.php">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <span class="cart-count"><?= count($_SESSION['cart']) ?></span>
                    <?php endif; ?>
                </a>
                <a href="account.php"><i class="fa-solid fa-user"></i> Account</a>
                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                <?php }
                    else {
                ?>
                <a href="register.php"><i class="fa-solid fa-user-plus"></i> Sign Up</a>
                <a href="login.php"><i class="fa-solid fa-user"></i> Login</a>
                <?php } ?>
            </div>
        </div>
    </div>