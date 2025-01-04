<?php
session_start();

$pageTitle = 'Wovie';
$backgroundImage = 'photos/starwarsbackG.jpg';
include 'temples/header.php';
require 'data/db_connection.php';

// Initialize variables for registration
$username = '';
$email = '';
$password = '';
$confirm_password = '';
$errors = [];

// Initialize variables for login
$login_email = '';
$login_password = '';
$login_error = '';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username)) {
        $errors['username'] = 'Please enter a username.';
    }
    if (empty($email)) {
        $errors['email'] = 'Please enter an email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email.';
    }
    if (empty($password)) {
        $errors['password'] = 'Please enter a password.';
    }
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'Email is already registered.';
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->execute([
            'username' => $username,
            'email'    => $email,
            'password' => $hashedPassword
        ]);
        header('Location: home.php');
        exit();
    }
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $login_email = $_POST['login_email'] ?? '';
    $login_password = $_POST['login_password'] ?? '';

    if (empty($login_email) || empty($login_password)) {
        $login_error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $login_email]);
        $user = $stmt->fetch();

        if ($user && password_verify($login_password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: home.php');
            exit();
        } else {
            $login_error = 'Invalid email or password.';
        }
    }
}
?>