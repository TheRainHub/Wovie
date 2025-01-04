<?php
session_start();
header('Content-Type: application/json');
require 'data/db_connection.php';

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    return strlen($password) >= 8;
}

function handleRegistration($data) {
    global $pdo;
    
    $response = ['success' => false, 'errors' => []];
    
    // Validate username
    if (empty($data['username'])) {
        $response['errors']['username'] = 'Username is required';
    } elseif (strlen($data['username']) < 3) {
        $response['errors']['username'] = 'Username must be at least 3 characters long';
    }
    
    // Validate email
    if (empty($data['email'])) {
        $response['errors']['email'] = 'Email is required';
    } elseif (!validateEmail($data['email'])) {
        $response['errors']['email'] = 'Please enter a valid email';
    } else {
        // Check if email exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            $response['errors']['email'] = 'Email is already registered';
        }
    }
    
    // Validate password
    if (empty($data['password'])) {
        $response['errors']['password'] = 'Password is required';
    } elseif (!validatePassword($data['password'])) {
        $response['errors']['password'] = 'Password must be at least 8 characters long';
    }
    
    // Validate password confirmation
    if ($data['password'] !== $data['confirm_password']) {
        $response['errors']['confirm_password'] = 'Passwords do not match';
    }
    
    // If no errors, proceed with registration
    if (empty($response['errors'])) {
        try {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$data['username'], $data['email'], $hashedPassword]);
            
            $response['success'] = true;
            $response['message'] = 'Registration successful!';
            $_SESSION['user_id'] = $pdo->lastInsertId();
        } catch (PDOException $e) {
            $response['errors']['general'] = 'Registration failed. Please try again.';
        }
    }
    
    return $response;
}

function handleLogin($data) {
    global $pdo;
    
    $response = ['success' => false, 'errors' => []];
    
    if (empty($data['email']) || empty($data['password'])) {
        $response['errors']['general'] = 'Please fill in all fields';
        return $response;
    }
    
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($data['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $response['success'] = true;
            $response['message'] = 'Login successful!';
        } else {
            $response['errors']['general'] = 'Invalid email or password';
        }
    } catch (PDOException $e) {
        $response['errors']['general'] = 'Login failed. Please try again.';
    }
    
    return $response;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['action'])) {
        switch ($data['action']) {
            case 'register':
                echo json_encode(handleRegistration($data));
                break;
            case 'login':
                echo json_encode(handleLogin($data));
                break;
            case 'check_email':
                $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
                $stmt->execute([$data['email']]);
                echo json_encode(['exists' => (bool)$stmt->fetch()]);
                break;
        }
    }
}
?>