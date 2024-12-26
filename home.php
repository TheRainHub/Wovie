<?php
require 'data/db_connection.php'; // Подключение к базе данных
include 'temples/header.php'; // Хедер с общей навигацией
//home.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Gallery</title>
    <link rel="stylesheet" href="css/filmstyle.css">
    <script src="js/scripts.js" defer></script>
</head>
<body class="home-page">
    <main id="movie-gallery" class="movie-gallery"></main>
    <div class="pagination-container">
        <div id="pagination" class="pagination"></div>
    </div>

    <script>
        const gallery = document.getElementById('movie-gallery');
        const pagination = document.getElementById('pagination');
        let currentPage = 1;
        let sessionId = null;

        const genresMap = {
            28: 'Action',
            12: 'Adventure',
            16: 'Animation',
            35: 'Comedy',
            80: 'Crime',
            99: 'Documentary',
            18: 'Drama',
            10751: 'Family',
            14: 'Fantasy',
            36: 'History',
            27: 'Horror',
            10402: 'Music',
            9648: 'Mystery',
            10749: 'Romance',
            878: 'Science Fiction',
            10770: 'TV Movie',
            53: 'Thriller',
            10752: 'War',
            37: 'Western',
        };

        async function loadMovies(page = 1) {
                    try {
                        gallery.innerHTML = '<div class="loading">Loading...</div>';
                        const response = await fetch(`load_movies.php?page=${page}`);
                        const data = await response.json();
                        
                        // session changes checkout
                        if (sessionId && sessionId !== data.session_id) {
                            console.log('Новая сессия началась');
                        }
                        sessionId = data.session_id;
                        
                        gallery.innerHTML = '';
                        
                        data.movies.forEach(movie => {
                            const genres = movie.genre_ids
                                .map(genreId => genresMap[genreId] || 'Undefine')
                                .join(', ');
                            
                            gallery.innerHTML += `
                                <div class="movie-card" onclick="openMovieDetails(${movie.id})">
                                    <div class="movie-poster">
                                        <img src="${movie.poster}" alt="${movie.title}">
                                        <span class="genre-label">${genres}</span>
                                    </div>
                                    <div class="details">
                                        <h3>${movie.title}</h3>
                                        <div class="rating">
                                            <span class="stars">${getStars(movie.rating)}</span>
                                            <span class="rating-number">${movie.rating}</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        updatePagination(data.current_page, data.total_pages);
                        currentPage = page;

                        sessionStorage.setItem('scrollPosition_' + page, window.screenY);
                        
                    } catch (error) {
                        console.error('Error while loading film:', error);
                        gallery.innerHTML = '<div class="error">Error film does not load</div>';
                    }
                }

                function updatePagination(currentPage, totalPages) {
                    let paginationHTML = '';
                    
                    // Prev button
                    if (currentPage > 1) {
                        paginationHTML += `<a href="#" onclick="loadMovies(${currentPage - 1})" class="pagination-btn">←</a>`;
                    }

                    // Page numbers
                    for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
                        paginationHTML += `<a href="#" onclick="loadMovies(${i})" class="pagination-btn ${i === currentPage ? 'active' : ''}">${i}</a>`;
                    }

                    // Next button
                    if (currentPage < totalPages) {
                        paginationHTML += `<a href="#" onclick="loadMovies(${currentPage + 1})" class="pagination-btn">→</a>`;
                    }

                    pagination.innerHTML = paginationHTML;
                }

                function getStars(rating) {
                    const fullStars = Math.floor(rating / 2);
                    const hasHalfStar = rating % 2 >= 1;
                    let stars = '★'.repeat(fullStars);
                    if (hasHalfStar) stars += '';
                    stars += '☆'.repeat(5 - fullStars - (hasHalfStar ? 1 : 0));
                    return stars;
                }

                function openMovieDetails(movieId) {
                    sessionStorage.setItem('lastPage', currentPage);
                    window.location.href = `movie_details.php?id=${movieId}`;
                }

                // Initial load
                window.onload = function() {
                    const lastPage = sessionStorage.getItem('lastPage');
                    if (lastPage) {
                        loadMovies(parseInt(lastPage));
                        // Восстанавливаем позицию скролла
                        const scrollPosition = sessionStorage.getItem('scrollPosition_' + lastPage);
                        if (scrollPosition) {
                            window.scrollTo(0, parseInt(scrollPosition));
                        }
                    } else {
                        loadMovies(1);
                    }
                };
    </script>
</body>
</html>
