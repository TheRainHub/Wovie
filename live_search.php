<?php
require 'data/db_connection.php';
header('Content-Type: application/json');

if (isset($_GET['query']) && strlen($_GET['query']) >= 2) {
    $query = '%' . $_GET['query'] . '%';
    $stmt = $pdo->prepare("SELECT id, title, poster_url, release_date FROM movies WHERE title LIKE ? LIMIT 5");
    $stmt->execute([$query]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
?>