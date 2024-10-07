<?php
session_start();
?>
<header>
    <h1 class="logo"><a href="index.php">MovieInfo</a></h1>
    <nav class="navigation">
        <a href="index.php">General</a>
        <a href="movies.php">Films</a>
        <a href="actors.php">Actors</a>
        <a href="directors.php">Directors</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="logout.php">Exit (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
        <?php else: ?>
            <a href="login_form.php">Login</a>
            <a href="register_form.php">Registration</a>
        <?php endif; ?>
    </nav>
</header>
