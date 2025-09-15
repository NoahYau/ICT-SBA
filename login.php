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
    <title>DogeBee - Login</title>
</head>
<body>
<?php include 'header.php'?>

    <!-- Login Content -->
    <div class="login-container">
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Sign in to your DogeBee Store account</p>
        </div>

        <form class="login-form" method="POST" action="login_submit.php">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
            </div>

            <div class="login-options">
                <div style="display: flex; width: auto;">
                    <input type="checkbox" style="transform: scale(1); margin-right: 5px;"> <div style="min-width: max-content;">Remember me</div>
                </div>
                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit" class="login-btn" style="width: 100%; padding: 14px">
                Sign In
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
                Don't have an account? <a href="./register.php" style="color: #0066cc">Create Account</a>
            </p>
        </form>
    </div>
</body>
</html>