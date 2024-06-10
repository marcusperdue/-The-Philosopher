<?php
include 'connect.php';

$order_id = $_GET['order_id'];

$query_order_details = "SELECT order_items.book_id, order_items.quantity, order_items.price, books.title 
                        FROM order_items 
                        JOIN books ON order_items.book_id = books.id 
                        WHERE order_items.order_id = ?";
$statement_order_details = $pdo->prepare($query_order_details);
$statement_order_details->execute([$order_id]);

$order_items = $statement_order_details->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
    <thead>
        <tr>
            <th>Book Title</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($order_items) > 0): ?>
            <?php foreach ($order_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['title']); ?></td>
                    <td><?= htmlspecialchars($item['quantity']); ?></td>
                    <td>$<?= number_format($item['price'], 2); ?></td>
                    <td>$<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No items found for this order.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
