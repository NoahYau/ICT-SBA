<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="./css&js/product.css">
    <link rel="stylesheet" href="./css&js/login.css">
    <link rel="icon" href="photos/doge_logo_v2.png" type="image/png" />
    <title>Register - DogeBee</title>
</head>
<body>
<?php include 'header.php'; ?>

    <!-- Login Content -->
    <div class="login-container">
        <div class="login-header">
            <h2>Welcome</h2>
            <p>Register your DogeBee Store account</p>
        </div>
        <form class="login-form" method="POST" action="register_submit.php" onsubmit="return validateForm()">
            <div class="form-group">
                <input type="text" name="name" id="name" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            </div> 
            <div class="form-group">
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group"> 
                <input type="tel"m name="contact" id="contact" placeholder="Contact" required>
            </div>
            <div class="form-group">
                <input type="text" name="city" id="city" placeholder="City" required>
            </div>
            <div class="form-group">
                <input type="text" name="address" id="address" placeholder="Address" required>
            </div>
            <div class="form-group">
                <input type="number" name="admin" id="admin" placeholder="admin" step="1" onchange="this.value = Math.max(0, Math.min(1, parseInt(this.value)));">
            </div>

            <button type="submit" class="login-btn">
                Register
            </button>

            <div class="social-login">
                <p>Or continue with</p>
                <div class="social-buttons">
                    <button type="button" class="social-btn facebook-btn">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </button>
                    <button type="button" class="social-btn google-btn">
                        <i class="fab fa-google"></i>
                        Google
                    </button>
                </div>
            </div>

            <p style="text-align: center; margin-top: 25px; color: #666">
                Have an account? <a href="./login.php" style="color: #0066cc">Login</a>
            </p>
        </form>
    </div>
</body>
</html>