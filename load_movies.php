<?php
// load_movies.php
require 'data/db_connection.php';
session_start();

// Параметры API и пагинации
$apiKey = '';
$limit = 32;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Получение общего количества фильмов
$totalMovies = $pdo->query("SELECT COUNT(*) FROM movies")->fetchColumn();
$totalPages = ceil($totalMovies / $limit);

// Проверка необходимости загрузки новых фильмов
if ($totalMovies < 1000) {
    $apiUrl = "https://api.themoviedb.org/3/movie/popular?api_key=$apiKey&language=ru-RU&page=$page";
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);
    
    if (isset($data['results']) && !empty($data['results'])) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO movies (id, title, poster, rating, genre_ids)
            VALUES (:id, :title, :poster, :rating, :genre_ids)
        ");
        
        foreach ($data['results'] as $movie) {
            $stmt->execute([
                'id' => $movie['id'],
                'title' => $movie['title'],
                'poster' => "https://image.tmdb.org/t/p/w500" . $movie['poster_path'],
                'rating' => $movie['vote_average'],
                'genre_ids' => implode(',', $movie['genre_ids'] ?? [])
            ]);
        }
        
        $totalMovies = $pdo->query("SELECT COUNT(*) FROM movies")->fetchColumn();
        $totalPages = ceil($totalMovies / $limit);
    }
}

// Проверяем существование seed для текущей сессии
if (!isset($_SESSION['random_seed'])) {
    $_SESSION['random_seed'] = mt_rand();
    $_SESSION['last_reset'] = time();
}

// Проверяем не истекла ли сессия (24 часа)
if (time() - $_SESSION['last_reset'] > 86400) {
    $_SESSION['random_seed'] = mt_rand();
    $_SESSION['last_reset'] = time();
}

// Используем seed для генерации случайного порядка
$seed = $_SESSION['random_seed'];
$sql = "SELECT * FROM movies ORDER BY (id * $seed) % 1000000 LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Подготовка данных для ответа
foreach ($movies as &$movie) {
    $movie['genre_ids'] = explode(',', $movie['genre_ids']);
}

header('Content-Type: application/json');
echo json_encode([
    'movies' => $movies,
    'total_pages' => $totalPages,
    'current_page' => $page,
    'total_movies' => $totalMovies,
    'session_id' => session_id()
]);
