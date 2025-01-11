<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Initialize variables
$pageTitle = 'Wovie';
$backgroundImage = 'photos/starwarsbackG.jpg';
$errors = [];
$username = '';
$email = '';
$password = '';
$confirm_password = '';
$login_email = '';
$login_password = '';
$login_error = '';

// Include required files
require 'data/db_connection.php';
include 'temples/header.php';


// Функция для обработки загрузки аватара
function processAvatar($file) {
    // Проверяем, был ли файл загружен
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return 'uploads/avatars/default-avatar.jpg';
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Ошибка при загрузке файла: ' . $file['error']);
    }

    // Определяем абсолютный путь к директории загрузки
    $uploadDir = __DIR__ . '/uploads/avatars/';
    
    // Проверяем и создаем директорию
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            throw new Exception('Не удалось создать директорию для загрузки');
        }
    }

    // Проверяем права доступа
    if (!is_writable($uploadDir)) {
        chmod($uploadDir, 0777);
        if (!is_writable($uploadDir)) {
            throw new Exception('Директория загрузки недоступна для записи');
        }
    }

    // Проверяем размер файла (5MB максимум)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('Размер файла превышает 5MB');
    }

    // Проверяем тип файла
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception('Недопустимый тип файла. Разрешены только JPG, PNG и GIF');
    }

    // Генерируем уникальное имя файла
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = uniqid('avatar_', true) . '.' . $extension;
    $targetPath = $uploadDir . $fileName;

    // Перемещаем загруженный файл
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Не удалось сохранить файл');
    }

    return 'uploads/avatars/' . $fileName;
}

// Обработка отправки формы регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    try {
        // Валидация входных данных
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        // Проверка обязательных полей
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            throw new Exception('Все поля обязательны для заполнения');
        }

        // Проверка совпадения паролей
        if ($password !== $confirm_password) {
            throw new Exception('Пароли не совпадают');
        }

        // Обработка аватара
        try {
            $avatar_path = processAvatar($_FILES['avatar'] ?? null);
        } catch (Exception $e) {
            $errors[] = "Ошибка загрузки аватара: " . $e->getMessage();
            $avatar_path = 'uploads/avatars/default-avatar.jpg';
        }

        // Если нет ошибок, сохраняем в базу
        if (empty($errors)) {
            $pdo->beginTransaction();

            try {
                // Проверка существующего email
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    throw new Exception("Этот email уже зарегистрирован");
                }

                // Добавление пользователя
                $sql = "INSERT INTO users (username, email, password, avatar_path, created_at) 
                        VALUES (:username, :email, :password, :avatar_path, NOW())";
                $stmt = $pdo->prepare($sql);

                $result = $stmt->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => password_hash($password, PASSWORD_DEFAULT),
                    ':avatar_path' => $avatar_path
                ]);

                if (!$result) {
                    throw new Exception("Ошибка при сохранении данных");
                }

                $pdo->commit();
                $_SESSION['success'] = "Регистрация успешно завершена!";
                header('Location: login.php');
                exit;

            } catch (Exception $e) {
                $pdo->rollBack();
                $errors[] = $e->getMessage();
            }
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
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
    <!-- Preloader -->
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

    <!-- Main Content -->
    <div class="wrapper">
        <span class="icon-close">
            <ion-icon name="close"></ion-icon>
        </span>

        <!-- Login Form -->
        <div class="form-box login">
            <h2>Login</h2>
            <?php if (!empty($login_error)): ?>
                <div class="error-list">
                    <p><?php echo htmlspecialchars($login_error); ?></p>
                </div>
            <?php endif; ?>
            <form id="loginForm" action="index.php" method="POST">
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input 
                        type="email" 
                        name="login_email" 
                        value="<?php echo htmlspecialchars($login_email); ?>" 
                        required
                    >
                    <label>Email</label>
                </div>
                <div class="error-message"></div>

                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input 
                        type="password" 
                        name="login_password" 
                        required
                    >
                    <label>Password</label>
                </div>
                <div class="error-message"></div>

                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="remember_me"> Remember me
                    </label>
                    <a href="forgot-password.php">Forgot Password?</a>
                </div>

                <button type="submit" name="login" class="loginbutton">
                    <span class="button-text">Login</span>
                    <span class="button-loader"></span>
                </button>

                <div class="login-register">
                    <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
                </div>
            </form>
        </div>

        <!-- Registration Form -->
        <div class="form-box register">
            <h2>Registration</h2>
            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form id="registerForm" action="index.php" method="POST" enctype="multipart/form-data">

            <div class="input-box avatar-upload">
                    <input type="file"
                        id="avatar"
                        name="avatar"
                        accept="image/*"
                        >
                    <label for="avatar" class="avatar-label">
                        <div class="avatar-preview">
                            <img id="avatar-preview-img" src="photos/uploads/avatars/default-avatar.jpg" alt="Avatar preview">
                        </div>
                    </label>
                    <div id="avatar-error"></div>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <input 
                        type="text" 
                        name="username" 
                        value="<?php echo htmlspecialchars($username); ?>" 
                        required
                        minlength="3"
                    >
                    <label>Username</label>
                </div>
                <div class="error-message"></div>

                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input 
                        type="email" 
                        name="email" 
                        value="<?php echo htmlspecialchars($email); ?>" 
                        required
                    >
                    <label>Email</label>
                </div>
                <div class="error-message"></div>

                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        minlength="8"
                    >
                    <label>Password</label>
                </div>
                <div class="error-message"></div>

                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input 
                        type="password" 
                        name="confirm_password" 
                        required
                    >
                    <label>Confirm Password</label>
                </div>
                <div class="error-message"></div>


                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="terms_agree" required> 
                        I agree to the <a href="terms.php">terms & conditions</a>
                    </label>
                </div>

                <button type="submit" name="register" class="loginbutton">
                    <span class="button-text">Register</span>
                    <span class="button-loader"></span>
                </button>

                <div class="login-register">
                    <p>Already have an account? <a href="#" class="login-link">Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/scripts.js"></script>
    <script src="js/auth.js"></script>
    
    <?php include 'temples/footer.php'; ?>
</body>
</html>