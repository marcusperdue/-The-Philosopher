<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

include_once './connect.php';

//  Search db for all books 

$books = [];
$search = $_GET['search'] ?? '';  

try {
    if (!empty($search)) {
        $sql = "SELECT * FROM books WHERE title LIKE :search OR author LIKE :search";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':search', '%' . $search . '%');
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
}

include './includes/header.php';
?>

<main>
    <div class="content-wrapper">
        <section>
            <div class="books-section">
                <div class="books-content">
                    <?php if (!empty($search)): ?>
                        <h2>Search Results for "<?php echo htmlspecialchars($search); ?>"</h2>
                    <?php else: ?>
                        <h2>Search Results</h2>
                    <?php endif; ?>
                    <?php if (!empty($error_message)): ?>
                        <p><?= $error_message ?></p>
                    <?php elseif (empty($books)): ?>
                        <p>No books found matching your search criteria.</p>
                    <?php else: ?>
                        <div class="books-grid">
                            <?php foreach ($books as $book): ?>
                                <a href="book.php?id=<?= $book['id'] ?>" class="book-link">
                                    <div class="book">
                                        <img src="../admin/<?= htmlspecialchars($book['image_path']); ?>" alt="<?= htmlspecialchars($book['title']); ?>" style="width: 100%; height: auto;">
                                        <h3><?= htmlspecialchars($book['title']); ?></h3>
                                        <p>By <?= htmlspecialchars($book['author']); ?></p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
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
