<?php
session_start();

$pageTitle = 'MIWovie - Log';
$backgroundImage = 'photos/starwarsbackG.jpg';
include 'temples/header.php';
include 'data/db_connection.php';

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
    // Retrieve form data
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate input
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

    // Check if email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'Email is already registered.';
        }
    }

    // If no errors, insert new user into the database
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->execute([
            'username' => $username,
            'email'    => $email,
            'password' => $hashedPassword
        ]);
        // Registration successful, redirect to login
        header('Location: index.php');
        exit();
    }
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Retrieve form data
    $login_email = $_POST['login_email'] ?? '';
    $login_password = $_POST['login_password'] ?? '';

    // Validate input
    if (empty($login_email) || empty($login_password)) {
        $login_error = 'Please fill in all fields.';
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $login_email]);
        $user = $stmt->fetch();

        // Verify user exists and password is correct
        if ($user && password_verify($login_password, $user['password'])) {
            // Authentication successful
            $_SESSION['user_id'] = $user['id'];
            header('Location: profile.php');
            exit();
        } else {
            // Authentication failed
            $login_error = 'Invalid email or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Patua+One&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div id="preloader">
        <div class="preloader-content">
            <h1 class="logo-animation">MIW</h1>
            <div class="tagline">
                <span class="word">Inspire.</span>
                <span class="word">Imagine.</span>
                <span class="word">Innovate.</span>
            </div>
        </div>
    </div>
    <div class="wrapper">
        <span class="icon-close">
            <ion-icon name="close"></ion-icon>
        </span>

        <!-- Login Form -->
        <div class="form-box login">
            <h2>Login</h2>
            <?php if ($login_error): ?>
                <ul class="error-list">
                    <li><?php echo htmlspecialchars($login_error); ?></li>
                </ul>
            <?php endif; ?>
            <form action="index.php" method="POST">
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input type="email" name="login_email" value="<?php echo htmlspecialchars($login_email); ?>" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="login_password" required>
                    <label>Password</label>
                </div>
                <div class="remember-forgot">
                    <label for="remember_me"><input type="checkbox" id="remember_me"> Remember me</label>
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit" name="login" class="loginbutton">Login</button>
                <div class="login-register">
                    <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
                </div>
            </form>
        </div>

        <!-- Registration Form -->
        <div class="form-box register">
            <h2>Registration</h2>
            <?php if ($errors): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $field => $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form action="index.php" method="POST">
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input type="email" id="email" name="email<?php echo htmlspecialchars($email); ?>" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="confirm_password" required>
                    <label>Confirm Password</label>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox" id="terms_agree" required> I agree to the terms & conditions</label>
                </div>
                <button type="submit" name="register" class="loginbutton">Register</button>
                <div class="login-register">
                    <p>Already have an account? <a href="#" class="login-link">Login</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="js/scripts.js"></script>
    <?php include 'temples/footer.php'; ?>
</body>
</html>