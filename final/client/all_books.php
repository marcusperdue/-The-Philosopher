<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

include_once './connect.php';

$books = [];
$books_per_page = 20;  
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $books_per_page; 

try {
    $sql = "SELECT * FROM books LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $books_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of books
    $total_books_sql = "SELECT COUNT(*) FROM books";
    $total_books_stmt = $pdo->query($total_books_sql);
    $total_books = $total_books_stmt->fetchColumn();
    $total_pages = ceil($total_books / $books_per_page);
} catch (PDOException $e) {
    if ($e->getCode() === '42S02') {
        $error_message = "No books available";
    } else {
        $error_message = "Error: " . $e->getMessage();
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<?php include './includes/header.php'; ?>

<main>
    <div class="content-wrapper">
        <section>
            <div class="books-section">
                <div class="books-content">
                    <h2>All Books</h2>
                    <!-- Pagination -->
                    <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="<?= $current_page ?>?page=<?= $page - 1 ?>" class="pagination-link">&laquo; Previous</a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="<?= $current_page ?>?page=<?= $i ?>" class="pagination-link <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="<?= $current_page ?>?page=<?= $page + 1 ?>" class="pagination-link">Next &raquo;</a>
                            <?php endif; ?>
                        </div>
                    <?php if (!empty($error_message)): ?>
                        <p><?= $error_message ?></p>
                    <?php elseif (empty($books)): ?>
                        <p>No books available</p>
                    <?php else: ?>
                        <div class="books-grid">
                            <?php foreach ($books as $book): ?>
                                <a href="book.php?id=<?= $book['id'] ?>" class="book-link">
                                    <div class="book">
                                        <img src="../admin/<?= htmlspecialchars($book['image_path']); ?>" alt="Book Image" style="width: 100%; height: auto;">
                                        <h3><?= htmlspecialchars($book['title']); ?></h3>
                                        <p>By <?= htmlspecialchars($book['author']); ?></p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>

 <!-- Pagination -->
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="<?= $current_page ?>?page=<?= $page - 1 ?>" class="pagination-link">&laquo; Previous</a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="<?= $current_page ?>?page=<?= $i ?>" class="pagination-link <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="<?= $current_page ?>?page=<?= $page + 1 ?>" class="pagination-link">Next &raquo;</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</main>

<script src="./js/script.js"></script>
<?php include './includes/footer.php'; ?>
</body>
</html>
