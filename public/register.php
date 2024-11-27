<?php
session_start();
require 'db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Данные получены<br>"; 

    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);

    if (empty($username) || !$email || empty($password)) {
        echo "Пожалуйста, заполните все поля корректно!";
        exit();
    }

    // Проверка уникальности email
    $stmt = $conn->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        echo "Этот email уже используется!";
        exit();
    }

    // Хеширование пароля
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$username, $email, $hashedPassword]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Не удалось зарегистрировать пользователя.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, 'error_log.txt');
        echo "Произошла ошибка. Попробуйте позже.";
    }
}
?>
