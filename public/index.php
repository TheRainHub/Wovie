<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIWovie</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>

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

<header>
    <h1 class="logo"><span class="mi">MI</span><span class="main-letter">W</span><span class="second-half">ovie</span></h1>
    <nav class="navigation">
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Service</a>
        <a href="#">Contact</a>
        <button class="loginbutton-popup">Login</button>
    </nav>
</header> 

<div class="wrapper">
    <span class="icon-close">
        <ion-icon name="close"></ion-icon>
    </span>

    <div class="form-box login">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="mail"></ion-icon>
                </span>
                <input type="email" required>
                <label>Email</label>
            </div>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="lock-closed"></ion-icon>
                </span>
                <input type="password" required>
                <label>Password</label>
            </div>            
            <div class="remember-forgot">
                <label><input type="checkbox"> Remember me</label>
                <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" class="loginbutton">Login</button>
            <div class="login-register">
                <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
            </div>
        </form>
    </div> 

    <div class="form-box register">
        <h2>Registration</h2>
        <form action="register.php" method="POST">
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="person"></ion-icon>
                </span>
                <input type="text" name="username" required>
                <label>Username</label>
            </div>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="mail"></ion-icon>
                </span>
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="lock-closed"></ion-icon>
                </span>
                <input type="password" name="password" required>
                <label>Password</label>
            </div>            
            <div class="remember-forgot">
                <label><input type="checkbox"> I agree to the terms & conditions</label>
            </div>
            <button type="submit" class="loginbutton">Register</button>
            <div class="login-register">
                <p>Already have an account? <a href="#" class="login-link">Login</a></p>
            </div>
        </form>
    </div>            
</div>

<script src="public/scripts.js" defer></script>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>