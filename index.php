<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>MIWovie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <h1>Welcome to MIW!</h1>
        <?php if (isset($_SESSION['username'])): ?>
            <p>Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        <?php else: ?>
            <p>Please, <a href="login_form.php">login</a> or <a href="register_form.php">register</a>.</p>
        <?php endif; ?>

        <h2>Last film</h2>
        <div class="movies-container">
            <?php
    
            $stmt = $conn->query("SELECT id, title, poster_path FROM movies ORDER BY release_date DESC LIMIT 10");
            while ($movie = $stmt->fetch()):
            ?>
                <div class="movie">
                    <a href="movie.php?id=<?php echo $movie['id']; ?>">
                        <img src="images/movies/<?php echo $movie['poster_path']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
