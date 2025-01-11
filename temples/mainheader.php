<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/styles.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Patua+One&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/print.css">
</head>
<body>
    <div id="blur-overlay"></div>
    <header id="main-header">
    <a href="home.php" class="logo-link">
        <div class="main-letter">W<span class="second-half">ovie</span></div></a>
        <div class="search-container">
            <form action="search.php" method="GET" class="search-bar" id="search-bar">
                <input type="text" 
                    name="query" 
                    id="search-input" 
                    placeholder="Search movies..." 
                    autocomplete="off"
                    required 
                    minlength="2">
                <button type="submit"><ion-icon name="search-outline"></ion-icon></button>
            </form>
            <div id="search-results" class="search-results"></div>
        </div>
        <nav class="nav-links">
            <a href="home.php">Home</a>
            <a href="top-rated.php">Top Rated</a>
            <a href="user_profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <script src="js/search.js"></script>
    <script src="js/logic.js"></script>
    <link rel="stylesheet" href="css/search.css">
</body>
</html>
