<?php
include 'connect.php';
session_start();

// Check if admin is logged in

if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_username = $_SESSION['admin_username'];


// set limit to 5 for showing books, user , and orders on starting page

// Fetch the latest 5 books
try {
    $query_books = "SELECT * FROM books LIMIT 5";
    $statement_books = $pdo->query($query_books);
    $books = $statement_books->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $book_error_message = "Error fetching books: " . $e->getMessage();
}


// Fetch the latest 5 users
try {
    $query_users = "SELECT * FROM users LIMIT 5";
    $statement_users = $pdo->query($query_users);
    $users = $statement_users->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $user_error_message = "Error fetching users: " . $e->getMessage();
}

// Fetch the latest 5 orders
try {
    $query_orders = "SELECT o.id, o.total, o.order_date, u.username 
                     FROM orders o 
                     JOIN users u ON o.user_id = u.id 
                     ORDER BY o.order_date DESC 
                     LIMIT 5";
    $statement_orders = $pdo->query($query_orders);
    $orders = $statement_orders->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $order_error_message = "Error fetching orders: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dashboard</title>
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="icon" type="image/png" href="./assets/favicon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <?php include './includes/sidebar.php' ?>
        <div class="content">
            <h2>Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h2>
            <div class="feedback">
                <?php if (isset($book_error_message)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($book_error_message); ?></div>
                <?php endif; ?>
                <?php if (isset($user_error_message)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($user_error_message); ?></div>
                <?php endif; ?>
                <?php if (isset($order_error_message)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($order_error_message); ?></div>
                <?php endif; ?>
            </div>
            <button id="insertMockBooksBtn">Insert Mock Books</button>
            <h1>Admin Dashboard</h1>
            
            <h3>Books</h3>
            <div class="books-container">
                <?php if (isset($books)): ?>
                    <?php if (count($books) > 0): ?>
                        <table>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td><img src="<?php echo htmlspecialchars($book['image_path']); ?>" alt="Book Image" style="width: 50px; height: auto;"></td>
                                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                                    <td><?php echo htmlspecialchars($book['quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($book['price']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p>No books found.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <h3>Users</h3>
            <div class="users-container">
                <?php if (isset($users)): ?>
                    <?php if (count($users) > 0): ?>
                        <table>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                            </tr>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p>No users found.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <h3>Recent Orders</h3>
            <div class="orders-container">
                <?php if (isset($orders)): ?>
                    <?php if (count($orders) > 0): ?>
                        <table>
                            <tr>
                                <th>Order ID</th>
                                <th>User</th>
                                <th>Total</th>
                                <th>Order Date</th>
                            </tr>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                                    <td>$<?php echo number_format($order['total'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p>No orders found.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {
            $('#insertMockBooksBtn').on('click', function() {

// Send an AJAX request to insert mock books
                $.ajax({
                    url: 'insert_books.php',
                    type: 'GET',
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error inserting mock books:', status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>
