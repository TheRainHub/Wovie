<?php
session_start();
require 'data/db_connection.php';

function getYoutubeId($url) {
    if (empty($url)) return '';
    $pattern = '~(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\\s]{11})~i';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return '';
}

$movie_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT m.*, 
    GROUP_CONCAT(a.id ORDER BY ma.actor_id) as actor_ids,
    GROUP_CONCAT(a.name ORDER BY ma.actor_id) as actor_names,
    GROUP_CONCAT(ma.role ORDER BY ma.actor_id) as actor_roles,
    GROUP_CONCAT(a.profile_photo ORDER BY ma.actor_id) as actor_photos
FROM movies m
LEFT JOIN movie_actors ma ON m.id = ma.movie_id
LEFT JOIN actors a ON ma.actor_id = a.id
WHERE m.id = ?
GROUP BY m.id");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT c.*, u.username 
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.movie_id = ?
    ORDER BY c.created_at DESC");
$stmt->execute([$movie_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = htmlspecialchars($movie['title']) . " - Movie Details";
include 'temples/mainheader.php';
?>
<link rel="stylesheet" href="css/movie_details.css">
<div class="movie-container">
    <div class="movie-header" style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.6), #121212), url('<?php echo htmlspecialchars($movie['poster_url']); ?>');">
        <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
        <p><?php echo htmlspecialchars($movie['description']); ?></p>
    </div>

    <div class="content-wrapper">
    <div class="trailer-section">
        <?php if ($movie['trailer_url']): ?>
            <div class="trailer-container">
                <iframe 
                    src="https://www.youtube.com/embed/<?php echo htmlspecialchars(getYoutubeId($movie['trailer_url'])); ?>" 
                    frameborder="0" 
                    allowfullscreen>
                </iframe>
            </div>
        <?php else: ?>
            <p>Trailer not available</p>
        <?php endif; ?>
    </div>

        <div class="cast-section">
            <h2>Cast</h2>
            <div class="cast-grid">
                <?php
                if ($movie['actor_names'] && $movie['actor_roles'] && $movie['actor_photos']) {
                    $actors = array_map('trim', explode(',', $movie['actor_names']));
                    $roles = array_map('trim', explode(',', $movie['actor_roles']));
                    $photos = array_map('trim', explode(',', $movie['actor_photos']));
                    $ids = array_map('trim', explode(',', $movie['actor_ids']));
                    
                    $total = count($ids);
                    
                    for ($i = 0; $i < $total; $i++) {
                        echo '<div class="cast-member">';
                        if (!empty($photos[$i])) {
                            echo '<img src="' . htmlspecialchars($photos[$i]) . '" alt="' . htmlspecialchars($actors[$i]) . '">';
                        } else {
                            echo '<img src="images/default-avatar.jpg" alt="No photo available">';
                        }
                        echo '<h4>' . htmlspecialchars($actors[$i]) . '</h4>';
                        echo '<p>' . htmlspecialchars($roles[$i]) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No cast information available.</p>';
                }
                ?>
            </div>
        </div>

        <div class="comments-section">
            <h2>Comments</h2>
            <?php if ($comments): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                        <p><?php echo htmlspecialchars($comment['content']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No comments yet. Be the first to share your thoughts!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

