<?php
include 'temples/mainheader.php';
// require 'db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supernatural</title>
    <style>

body {
    box-sizing: border-box;
    margin: 0;
    font-family: 'Arial', sans-serif;
    background: 
        url('photos/background.jpg') no-repeat center center fixed;
    background-size: cover;
    line-height: 1.5;
    color: #ffffff;
    position: relative;
}

.container {
    padding: 40px;
    max-width: 700px; /* Уменьшенная ширина контейнера для текстового блока */
    margin: auto;
    margin-left: 10%; /* Сдвиг текста ближе к центру */
    z-index: 10;
    position: relative;
}

h1 {
    font-size: 3rem;
    margin-bottom: 20px;
}

.info {
    margin-bottom: 30px;
    opacity: 0.8;
}

.tags span {
    background-color: rgba(255, 255, 255, 0.2);
    padding: 5px 10px;
    border-radius: 5px;
    margin-right: 10px;
    margin-bottom: 50px;
}

.buttons button {
    padding: 10px 20px;
    font-size: 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.buttons .play {
    background-color: #ff9800;
    color: black;
    margin-top: 20px;
}

.buttons .database {
    background-color: rgba(255, 255, 255, 0.2);
}

.image-overlay {
    position: absolute;
    top: 0;
    right: 0;
    width: 55%; /* Правая половина экрана */
    height: 950px; /* На весь экран по высоте */
    overflow: hidden; /* Скрыть выходящие за края части изображения */
    z-index: 10;
    background: 
        linear-gradient(50deg, black 1%, transparent 80%), /* Градиент для размытия */
        url('photos/Rocky-PNG-Clipart-Background.png') no-repeat center center;
    background-size: cover; /* Растягивает изображение на всю правую часть */
    filter: blur(1px); /* Эффект размытия */
}

/* Для точной настройки изображения */
.image-overlay img {
    display: none; /* Прямой тег img здесь не нужен, фон задаётся стилем */
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Rocky</h1>
        <div class="info">
            <p>44 minutes | 2005 | 8.4 ⭐</p>
            <p>
                Rocky raised his two sons Sam and Dean to hunt and kill all things that go "bump in the night" after his wife Mary was murdered...
            </p>
        </div>
        <div class="tags">
            <span>Tv Series</span>
            <span>Drama</span>
            <span>Fantasy</span>
            <span>Horror</span>
        </div>
        <div class="buttons">
            <button class="play">Play</button>
            <button class="database">Database</button>
        </div>
    </div>
    <div class="image-overlay">
        <img src="photos/Rocky-PNG-Clipart-Background.png" alt="Overlay" />
    </div>
</body>
</html>
