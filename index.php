<?php
session_start();

// Initialize all variables
$pageTitle = 'Wovie';
$backgroundImage = 'photos/starwarsbackG.jpg';
$errors = [];
$username = '';
$email = '';
$password = '';
$confirm_password = '';
$login_email = '';
$login_password = '';
$login_error = '';

// Include required files
include 'temples/header.php';
require 'data/db_connection.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Patua+One&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="preloader-content">
            <h1 class="logo-animation">MIW</h1>
            <div class="tagline">
                <span class="word">Inspire.</span>
                <span class="word">Imagine.</span>
                <span class="word">Innovate.</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="wrapper">
        <span class="icon-close">
            <ion-icon name="close"></ion-icon>
        </span>

        <!-- Login Form -->
        <div class="form-box login">
            <h2>Login</h2>
            <?php if (!empty($login_error)): ?>
                <div class="error-list">
                    <p><?php echo htmlspecialchars($login_error); ?></p>
                </div>
            <?php endif; ?>
            <form id="loginForm" action="index.php" method="POST">
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input 
                        type="email" 
                        name="login_email" 
                        value="<?php echo htmlspecialchars($login_email); ?>" 
                        required
                    >
                    <label>Email</label>
                </div>
                <div class="error-message"></div>

                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input 
                        type="password" 
                        name="login_password" 
                        required
                    >
                    <label>Password</label>
                </div>
                <div class="error-message"></div>

                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="remember_me"> Remember me
                    </label>
                    <a href="forgot-password.php">Forgot Password?</a>
                </div>

                <button type="submit" name="login" class="loginbutton">
                    <span class="button-text">Login</span>
                    <span class="button-loader"></span>
                </button>

                <div class="login-register">
                    <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
                </div>
            </form>
        </div>

        <!-- Registration Form -->
        <div class="form-box register">
            <h2>Registration</h2>
            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form id="registerForm" action="index.php" method="POST">
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <input 
                        type="text" 
                        name="username" 
                        value="<?php echo htmlspecialchars($username); ?>" 
                        required
                        minlength="3"
                    >
                    <label>Username</label>
                </div>
                <div class="error-message"></div>

                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input 
                        type="email" 
                        name="email" 
                        value="<?php echo htmlspecialchars($email); ?>" 
                        required
                    >
                    <label>Email</label>
                </div>
                <div class="error-message"></div>

                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        minlength="8"
                    >
                    <label>Password</label>
                </div>
                <div class="error-message"></div>

                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input 
                        type="password" 
                        name="confirm_password" 
                        required
                    >
                    <label>Confirm Password</label>
                </div>
                <div class="error-message"></div>

                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="terms_agree" required> 
                        I agree to the <a href="terms.php">terms & conditions</a>
                    </label>
                </div>

                <button type="submit" name="register" class="loginbutton">
                    <span class="button-text">Register</span>
                    <span class="button-loader"></span>
                </button>

                <div class="login-register">
                    <p>Already have an account? <a href="#" class="login-link">Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/scripts.js"></script>
    <script src="js/auth.js"></script>
    
    <?php include 'temples/footer.php'; ?>
</body>
</html>