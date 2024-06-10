<?php
session_start();
include_once 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$total = 0;

// Calculate total
foreach ($_SESSION['cart'] as $item) {
    $total += $item['book_price'] * $item['quantity'];
}

// Process the order when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

// Basic validation  
    if (empty($name) || empty($address) || empty($city) || empty($postal_code) || empty($card_number) || empty($expiry_date) || empty($cvv)) {
        $error_message = "All fields are required.";
    } else {
        try {



 // Start transaction
            $pdo->beginTransaction();

// Insert order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, name, address, city, postal_code, card_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $total, $name, $address, $city, $postal_code, $card_number]);
            $order_id = $pdo->lastInsertId();

// Insert order items and update book quantities
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
            $updateStmt = $pdo->prepare("UPDATE books SET quantity = quantity - ? WHERE id = ? AND quantity >= ?");
            
            foreach ($_SESSION['cart'] as $item) {
                $stmt->execute([$order_id, $item['book_id'], $item['quantity'], $item['book_price']]);
                $updateStmt->execute([$item['quantity'], $item['book_id'], $item['quantity']]);

// Check if the update was successful
                if ($updateStmt->rowCount() === 0) {
                    throw new Exception("Not enough stock for book ID: " . $item['book_id']);
                }
            }

             
            $pdo->commit();

 // Clear 
            unset($_SESSION['cart']);

            header("Location: order_success.php");
            exit;

        } catch (Exception $e) {
            
            $pdo->rollBack();

            die("Checkout failed: " . $e->getMessage());

            
        }
    }
}
?>

