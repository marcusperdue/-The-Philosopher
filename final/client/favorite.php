<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once './connect.php';




// Handle POST request to add favorite
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];

    try {
        
        $stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ? AND book_id = ?");
        $stmt->execute([$user_id, $book_id]);

        if ($stmt->rowCount() > 0) {
    
            $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND book_id = ?");
            $stmt->execute([$user_id, $book_id]);
            echo json_encode(['success' => true, 'message' => 'Book removed from favorites.', 'is_favorite' => false]);
        } else {
           
            $stmt = $pdo->prepare("INSERT INTO favorites (user_id, book_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $book_id]);
            echo json_encode(['success' => true, 'message' => 'Book added to favorites.', 'is_favorite' => true]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Retrieve user's fav books
$favorite_books = [];
$error_message = "";

try {

// 
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT books.id, books.title, books.author, books.image_path FROM favorites
            INNER JOIN books ON favorites.book_id = books.id
            WHERE favorites.user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $favorite_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  
    $error_message = "Error retrieving favorite books: " . $e->getMessage();
}

 
$current_page = basename($_SERVER['PHP_SELF']);
?>

<?php include './includes/header.php'; ?>
<main>
    <div class="content-wrapper">
        <section>
            <div class="favorite-section">
                <div class="favorite-content">
                    <h2>Your Favorite Books</h2>
                    <div id="message-container"></div>
                    <?php if (!empty($error_message)): ?>
                        <p class="favorite-message error"><?= htmlspecialchars($error_message) ?></p>
                    <?php elseif (empty($favorite_books)): ?>
                        <p class="favorite-message error">No favorite books found</p>
                    <?php else: ?>
                        <div class="books-grid">
                            <?php foreach ($favorite_books as $book): ?>
                                <a href="book.php?id=<?= $book['id'] ?>" class="book-link">
                                    <div class="book">
                                        <img src="../admin/<?= htmlspecialchars($book['image_path']); ?>" alt="Book Image" style="width: 100%; height: auto;">
                                        <h3><?= htmlspecialchars($book['title']); ?></h3>
                                        <p><?= htmlspecialchars($book['author']); ?></p>
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

<script>

// Function to toggle the favorite status of a book

function toggleFavorite(bookId) {
    const xhr = new XMLHttpRequest();


    xhr.open('POST', 'favorite.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                 
                location.reload();  
            }
        }
    };
    xhr.send('book_id=' + bookId);
}
</script>

<script src="./js/script.js"></script>
<?php include './includes/footer.php'; ?>
</body>
</html>
