<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Данные получены<br>"; 

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Проверка на пустые поля
    if (empty($username) || empty($email) || empty($password)) {
        echo "Пожалуйста, заполните все поля!";
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$username, $email, $hashedPassword]);

        $_SESSION['user_id'] = $conn->lastInsertId();
        $_SESSION['username'] = $username;

        echo "Регистрация прошла успешно!";
    } catch (PDOException $e) {
        echo "Ошибка при добавлении пользователя: " . $e->getMessage();
    }
}
?>
