<?php
// profile.php

include 'includes/header.php';
include 'includes/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Retrieve user information
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    // User not found, log out
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

$pageTitle = 'Profile - ' . htmlspecialchars($user['username']);
?>

<div class="content">
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <!-- Add more profile details here -->
</div>

<?php include 'includes/footer.php'; ?>
