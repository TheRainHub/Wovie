<?php
// temples/header.php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/styles.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <header>
        <h1 class="logo">
            <span class="mi"></span>
            <span class="main-letter">W</span>
            <span class="second-half">ovie</span>
        </h1>
        <nav class="navigation">   
        <a href="#">Home</a>
        <a href="#">Articles</a>
        <a href="#">Top Rated</a>
        <a href="#">Discussions</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- User is logged in -->
                <a href="user_profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <!-- User is not logged in -->
                <button class="loginbutton-popup">Login</button>
            <?php endif; ?>
        </nav>
    </header>