<?php
require 'data/db_connection.php'; // Подключение к базе данных

$apiKey = '394d146307bda71fae837829dde725e9';
$maxPages = 50; // Максимум 50 страниц (50 * 20 фильмов = 1000 фильмов)

for ($page = 1; $page <= $maxPages; $page++) {
    $apiUrl = "https://api.themoviedb.org/3/movie/popular?api_key=$apiKey&language=en-US&page=$page";

    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if (!isset($data['results']) || empty($data['results'])) {
        echo "No more movies to load. Stopping.";
        break;
    }

    // Обновленный запрос
    $stmtInsert = $pdo->prepare("
        INSERT INTO movies (id, title, poster, rating, genre_ids, release_date) 
        VALUES (:id, :title, :poster, :rating, :genre_ids, :release_date)
        ON DUPLICATE KEY UPDATE 
            title=VALUES(title), 
            poster=VALUES(poster), 
            rating=VALUES(rating), 
            genre_ids=VALUES(genre_ids),
            release_date=VALUES(release_date)
    ");

    foreach ($data['results'] as $movie) {
        $stmtInsert->execute([
            'id' => $movie['id'],
            'title' => $movie['title'],
            'poster' => isset($movie['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] : null,
            'rating' => $movie['vote_average'],
            'genre_ids' => implode(',', $movie['genre_ids'] ?? []),
            'release_date' => $movie['release_date'] ?? null, // Добавляем дату выхода
        ]);
    }

    echo "Page $page loaded successfully.<br>";
}

echo "All movies loaded.";

