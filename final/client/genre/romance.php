<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

include_once '../connect.php';

$books = [];  

try {
  
    $sql = "SELECT * FROM books WHERE genre = 'Romance'";
    $stmt = $pdo->query($sql);

  
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>


<?php include '../includes/header.php'; ?>

<main>
    <div class="content-wrapper">
        <section>
            <div class="books-section">
                <div class="books-content">
                    <h2>Romance Books</h2>
                    <?php if (empty($books)): ?>
                        <p>No books available in this genre</p>
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
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</main>

<script src="./js/script.js"></script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
