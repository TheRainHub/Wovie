<?php
require 'data/db_connection.php'; // Подключение к базе данных
include 'temples/header.php';

// Параметры пагинации
$limit = 30; // Количество фильмов на страницу
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// SQL-запрос на выбор случайных фильмов с пагинацией
$sql = "SELECT id, title, poster, rating FROM movies ORDER BY RAND() LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Общее количество фильмов для пагинации
$total_movies = $pdo->query("SELECT COUNT(*) AS total FROM movies")->fetch()['total'];
$total_pages = ceil($total_movies / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Gallery</title>
    <link rel="stylesheet" href="css/filmstyle.css">
    <script src="js/scripts.js" defer></script>
</head>
<body class="home-page">
    <main class="movie-gallery">
        <?php if (!empty($result)): ?>
            <?php foreach ($result as $movie): ?>
                <div class="movie-card">
                    <img src="<?= htmlspecialchars($movie['poster']); ?>" alt="<?= htmlspecialchars($movie['title']); ?>">
                    <div class="details">
                        <h3><?= htmlspecialchars($movie['title']); ?></h3>
                        <p>Rating: ⭐<?= htmlspecialchars($movie['rating']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No movies found</p>
        <?php endif; ?>
    </main>

    <nav class="pagination">
        <?php if ($page > 1): ?>
            <a href="home.php?page=<?= $page - 1; ?>" class="pagination-btn">&laquo; Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="home.php?page=<?= $i; ?>" class="pagination-btn <?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="home.php?page=<?= $page + 1; ?>" class="pagination-btn">Next &raquo;</a>
        <?php endif; ?>
    </nav>

</body>
</html>
