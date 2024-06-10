<?php
session_start();


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['clear_cart'])) {
        unset($_SESSION['cart']);
        header('Location: cart.php');
        exit;
    }


// Update item quantity
    if (isset($_POST['update_quantity'])) {
        $book_id = $_POST['book_id'];
        $quantity = $_POST['quantity'];


// Find the item in the cart and update its quantity
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['book_id'] == $book_id) {
                $item['quantity'] = $quantity;
                break;
            }
        }

        header('Location: cart.php');
        exit;
    }

 // Remove an item from the cart
    if (isset($_POST['remove_item'])) {
        $book_id = $_POST['book_id'];

        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['book_id'] == $book_id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }

        header('Location: cart.php');
        exit;
    }

    // Add item to cart
    if (isset($_POST['book_id']) && isset($_POST['quantity'])) {
        $book_id = $_POST['book_id'];
        $book_title = $_POST['book_title'];
        $book_price = $_POST['book_price'];
        $book_image = $_POST['book_image'];
        $quantity = (int)$_POST['quantity'];

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

// Check if the item is already in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['book_id'] == $book_id) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

// Add the item to the cart if it wasn't found
        if (!$found) {
            $_SESSION['cart'][] = [
                'book_id' => $book_id,
                'book_title' => $book_title,
                'book_price' => $book_price,
                'book_image' => $book_image,
                'quantity' => $quantity
            ];
        }

        header('Location: cart.php');
        exit;
    }
}

$total = 0;
?>

<?php include './includes/header.php'; ?>
<main>
    <div class="content-wrapper">
        <section>
            <div class="cart-section">
                <div class="cart-content">
                    <h2>Your Shopping Cart</h2>
                    <?php if (empty($_SESSION['cart'])): ?>
                        <p>Your cart is empty.</p>
                    <?php else: ?>
                        <form method="POST" action="cart.php" style="margin-bottom: 20px;">
                            <button type="submit" name="clear_cart" class="clear-cart-button">Clear Cart</button>
                        </form>
                        <div class="cart-container">
                            <div class="cart-items">
                                <table class="cart-table">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($_SESSION['cart'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <a href="book.php?id=<?= $item['book_id'] ?>">
                                                        <img src="../admin/<?= htmlspecialchars($item['book_image']); ?>" alt="<?= htmlspecialchars($item['book_title']); ?>" class="cart-book-image">
                                                    </a>
                                                </td>
                                                <td><?= htmlspecialchars($item['book_title']); ?></td>
                                                <td>$<?= number_format($item['book_price'], 2); ?></td>
                                                <td>
                                                    <form method="POST" action="cart.php" class="update-form">
                                                        <input type="hidden" name="book_id" value="<?= $item['book_id']; ?>">
                                                        <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="1" class="quantity-input">
                                                        <button type="submit" name="update_quantity" class="update-button">Update</button>
                                                    </form>
                                                </td>
                                                <td>$<?= number_format($item['book_price'] * $item['quantity'], 2); ?></td>
                                                <td>
                                                    <form method="POST" action="cart.php" class="remove-form">
                                                        <input type="hidden" name="book_id" value="<?= $item['book_id']; ?>">
                                                        <button type="submit" name="remove_item" class="remove-button">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php $total += $item['book_price'] * $item['quantity']; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="order-summary">
                                <h3>Order Summary</h3>
                                <p>Estimated Shipping: <span class="total-price">Free</span></p>
                                <p>Estimated Tax: <span class="total-price">$0.00</span></p>
                                <hr>
                                <p>Order Total: <span class="total-price">$<?= number_format($total, 2); ?></span></p>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <form method="GET" action="checkout.php">
                                        <button type="submit" class="checkout-button">Checkout</button>
                                    </form>
                                <?php else: ?>
                                    <button type="button" class="checkout-button" style="background-color: grey;">You need to be signed in to checkout</button>
                                <?php endif; ?>
                            </div>
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
