<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once './connect.php';

// Initialize variables
$user_info = [];
$transactions = [];
$error_message = "";
$success_message = "";

// Generate CSRF token if needed
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

try {

    // Fetch user information from the database
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch recent transactions from the database
    $sql = "SELECT o.id, o.total, o.order_date, oi.book_id, b.title, oi.quantity, oi.price 
            FROM orders o 
            JOIN order_items oi ON o.id = oi.order_id 
            JOIN books b ON oi.book_id = b.id 
            WHERE o.user_id = :user_id 
            ORDER BY o.order_date DESC 
            LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

// Handle database errors
    $error_message = "Error retrieving information: " . $e->getMessage();
}
?>

<?php include './includes/header.php'; ?>
<main>
    <div class="content-wrapper">
        <section>
            <div class="user-section">
                <div class="user-content">
                    <h2>Your User Profile</h2>
                    <div id="message-container"></div>
                    <?php if (!empty($error_message)): ?>
                        <p class="user-message error"><?= htmlspecialchars($error_message) ?></p>
                    <?php elseif (empty($user_info)): ?>
                        <p class="user-message error">No user found</p>
                    <?php else: ?>
                        <form id="user-form" class="user-form">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                            <div class="user-input-group">
                                <label for="first-name">First Name:</label>
                                <input type="text" id="first-name" name="first_name" value="<?= htmlspecialchars($user_info['first_name']); ?>" required>
                            </div>
                            <div class="user-input-group">
                                <label for="last-name">Last Name:</label>
                                <input type="text" id="last-name" name="last_name" value="<?= htmlspecialchars($user_info['last_name']); ?>" required>
                            </div>
                            <div class="user-input-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user_info['email']); ?>" required>
                            </div>
                            <div class="user-input-group">
                                <label for="password">New Password (leave blank if not changing):</label>
                                <input type="password" id="password" name="password">
                            </div>
                            <div class="user-input-group">
                                <label for="confirm-password">Confirm New Password:</label>
                                <input type="password" id="confirm-password" name="confirm_password">
                            </div>
                            <button type="submit" class="user-submit-button">Update Profile</button>
                        </form>
                    <?php endif; ?>

                    <div class="transactions-section">
                        <h2>Recent Transactions</h2>
                        <?php if (!empty($transactions)): ?>
                            <table class="transactions-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Book Title</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($transaction['id']); ?></td>
                                            <td><?= htmlspecialchars($transaction['order_date']); ?></td>
                                            <td><?= htmlspecialchars($transaction['title']); ?></td>
                                            <td><?= htmlspecialchars($transaction['quantity']); ?></td>
                                            <td>$<?= number_format($transaction['price'], 2); ?></td>
                                            <td>$<?= number_format($transaction['total'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No recent transactions found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Password submission 
    const userForm = document.getElementById('user-form');
    userForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        if (password !== confirmPassword) {
            document.getElementById('message-container').innerHTML = '<p class="user-message error">Passwords do not match.</p>';
            return;
        }

        const formData = new FormData(userForm);

// Send a POST request to update_user.php
        fetch('update_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const messageContainer = document.getElementById('message-container');
            if (data.success) {
                messageContainer.innerHTML = `<p class="user-message success">${data.message}</p>`;
                setTimeout(() => {
                    messageContainer.innerHTML = '';
                }, 3000);
            } else {
                messageContainer.innerHTML = `<p class="user-message error">${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('message-container').innerHTML = '<p class="user-message error">An error occurred. Please try again.</p>';
        });
    });
});
</script>
<script src="./js/script.js"></script>
<?php include './includes/footer.php'; ?>
</body>
</html>
