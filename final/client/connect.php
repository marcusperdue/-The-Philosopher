<?php
$host = 'localhost';  
$username = 'mperdue_admin';  
$password = 'Z@dmin99';  
$database = 'mperdue_133p';  


// Function to create the books table
function createBooksTable($pdo) {
    $query = "CREATE TABLE IF NOT EXISTS books (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                author VARCHAR(255) NOT NULL,
                description TEXT,
                quantity INT(11) NOT NULL DEFAULT 0,
                price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
                genre VARCHAR(255) NOT NULL,
                image_path VARCHAR(255) NOT NULL
              )";
    $pdo->exec($query);
}
// Function to create the users table
function createUsersTable($pdo) {
    $query = "CREATE TABLE IF NOT EXISTS users (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                username VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL
              )";
    $pdo->exec($query);
}

// Function to create the favorites table
function createFavoritesTable($pdo) {
    $query = "CREATE TABLE IF NOT EXISTS favorites (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                book_id INT(11) NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (book_id) REFERENCES books(id)
              )";
    $pdo->exec($query);
}

// Function to create the cart table
function createCartTable($pdo) {
    $query = "CREATE TABLE IF NOT EXISTS cart (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                book_id INT(11) NOT NULL,
                quantity INT(11) NOT NULL DEFAULT 1,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (book_id) REFERENCES books(id)
              )";
    $pdo->exec($query);
}

// Function to create the orders table with delivery and payment information
function createOrdersTable($pdo) {
    $query = "CREATE TABLE IF NOT EXISTS orders (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                total DECIMAL(10, 2) NOT NULL,
                order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                name VARCHAR(255) NOT NULL,
                address VARCHAR(255) NOT NULL,
                city VARCHAR(255) NOT NULL,
                postal_code VARCHAR(50) NOT NULL,
                card_number VARCHAR(255) NOT NULL,
                shipped TINYINT(1) DEFAULT 0,
                FOREIGN KEY (user_id) REFERENCES users(id)
              )";
    $pdo->exec($query);
}


// Function to create the order_items table
function createOrderItemsTable($pdo) {
    $query = "CREATE TABLE IF NOT EXISTS order_items (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                order_id INT(11) NOT NULL,
                book_id INT(11) NOT NULL,
                quantity INT(11) NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(id),
                FOREIGN KEY (book_id) REFERENCES books(id)
              )";
    $pdo->exec($query);
}

try {

// Database connection
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables



    createUsersTable($pdo);
    createFavoritesTable($pdo);
    createCartTable($pdo);
    createOrdersTable($pdo);
    createOrderItemsTable($pdo);




    // echo "Tables created successfully.";

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
