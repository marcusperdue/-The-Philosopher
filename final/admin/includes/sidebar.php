<div class="sidebar">
        <img src="./assests/black-logo.svg" alt="Logo" class="logo">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="index.php">Home</a></li>  
            <li><a href="books.php">Books</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
        </ul>
        <div class="admin-info">
            <div class="admin-name"><?php echo $admin_username; ?></div>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>