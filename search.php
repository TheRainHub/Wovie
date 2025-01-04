<?php
require 'data/db_connection.php';

// Initialize pagination variables
$per_page = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($query)) {
    // Get total count
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM movies WHERE title LIKE ?");
    $count_stmt->execute(['%' . $query . '%']);
    $total_results = $count_stmt->fetchColumn();
    
    // Get paginated results
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE title LIKE ? LIMIT ? OFFSET ?");
    $stmt->execute(['%' . $query . '%', $per_page, $offset]);
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $total_pages = ceil($total_results / $per_page);
}

$pageTitle = "Search Results for: " . htmlspecialchars($query);
include 'temples/mainheader.php';
?>
<main class="main-content">
    <div class="search-results-container">
        <?php if (!empty($query)): ?>
            <h1>Search Results for: <?php echo htmlspecialchars($query); ?></h1>
            <p class="results-count">Found <?php echo $total_results; ?> movies</p>
            
            <?php if ($movies): ?>
                <div class="movies-grid">
                    <?php foreach ($movies as $movie): ?>
                        <div class="movie-card">
                            <a href="movie_details.php?id=<?php echo $movie['id']; ?>">
                                <div class="movie-poster-container">
                                    <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>" 
                                        alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                    <?php if ($movie['rating']): ?>
                                        <div class="rating">
                                            <span class="rating-number"><?php echo number_format($movie['rating'], 1); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="movie-info">
                                    <h3 class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                                    <p class="movie-year"><?php echo date('Y', strtotime($movie['release_date'])); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?query=<?php echo urlencode($query); ?>&page=<?php echo $i; ?>" 
                            class="<?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <p class="no-results">No movies found</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
