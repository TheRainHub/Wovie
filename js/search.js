document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value;

        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                fetch('live_search.php?query=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(movies => {
                        searchResults.innerHTML = movies.map(movie => `
                            <div class="search-result" onclick="window.location.href='movie_details.php?id=${movie.id}'">
                                <img src="${movie.poster_url}" alt="${movie.title}">
                                <div class="movie-info">
                                    <div class="movie-title">${movie.title}</div>
                                    <div class="movie-year">${movie.release_date.split('-')[0]}</div>
                                </div>
                            </div>
                        `).join('');
                        searchResults.style.display = movies.length ? 'block' : 'none';
                    });
            }, 300);
        } else {
            searchResults.style.display = 'none';
        }
    });

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});