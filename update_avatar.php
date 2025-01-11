<?php
header('Content-Type: application/json');
session_start();
require_once 'data/db_connection.php';

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized');
    }

    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No file uploaded');
    }

    $userId = $_SESSION['user_id'];
    $file = $_FILES['avatar'];
    $uploadDir = 'uploads/avatars/';
    
    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Invalid file type');
    }

    // Create unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = 'avatar_' . $userId . '_' . time() . '.' . $extension;
    $targetPath = $uploadDir . $newFileName;

    // Ensure upload directory exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Failed to move uploaded file');
    }

    // Update database
    $stmt = $pdo->prepare("UPDATE users SET avatar_path = ? WHERE id = ?");
    if (!$stmt->execute([$targetPath, $userId])) {
        unlink($targetPath); // Remove uploaded file if DB update fails
        throw new Exception('Database update failed');
    }

    echo json_encode([
        'success' => true,
        'avatar_path' => $targetPath
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}