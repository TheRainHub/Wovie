const gallery = document.getElementById('movie-gallery');
        const pagination = document.getElementById('pagination');
        let currentPage = 1;
        let sessionId = null;

        const GENRES_MAP = {
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
            878: 'Sci-Fi',
            10770: 'TV Movie',
            53: 'Thriller',
            10752: 'War',
            37: 'Western'
        };

        /**
         * Creates a movie card element.
         * @param {Object} movie - The movie data.
         * @returns {HTMLElement} - The movie card element.
         */
        function createMovieCard(movie) {
            const genreId = movie.genre_ids[0];
            const genreClass = `genre-${GENRES_MAP[genreId]?.toLowerCase() || 'default'}`;
            const genreName = GENRES_MAP[genreId] || 'Unknown';
            const fallbackPoster = 'images/no-poster.jpg'; // Ensure this image exists in your project
            
            const card = document.createElement('div');
            card.className = 'movie-card';
            card.onclick = () => openMovieDetails(movie.id);
            
            card.innerHTML = `
                <div class="movie-poster-container">
                    <img src="${movie.poster || fallbackPoster}" 
                        alt="${movie.title}"
                        onerror="this.onerror=null; this.src='${fallbackPoster}';"
                        loading="lazy">
                    <span class="genre-label ${genreClass}">${genreName}</span>
                </div>
                <div class="movie-info">
                    <h3 class="movie-title">${movie.title}</h3>
                    <div class="movie-year">${movie.release_date ? movie.release_date.split('-')[0] : 'N/A'}</div>
                    <div class="rating">
                        <span class="stars">${getStars(movie.rating)}</span>
                        <span class="rating-number">${movie.rating}</span>
                    </div>
                </div>
            `;
            return card;
        }

        /**
         * Loads movies for a given page.
         * @param {number} page - The page number to load.
         */
        async function loadMovies(page = 1) {
            try {
                showLoader();
                const response = await fetch(`load_movies.php?page=${page}`);
                const data = await response.json();
                
                if (!data.session_id) {
                    throw new Error('Invalid session');
                }
                
                if (sessionId && sessionId !== data.session_id) {
                    handleNewSession();
                }
                sessionId = data.session_id;
                
                renderMovies(data.movies);
                updatePagination(data.current_page, data.total_pages);
                savePageState(page);
                
            } catch (error) {
                handleError(error);
            }
        }

        /**
         * Renders movies in the gallery.
         * @param {Array} movies - The array of movie objects.
         */
        function renderMovies(movies) {
            const fragment = document.createDocumentFragment();
            movies.forEach(movie => {
                fragment.appendChild(createMovieCard(movie));
            });
            gallery.innerHTML = '';
            gallery.appendChild(fragment);
        }

        /**
         * Generates star ratings based on the movie rating.
         * @param {number} rating - The movie rating.
         * @returns {string} - The star representation.
         */
        function getStars(rating) {
            const fullStars = Math.floor(rating / 2);
            const hasHalfStar = rating % 2 >= 1;
            let stars = '★'.repeat(fullStars);
            stars += hasHalfStar ? '★' : '';
            stars += '☆'.repeat(5 - fullStars - (hasHalfStar ? 1 : 0));
            return stars;
        }

        /**
         * Updates the pagination controls.
         * @param {number} currentPage - The current page number.
         * @param {number} totalPages - The total number of pages.
         */
        function updatePagination(currentPage, totalPages) {
            const paginationButtons = [];
            
            if (currentPage > 1) {
                paginationButtons.push(createPaginationButton(currentPage - 1, '←'));
            }
            
            for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
                paginationButtons.push(createPaginationButton(i, i, i === currentPage));
            }
            
            if (currentPage < totalPages) {
                paginationButtons.push(createPaginationButton(currentPage + 1, '→'));
            }
            
            pagination.innerHTML = paginationButtons.join('');
        }

        /**
         * Creates a pagination button.
         * @param {number} page - The page number.
         * @param {string|number} text - The text to display.
         * @param {boolean} isActive - Whether the button is active.
         * @returns {string} - The HTML string for the button.
         */
        function createPaginationButton(page, text, isActive = false) {
            return `<a href="#" 
                onclick="loadMovies(${page})" 
                class="pagination-btn ${isActive ? 'active' : ''}">${text}</a>`;
        }

        /**
         * Saves the current page state.
         * @param {number} page - The current page number.
         */
        function savePageState(page) {
            currentPage = page;
            sessionStorage.setItem('lastPage', page);
            sessionStorage.setItem(`scrollPosition_${page}`, window.scrollY);
        }

        /**
         * Shows a loading indicator.
         */
        function showLoader() {
            gallery.innerHTML = '<div class="loading">Loading movies...</div>';
        }

        /**
         * Handles errors by displaying an error message.
         * @param {Error} error - The error object.
         */
        function handleError(error) {
            console.error('Error loading movies:', error);
            gallery.innerHTML = '<div class="error">Failed to load movies. Please try again.</div>';
        }

        /**
         * Handles a new session by clearing session storage.
         */
        function handleNewSession() {
            console.log('New session started');
            sessionStorage.clear();
        }

        /**
         * Opens the movie details page.
         * @param {number} movieId - The ID of the movie.
         */
        function openMovieDetails(movieId) {
            savePageState(currentPage);
            window.location.href = `movie_details.php?id=${movieId}`;
        }

        // Load movies on page load
        window.onload = () => {
            const lastPage = parseInt(sessionStorage.getItem('lastPage')) || 1;
            const scrollPosition = parseInt(sessionStorage.getItem(`scrollPosition_${lastPage}`)) || 0;
            
            loadMovies(lastPage).then(() => {
                window.scrollTo(0, scrollPosition);
            });
        };