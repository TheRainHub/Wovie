<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Page</title>
    <!-- Подключение стилей -->
    <link rel="stylesheet" href="public/style.css">
    <link rel="icon" href="path-to-your-favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- Прелоадер -->
    <div id="preloader">
        <div class="preloader-content">
            <h1 class="logo-animation">Logo</h1>
            <div class="tagline">
                <span class="word">Welcome</span>
                <span class="word">to</span>
                <span class="word">PHP</span>
            </div>
        </div>
    </div>

    <!-- Основное содержимое -->
    <header>
        <div class="logo">
            <span class="main-letter">PHP</span>
            <span class="second-half">Dynamic</span>
        </div>
        <nav class="navigation">
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
            <button class="loginbutton-popup">Login</button>
        </nav>
    </header>

    <div class="wrapper">
        <div class="form-box login">
            <h2>Login</h2>
            <form>
                <div class="input-box">
                    <input type="text" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
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
            <h2>Register</h2>
            <form>
                <div class="input-box">
                    <input type="text" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <input type="email" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <input type="password" required>
                    <label>Password</label>
                </div>
                <button type="submit" class="loginbutton">Register</button>
                <div class="login-register">
                    <p>Already have an account? <a href="#" class="login-link">Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Контейнер фильмов -->
    <div class="movies-container">
        <div class="movie">
            <img src="path-to-movie-image.jpg" alt="Movie Poster">
            <h3>Movie Title</h3>
        </div>
        <!-- Добавьте больше фильмов по необходимости -->
    </div>

    <!-- Подключение JavaScript -->
    <script src="scripts.js"></script>
</body>
</html>
