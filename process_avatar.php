<?php
$newImage = '';
function processAvatar($file) {
    $uploadDir = 'photos/uploads/avatars/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Проверка загруженного файла
    if (!isset($file) || !is_array($file) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No file uploaded or upload error occurred.');
    }

    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        throw new Exception('File size too large. Maximum is 5MB.');
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Invalid file type. Only JPG, PNG and GIF allowed.');
    }

    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    // Image compression
    $image = imagecreatefromstring(file_get_contents($file['tmp_name']));
    if (!$image) {
        throw new Exception('Invalid image file.');
    }

    $width = imagesx($image);
    $height = imagesy($image);

    // Resize if too large
    $maxDimension = 500;
    if ($width > $maxDimension || $height > $maxDimension) {
        $ratio = $width / $height;
        if ($ratio > 1) {
            $newWidth = $maxDimension;
            $newHeight = $maxDimension / $ratio;
        } else {
            $newHeight = $maxDimension;
            $newWidth = $maxDimension * $ratio;
        }

        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        switch($file['type']) {
            case 'image/jpeg':
                imagejpeg($newImage, $targetPath, 75);
                break;
            case 'image/png':
                imagepng($newImage, $targetPath, 7);
                break;
            case 'image/gif':
                imagegif($newImage, $targetPath);
                break;
        }
        imagedestroy($newImage);
        imagedestroy($image);
    } else {
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Failed to move uploaded file.');
        }
    }

    if (!file_exists($targetPath)) {
        throw new Exception('Failed to save image.');
    }

    return $uploadDir . $fileName; // Возвращаем относительный путь
}
?>