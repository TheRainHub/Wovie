<?php
function checkAchievements($userId, $pdo) {
    // Считаем количество фильмов в избранном
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ?");
    $stmt->execute([$userId]);
    $favCount = $stmt->fetchColumn();
    
    // Считаем комментарии
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE user_id = ?");
    $stmt->execute([$userId]);
    $commCount = $stmt->fetchColumn();
    
    // Массив с достижениями
    $achievements = [];
    
    // Проверяем условия
    if ($favCount >= 1) $achievements[] = 1; // ID достижения "Первый фаворит"
    if ($favCount >= 10) $achievements[] = 2; // ID "Коллекционер"
    if ($commCount >= 1) $achievements[] = 3; // ID "Первый комментарий"
    if ($commCount >= 10) $achievements[] = 4; // ID "Активный критик"
    
    // Добавляем достижения, которых еще нет
    foreach ($achievements as $achievementId) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO user_achievements (user_id, achievement_id, date_earned) 
                            VALUES (?, ?, NOW())");
        $stmt->execute([$userId, $achievementId]);
    }
}
?>