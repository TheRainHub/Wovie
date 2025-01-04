<!-- <?php
//temples/pagination.php 
?> 
<nav class="pagination">
        <?php if ($page > 1): ?>
            <a href="home.php?page=<?= $page - 1; ?>" class="pagination-btn">&laquo; Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="home.php?page=<?= $i; ?>" class="pagination-btn <?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="home.php?page=<?= $page + 1; ?>" class="pagination-btn">Next &raquo;</a>
        <?php endif; ?>
    </nav> -->