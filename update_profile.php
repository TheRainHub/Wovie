<?php
session_start();
require_once 'data/db_connection.php';

// Проверьте авторизацию
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];

// Проверьте, что запрос сделан через POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Метод не разрешен
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

// Получение данных из POST
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$currentPassword = $_POST['current_password'] ?? null;
$newPassword = $_POST['new_password'] ?? null;

// Валидация данных
if (!$username || !$email) {
    http_response_code(400); // Неполные данные
    echo json_encode(['error' => 'Username and email are required']);
    exit;
}

try {
    // Получение текущего пароля из базы данных
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404); // Пользователь не найден
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    // Проверка текущего пароля
    if ($currentPassword && !password_verify($currentPassword, $user['password'])) {
        http_response_code(401); // Неверный пароль
        echo json_encode(['error' => 'Invalid current password']);
        exit;
    }

    // Обновление данных пользователя
    $updateFields = ['username' => $username, 'email' => $email];
    if ($newPassword) {
        $updateFields['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
    }

    $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($updateFields)));

    $stmt = $pdo->prepare("UPDATE users SET $setClause WHERE id = :id");
    $updateFields['id'] = $userId;
    $stmt->execute($updateFields);

    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
} catch (PDOException $e) {
    http_response_code(500); // Ошибка базы данных
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}
