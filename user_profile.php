<?php
session_start();
require_once 'data/db_connection.php';
include 'temples/mainheader.php';

// Check authorization
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Get user data
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT u.*, 
        COUNT(DISTINCT f.id) as favorites_count,
        COUNT(DISTINCT c.id) as comments_count
    FROM users u
    LEFT JOIN favorites f ON u.id = f.user_id
    LEFT JOIN comments c ON u.id = c.user_id
    WHERE u.id = ?
    GROUP BY u.id
");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Set default avatar if not exists
if (!empty($user['avatar_path']) && file_exists($user['avatar_path'])) {
    $avatarPath = htmlspecialchars($user['avatar_path']);
} else {
    $avatarPath = 'uploads/avatars/default-avatar.jpg';
}

// Get achievements
$stmt = $pdo->prepare("
    SELECT a.*, ua.date_earned 
    FROM achievements a
    JOIN user_achievements ua ON a.id = ua.achievement_id
    WHERE ua.user_id = ?
");
$stmt->execute([$userId]);
$achievements = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get favorite movies
$stmt = $pdo->prepare("
    SELECT m.*, f.date_added 
    FROM movies m
    JOIN favorites f ON m.id = f.movie_id
    WHERE f.user_id = ?
    ORDER BY f.date_added DESC
    LIMIT 6
");
$stmt->execute([$userId]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle avatar upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка, что файл отправлен
    if (isset($_FILES['avatar'])) {
        echo '<pre>';
        var_dump($_FILES['avatar']);
        echo '</pre>';
    } else {
        echo 'No file uploaded.';
    }

    // Логика загрузки файла
    $uploadDir = 'uploads/avatars/';
    $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);

    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
        $stmt = $pdo->prepare("UPDATE users SET avatar_path = ? WHERE id = ?");
        $stmt->execute([$uploadFile, $userId]);
        header('Location: user_profile.php');
        exit;
    } else {
        echo "File upload error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo htmlspecialchars($user['username']); ?></title>
    <link rel="stylesheet" href="css/user_profile.css">
</head>
<body>
    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="avatar-section">
                <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="Avatar" class="avatar">
                <label for="avatar-upload" class="avatar-upload">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                        <path d="M3 4v16h18V4H3zm16 14H5V6h14v12zM11 7H9v3H6v2h3v3h2v-3h3v-2h-3V7z"/>
                    </svg>
                </label>
                <input type="file" name="avatar" id="avatar-upload" style="display: none" accept="image/*">
            </div>
            <div class="profile-info">
                <h1 class="username"><?php echo htmlspecialchars($user['username']); ?></h1>
                <p class="join-date">Member since <?php echo date('m/d/Y', strtotime($user['created_at'])); ?></p>
                <button class="btn btn-primary" onclick="openSettingsModal()">Edit Profile</button>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?php echo $user['favorites_count']; ?></div>
                <div class="stat-label">Favorites</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $user['comments_count']; ?></div>
                <div class="stat-label">Comments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($achievements); ?></div>
                <div class="stat-label">Achievements</div>
            </div>
        </div>

        <!-- Achievements -->
        <div class="achievements-section">
            <h2>Achievements</h2>
            <div class="achievements-grid">
                <?php foreach ($achievements as $achievement): ?>
                <div class="achievement-card">
                    <img src="<?php echo htmlspecialchars($achievement['icon_path']); ?>" alt="<?php echo htmlspecialchars($achievement['name']); ?>" class="achievement-icon">
                    <div class="achievement-name"><?php echo htmlspecialchars($achievement['name']); ?></div>
                    <div class="achievement-date">Earned on <?php echo date('m/d/Y', strtotime($achievement['date_earned'])); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Favorite Movies -->
        <div class="favorites-section">
            <h2>Favorite Movies</h2>
            <div class="movies-grid">
                <?php foreach ($favorites as $movie): ?>
                <div class="movie-card">
                    <img src="<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>" class="movie-poster">
                    <div class="movie-info">
                        <div class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></div>
                        <div class="movie-year"><?php echo $movie['release_year']; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="modal" id="settings-modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeSettingsModal()">&times;</span>
            <h2>Profile Settings</h2>
            <form id="profile-settings-form" method="post" action="update_profile.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" name="current_password">
                </div>
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new_password">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="js/user_profile.js"></script>
</body>
</html>