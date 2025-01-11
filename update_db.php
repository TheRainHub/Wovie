<?php
require 'data/db_connection.php';

// Configuration
$api_key = '394d146307bda71fae837829dde725e9';
$image_base_url = 'https://image.tmdb.org/t/p/';
$backdrop_size = 'original';
$local_backdrop_dir = 'images/backdrops/';

// Create directory
if (!file_exists($local_backdrop_dir)) {
    mkdir($local_backdrop_dir, 0777, true);
}

// Get all movies
$query = $pdo->query("SELECT id, title FROM movies");
$movies = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($movies as $movie) {
    echo "Processing: " . $movie['title'] . "\n";
    
    // Search TMDB
    $search_url = "https://api.themoviedb.org/3/search/movie?api_key=$api_key&query=" . urlencode($movie['title']);
    $search_response = json_decode(file_get_contents($search_url), true);

    if (!empty($search_response['results'])) {
        $tmdb_id = $search_response['results'][0]['id'];
        
        // Get movie images
        $images_url = "https://api.themoviedb.org/3/movie/$tmdb_id/images?api_key=$api_key";
        $images = json_decode(file_get_contents($images_url), true);

        if (!empty($images['backdrops'])) {
            // Get highest resolution backdrop
            $backdrop = $images['backdrops'][0];
            $backdrop_url = $image_base_url . $backdrop_size . $backdrop['file_path'];
            $local_path = $local_backdrop_dir . $movie['id'] . '_backdrop.jpg';
            
            // Download and save
            $image_data = file_get_contents($backdrop_url);
            if ($image_data) {
                file_put_contents($local_path, $image_data);
                $stmt = $pdo->prepare("UPDATE movies SET backdrop_url = ? WHERE id = ?");
                $stmt->execute([$local_path, $movie['id']]);
                echo "Updated backdrop for: " . $movie['title'] . "\n";
            }
        }
        
        sleep(1); // API rate limit
    }
}

echo "All backdrops updated!\n";
?>