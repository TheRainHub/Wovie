<?php
// process_avatar.php
session_start();
require_once 'db_connection.php';

function uploadAvatar($file, $userId) {
    $uploadDir = 'uploads/avatars/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    // Проверки
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Неверный формат файла'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'Файл слишком большой'];
    }
    
    // Создаем имя файла
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = $userId . '_' . time() . '.' . $extension;
    $targetPath = $uploadDir . $newFileName;
    
    // Загружаем файл
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'path' => $targetPath];
    }
    
    return ['success' => false, 'message' => 'Ошибка при загрузке'];
}

if (isset($_FILES['avatar']) && isset($_SESSION['user_id'])) {
    $result = uploadAvatar($_FILES['avatar'], $_SESSION['user_id']);
    
    if ($result['success']) {
        // Обновляем в базе
        $stmt = $pdo->prepare("UPDATE users SET avatar_path = ? WHERE id = ?");
        $stmt->execute([$result['path'], $_SESSION['user_id']]);
        
        echo json_encode(['success' => true, 'avatar_path' => $result['path']]);
    } else {
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
}