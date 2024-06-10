<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

include_once './connect.php';



// Check if book ID is provided in the URL
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    
    try {

// Fetch book details from the database
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
    
 // Redirect if the book is not found
        if (!$book) {
            header("Location: index.php");
            exit;
        }

// Check if the book is in the user's favorites
        $fav_stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ? AND book_id = ?");
        $fav_stmt->execute([$user_id, $book_id]);
        $is_favorite = $fav_stmt->rowCount() > 0;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
} else {

// Redirect if no book ID is not there
    header("Location: index.php");
    exit;
}
?>

<?php include './includes/header.php'; ?>
<main>
    <div class="content-wrapper">
        <section>
            <div class="book-section">
                <div class="book-content">
                    <div class="top-section">
                        <div class="left-column">
                            <img src="../admin/<?= htmlspecialchars($book['image_path']); ?>" alt="Book Image" class="book-image">
                            <div class="favorites">
                                <button class="favorite-button <?= $is_favorite ? 'added' : '' ?>" onclick="toggleFavorite(<?= $book['id'] ?>)">
                                    <i class="far fa-heart favorite-icon <?= $is_favorite ? 'added' : '' ?>"></i>
                                    <span class="button-text"><?= $is_favorite ? 'Added to Favorites' : 'Add to Favorites' ?></span>
                                </button>
                            </div>
                        </div>
                        <div class="right-column">
                            <div class="book-details">
                                <div class="top-book-details">
                                    <h2><?= htmlspecialchars($book['title']); ?></h2>
                                    <p>by <?= htmlspecialchars($book['author']); ?></p>
                                    <div class="ratings-reviews">
                                        <div class="stars">
                                            <!-- star icons-->
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="reviews">
                                            <!--  number for reviews -->
                                            <span>123 reviews</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout-info">
                                    <p>
                                        <div class="price-label">Price:</div> 
                                        <span class="price-value">$<?= htmlspecialchars($book['price']); ?></span>
                                    </p>
                                    <p>
                                        <div class="quantity-label">Quantity Left:</div> 
                                        <span class="quantity-value"><?= htmlspecialchars($book['quantity']); ?></span>
                                    </p>

                                    <form method="POST" action="cart.php">
                                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                        <input type="hidden" name="book_title" value="<?= htmlspecialchars($book['title']); ?>">
                                        <input type="hidden" name="book_price" value="<?= htmlspecialchars($book['price']); ?>">
                                        <input type="hidden" name="book_image" value="<?= htmlspecialchars($book['image_path']); ?>">
                                        <label for="quantity">Quantity:</label>
                                        <input type="number" id="quantity" name="quantity" value="1" min="1" <?= $book['quantity'] <= 0 ? 'disabled' : '' ?>>
                                        <button type="submit" name="add_to_cart" <?= $book['quantity'] <= 0 ? 'disabled' : '' ?>>Add to Cart</button>
                                    </form>
                                    <?php if ($book['quantity'] <= 0): ?>
                                        <p class="out-of-stock-message">This book is currently out of stock.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="description-section">
                        <div class="description">
                            <h3>Description:</h3>
                            <p class="description-text"><?= htmlspecialchars($book['description']); ?></p>
                        </div>
                    </div>
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
                const button = document.querySelector('.favorite-button');
                const icon = button.querySelector('.favorite-icon');
                button.classList.toggle('added', response.is_favorite);
                icon.classList.toggle('added', response.is_favorite);
                const buttonText = button.querySelector('.button-text');
                buttonText.textContent = response.is_favorite ? 'Added to Favorites' : 'Add to Favorites';
            } else {
                alert(response.message);
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
