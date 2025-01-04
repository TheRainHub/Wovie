<?php
// Подключение к базе данных
require 'data/db_connection.php';

// Получаем ID фильма из параметров URL
$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($movie_id > 0) {
    // Запрос информации о фильме
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = :id");
    $stmt->execute(['id' => $movie_id]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$movie) {
        die('Фильм не найден');
    }
} else {
    die('Неверный ID фильма');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?> - Информация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="movie-container">
        <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
        <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>" alt="Постер фильма" class="movie-poster">
        <p><strong>Описание:</strong> <?php echo htmlspecialchars($movie['description']); ?></p>
        <p><strong>Дата выхода:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
        <p><strong>Режиссёр:</strong> <?php echo htmlspecialchars($movie['director']); ?></p>
        <p><strong>Жанр:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
        <p><strong>Рейтинг:</strong> <?php echo htmlspecialchars($movie['rating']); ?>/10</p>
        <a href="<?php echo htmlspecialchars($movie['trailer_url']); ?>" target="_blank">Смотреть трейлер</a>
        <br>
        <a href="index.php">Вернуться на главную</a>
    </div>
</body>
</html>

