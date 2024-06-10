<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_username = $_SESSION['admin_username'];

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Fetch orders
$query_orders = "SELECT orders.id, orders.total, orders.order_date, users.username, orders.name, orders.address, orders.city, orders.postal_code, orders.shipped 
                 FROM orders 
                 JOIN users ON orders.user_id = users.id
                 ORDER BY orders.order_date DESC";
$statement_orders = $pdo->prepare($query_orders);
$statement_orders->execute();

$orders = $statement_orders->fetchAll(PDO::FETCH_ASSOC);

// Handle marking as shipped
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_shipped'])) {
    $order_id = $_POST['order_id'];
    $stmt = $pdo->prepare("UPDATE orders SET shipped = 1 WHERE id = ?");
    $stmt->execute([$order_id]);
    header("Location: orders.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Orders</title>
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="./css/orders.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        
        #orderDetailsModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <?php include './includes/sidebar.php'; ?>
    <div class="content">
        <h3>Orders</h3>
        <div class="orders-container">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Order Date</th>
                        <th>Delivery Info</th>
                        <th>Shipped</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($orders) > 0): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr id="order-row-<?= htmlspecialchars($order['id']); ?>">
                                <td><?= htmlspecialchars($order['id']); ?></td>
                                <td><?= htmlspecialchars($order['username']); ?></td>
                                <td>$<?= number_format($order['total'], 2); ?></td>
                                <td><?= htmlspecialchars($order['order_date']); ?></td>
                                <td>
                                    <?= htmlspecialchars($order['name']); ?><br>
                                    <?= htmlspecialchars($order['address']); ?><br>
                                    <?= htmlspecialchars($order['city']); ?>, <?= htmlspecialchars($order['postal_code']); ?>
                                </td>
                                <td><?= $order['shipped'] ? 'Yes' : 'No'; ?></td>
                                <td>
                                    <button class="view-order-btn" onclick="viewOrderDetails(<?= $order['id']; ?>)">View Details</button>
                                    <?php if (!$order['shipped']): ?>
                                        <form method="POST" action="orders.php" style="display:inline;">
                                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                            <button type="submit" name="mark_shipped">Mark as Shipped</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div id="orderDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeOrderDetailsModal()">&times;</span>
        <h3>Order Details</h3>
        <div id="order-details-content"></div>
    </div>
</div>

<script>
    const orderDetailsModal = document.getElementById('orderDetailsModal');

    function closeOrderDetailsModal() {
        orderDetailsModal.style.display = "none";
    }

    function viewOrderDetails(orderId) {

 // Send an AJAX request to get orders
        $.ajax({
            url: 'fetch_order_details.php',
            type: 'GET',
            data: { order_id: orderId },
            success: function(response) {
                $('#order-details-content').html(response);
                orderDetailsModal.style.display = "block";
            },
            error: function(xhr, status, error) {
                console.error('Error fetching order details: ', status, error);
            }
        });
    }

    window.onclick = function(event) {
        if (event.target == orderDetailsModal) {
            closeOrderDetailsModal();
        }
    }
</script>
</body>
</html>
