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
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="home.php">Home</a>
                <a href="articles.php">Articles</a>
                <a href="top_rated.php">Top Rated</a>
                <a href="discussions.php">Discussions</a>
                <a href="user_profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="#" class="nav-link" data-requires-auth>Home</a>
                <a href="#" class="nav-link" data-requires-auth>Articles</a>
                <a href="#" class="nav-link" data-requires-auth>Top Rated</a>
                <a href="#" class="nav-link" data-requires-auth>Discussions</a>
                <button class="loginbutton-popup">Login</button>
            <?php endif; ?>
        </nav>
    </header>
    <script src="js/scripts.js" defer></script>
</body>
</html>