<?php include './includes/header.php'; ?>
<main>
    <div class="content-wrapper">
        <section>
            <div class="checkout-section">
                <div class="checkout-content">
                    <h2>Checkout</h2>
                    <?php if (isset($error_message)): ?>
                        <p class="checkout-error"><?= htmlspecialchars($error_message); ?></p>
                    <?php endif; ?>
                    <form id="checkoutForm" method="POST" action="checkout.php" onsubmit="return validateCheckoutForm()">
                        <div class="checkout-info-container">
                            <div class="checkout-form-section">
                                <h3>Delivery Information</h3>
                                <div class="checkout-form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" id="name" name="name" required>
                                    <p id="nameError" class="error-message"></p>
                                </div>
                                <div class="checkout-form-group">
                                    <label for="address">Address:</label>
                                    <input type="text" id="address" name="address" required>
                                    <p id="addressError" class="error-message"></p>
                                </div>
                                <div class="checkout-form-group">
                                    <label for="city">City:</label>
                                    <input type="text" id="city" name="city" required>
                                    <p id="cityError" class="error-message"></p>
                                </div>
                                <div class="checkout-form-group">
                                    <label for="postal_code">Postal Code:</label>
                                    <input type="text" id="postal_code" name="postal_code" required>
                                    <p id="postalCodeError" class="error-message"></p>
                                </div>
                            </div>
                            <div class="checkout-form-section">
                                <h3>Payment Information</h3>
                                <div class="checkout-form-group">
                                    <label for="card_number">Card Number:</label>
                                    <input type="text" id="card_number" name="card_number" required pattern="\d{16}">
                                    <p id="cardNumberError" class="error-message"></p>
                                </div>
                                <div class="checkout-form-group">
                                    <label for="expiry_date">Expiry Date:</label>
                                    <input type="month" id="expiry_date" name="expiry_date" required>
                                    <p id="expiryDateError" class="error-message"></p>
                                </div>
                                <div class="checkout-form-group">
                                    <label for="cvv">CVV:</label>
                                    <input type="text" id="cvv" name="cvv" required pattern="\d{3}">
                                    <p id="cvvError" class="error-message"></p>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-cart-container">
                            <div class="checkout-cart-items">
                                <table class="checkout-cart-table">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($_SESSION['cart'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <a href="book.php?id=<?= $item['book_id'] ?>">
                                                        <img src="../admin/<?= htmlspecialchars($item['book_image']); ?>" alt="<?= htmlspecialchars($item['book_title']); ?>" class="checkout-cart-book-image">
                                                    </a>
                                                </td>
                                                <td><?= htmlspecialchars($item['book_title']); ?></td>
                                                <td>$<?= number_format($item['book_price'], 2); ?></td>
                                                <td><?= htmlspecialchars($item['quantity']); ?></td>
                                                <td>$<?= number_format($item['book_price'] * $item['quantity'], 2); ?></td>
                                            </tr>
                                            <?php $total += $item['book_price'] * $item['quantity']; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="checkout-order-summary">
                                <h3>Order Summary</h3>
                                <p>Estimated Shipping: <span class="checkout-total-price">Free</span></p>
                                <p>Estimated Tax: <span class="checkout-total-price">$0.00</span></p>
                                <hr>
                                <p>Order Total: <span class="checkout-total-price">$<?= number_format($total, 2); ?></span></p>
                                <button type="submit" class="checkout-confirm-button">Confirm Order</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</main>
<?php include './includes/footer.php'; ?>
</body>
</html>
 

<script>
document.addEventListener('DOMContentLoaded', function() {

    const nameInput = document.getElementById('name');
    const addressInput = document.getElementById('address');
    const cityInput = document.getElementById('city');
    const postalCodeInput = document.getElementById('postal_code');
    const cardNumberInput = document.getElementById('card_number');
    const expiryDateInput = document.getElementById('expiry_date');
    const cvvInput = document.getElementById('cvv');

    nameInput.addEventListener('input', validateName);
    addressInput.addEventListener('input', validateAddress);
    cityInput.addEventListener('input', validateCity);
    postalCodeInput.addEventListener('input', validatePostalCode);
    cardNumberInput.addEventListener('input', validateCardNumber);
    expiryDateInput.addEventListener('input', validateExpiryDate);
    cvvInput.addEventListener('input', validateCVV);

    nameInput.addEventListener('blur', validateName);
    addressInput.addEventListener('blur', validateAddress);
    cityInput.addEventListener('blur', validateCity);
    postalCodeInput.addEventListener('blur', validatePostalCode);
    cardNumberInput.addEventListener('blur', validateCardNumber);
    expiryDateInput.addEventListener('blur', validateExpiryDate);
    cvvInput.addEventListener('blur', validateCVV);
});

function validateName() {
    const name = document.getElementById('name').value.trim();
    const nameError = document.getElementById('nameError');
    if (name === '') {
        nameError.textContent = 'Name is required.';
    } else {
        nameError.textContent = '';
    }
}

function validateAddress() {
    const address = document.getElementById('address').value.trim();
    const addressError = document.getElementById('addressError');
    if (address === '') {
        addressError.textContent = 'Address is required.';
    } else {
        addressError.textContent = '';
    }
}

function validateCity() {
    const city = document.getElementById('city').value.trim();
    const cityError = document.getElementById('cityError');
    if (city === '') {
        cityError.textContent = 'City is required.';
    } else {
        cityError.textContent = '';
    }
}

function validatePostalCode() {
    const postalCode = document.getElementById('postal_code').value.trim();
    const postalCodeError = document.getElementById('postalCodeError');
    if (postalCode === '') {
        postalCodeError.textContent = 'Postal code is required.';
    } else {
        postalCodeError.textContent = '';
    }
}

function validateCardNumber() {
    const cardNumber = document.getElementById('card_number').value.trim();
    const cardNumberError = document.getElementById('cardNumberError');
    const cardNumberPattern = /^\d{16}$/;
    if (!cardNumberPattern.test(cardNumber)) {
        cardNumberError.textContent = 'Invalid card number. Please enter a 16-digit card number.';
    } else {
        cardNumberError.textContent = '';
    }
}

function validateExpiryDate() {
    const expiryDate = document.getElementById('expiry_date').value.trim();
    const expiryDateError = document.getElementById('expiryDateError');
    const expiryDatePattern = /^\d{4}-\d{2}$/;
    if (!expiryDatePattern.test(expiryDate)) {
        expiryDateError.textContent = 'Invalid expiry date. Please enter a valid expiry date.';
    } else {
        expiryDateError.textContent = '';
    }
}

function validateCVV() {
    const cvv = document.getElementById('cvv').value.trim();
    const cvvError = document.getElementById('cvvError');
    const cvvPattern = /^\d{3}$/;
    if (!cvvPattern.test(cvv)) {
        cvvError.textContent = 'Invalid CVV. Please enter a 3-digit CVV.';
    } else {
        cvvError.textContent = '';
    }
}

function validateCheckoutForm() {
    validateName();
    validateAddress();
    validateCity();
    validatePostalCode();
    validateCardNumber();
    validateExpiryDate();
    validateCVV();

    const errors = document.querySelectorAll('.error-message');
    let isValid = true;
    errors.forEach(error => {
        if (error.textContent !== '') {
            isValid = false;
        }
    });

    return isValid;
}
</script>


<?php include './includes/footer.php'; ?>

</body>
</html>
