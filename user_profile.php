<?php
session_start();
require 'data/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Get user stats
$stmt = $pdo->prepare("
    SELECT 
        (SELECT COUNT(*) FROM comments WHERE user_id = ?) as total_comments,
        (SELECT COUNT(*) FROM favorite_movies WHERE user_id = ?) as total_favorites,
        (SELECT COUNT(*) FROM movie_ratings WHERE user_id = ?) as total_ratings
");
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']]);
$stats = $stmt->fetch();

$pageTitle = "Profile - " . htmlspecialchars($user['username']);
include 'temples/mainheader.php';
?>
<link rel="stylesheet" href="css/user_profile.css">
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-avatar">
            <img src="<?php echo $user['avatar_url'] ?: 'images/default-avatar.jpg'; ?>" alt="Profile Photo">
            <form action="upload_avatar.php" method="POST" enctype="multipart/form-data" class="avatar-upload">
                <input type="file" name="avatar" id="avatar" accept="image/*">
                <button type="submit">Update Photo</button>
            </form>
        </div>
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($user['username']); ?></h1>
            <div class="profile-stats">
                <div class="stat">
                    <span class="number"><?php echo $stats['total_comments']; ?></span>
                    <span class="label">Comments</span>
                </div>
                <div class="stat">
                    <span class="number"><?php echo $stats['total_favorites']; ?></span>
                    <span class="label">Favorites</span>
                </div>
                <div class="stat">
                    <span class="number"><?php echo $stats['total_ratings']; ?></span>
                    <span class="label">Ratings</span>
                </div>
            </div>
        </div>
    </div>

    <div class="profile-content">
        <div class="profile-section">
            <h2>Account Settings</h2>
            <form action="update_profile.php" method="POST" class="settings-form">
                <div class="form-group">
                    <label for="newUsername">Change Username:</label>
                    <input type="text" id="newUsername" name="newUsername" value="<?php echo htmlspecialchars($user['username']); ?>">
                </div>
                <div class="form-group">
                    <label for="newPassword">Change Password:</label>
                    <input type="password" id="newPassword" name="newPassword">
                </div>
                <div class="form-group">
                    <label for="bio">About Me:</label>
                    <textarea id="bio" name="bio"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                </div>
                <button type="submit" class="save-button">Save Changes</button>
            </form>
        </div>

        <div class="profile-section">
            <h2>My Movie Lists</h2>
            <div class="movie-lists">
                <div class="list-section favorites">
                    <h3>Favorite Movies</h3>
                    <div class="movies-grid">
                        <?php
                        $stmt = $pdo->prepare("
                            SELECT m.* FROM movies m
                            JOIN favorite_movies f ON m.id = f.movie_id
                            WHERE f.user_id = ?
                            LIMIT 6
                        ");
                        $stmt->execute([$_SESSION['user_id']]);
                        while ($movie = $stmt->fetch()) {
                            echo '<div class="movie-card mini">';
                            echo '<img src="' . htmlspecialchars($movie['poster_url']) . '" alt="' . htmlspecialchars($movie['title']) . '">';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="list-section watchlist">
                    <h3>Watch Later</h3>
                    <div class="movies-grid">
                        <?php
                        $stmt = $pdo->prepare("
                            SELECT m.* FROM movies m
                            JOIN watchlist w ON m.id = w.movie_id
                            WHERE w.user_id = ?
                            LIMIT 6
                        ");
                        $stmt->execute([$_SESSION['user_id']]);
                        while ($movie = $stmt->fetch()) {
                            echo '<div class="movie-card mini">';
                            echo '<img src="' . htmlspecialchars($movie['poster_url']) . '" alt="' . htmlspecialchars($movie['title']) . '">';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-section">
            <h2>Recent Activity</h2>
            <div class="activity-feed">
                <?php
                $stmt = $pdo->prepare("
                    SELECT c.*, m.title as movie_title 
                    FROM comments c
                    JOIN movies m ON c.movie_id = m.id
                    WHERE c.user_id = ?
                    ORDER BY c.created_at DESC
                    LIMIT 5
                ");
                $stmt->execute([$_SESSION['user_id']]);
                while ($comment = $stmt->fetch()) {
                    echo '<div class="activity-item">';
                    echo '<span class="activity-date">' . date('M d, Y', strtotime($comment['created_at'])) . '</span>';
                    echo '<p>Commented on <a href="movie_details.php?id=' . $comment['movie_id'] . '">' . htmlspecialchars($comment['movie_title']) . '</a></p>';
                    echo '<p class="comment-text">"' . htmlspecialchars($comment['content']) . '"</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>