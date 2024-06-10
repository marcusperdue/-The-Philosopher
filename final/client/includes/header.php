<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : "";
$error_message = "";
$welcome_message = $first_name ? "Hi, " . $first_name : "Login";

// cart session items
$cart_item_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Philosopher Bookstore</title>
    
    <?php

// Check if running on localhost
        if ($_SERVER['HTTP_HOST'] === 'localhost') {
            $base_url = 'http://localhost/233p/final/client/';
        } else {
            $base_url = 'https://mperdue.cocc-cis.com/233p/final/client/';
        }
    ?>
    
    <base href="<?php echo $base_url; ?>" />
    <link rel="stylesheet" href="./css/style.css">  
    <link rel="stylesheet" href="./css/media.css">  
    <link rel="icon" type="image/png" href="./assets/images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- canvas-confetti library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.min.js"></script>
    <style>
    .cart-icon-container {
            position: relative;
        }

        .cart-item-count {
            display: <?php echo ($cart_item_count > 0) ? 'block' : 'none'; ?>;
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 12px;
        }
        </style>
    
</head>
<body>

<header>
    <div class="top-header">
        <div class="top-container">  
            <a href="index.php">
                <img src="./assets/images/logo.svg" alt="The Philosopher Bookstore" class="logo">
            </a>

            <div class="search-bar">
                <form action="search_books.php" method="GET" style="display: flex; width: 100%;">
                    <input type="text" name="search" placeholder="Search for books..." style="flex-grow: 1;">
                    <button type="submit" class="search-button">
                        <img src="./assets/images/search.svg" alt="Search" class="search-icon">
                    </button>
                </form>
            </div>

            <div class="user-interactions">

            <div class="icon-button cart-icon-container">
                    <a href="./cart.php">
                        <img src="./assets/images/cart.svg" alt="Cart" class="cart-icon">
                    </a>
                    <span class="cart-item-count"><?php echo $cart_item_count; ?></span>
                </div>
                <a href="./favorite.php" class="icon-button">
                    <img src="./assets/images/heart.svg" alt="Wishlist" class="wishlist-icon">
                </a>

                <?php if ($first_name) : ?>
               
                <a href="./user.php" class="user-link"> 

                <div class="icon-button user-button">
                <img src="./assets/images/user.svg" alt="User" class="user-icon">

                <?php echo $welcome_message; ?>

                 </a>
                </div>


                    <a href="?logout" class="logout-button">Logout</a>
                <?php else : ?>
                    <div class="icon-button ">
                    <button class="mobile-login-button" onclick="redirectToLoginPage()">
                        <img src="./assets/images/user.svg" alt="User" class="user-icon">
                        Login/Sign-Up
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bottom-header">
        <nav>
            <div class="bottom-container">  
                <ul>
                    <li><a href="./all_books.php">All Books</a></li>
                    <li><a href="../client/genre/fiction.php">Fiction</a></li>
                    <li><a href="../client/genre/mystery.php">Mystery</a></li>
                    <li><a href="../client/genre/thriller.php">Thriller</a></li>
                    <li><a href="../client/genre/non-fiction.php">Non-Fiction</a></li>
                    <li><a href="../client/genre/horror.php">Horror</a></li>
                    <li><a href="../client/genre/fantasy.php">Fantasy</a></li>
                    <li><a href="../client/genre/biography.php">Biography</a></li>
                    <li><a href="../client/genre/history.php">History</a></li>
                    <li><a href="../client/genre/romance.php">Romance</a></li>
                    <li><a href="../client/genre/poetry.php">Poetry</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="mobile-header">
        <div class="top-container">
            <button class="nav-toggle">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </button>
            <a href="index.php">
                <img src="./assets/images/logo.svg" alt="The Philosopher Bookstore" class="logo">
            </a>
            <div class="user-interactions">
            <a href="./cart.php" class="icon-button">
                    <img src="./assets/images/cart.svg" alt="Cart" class="cart-icon">
                </a>
                <a href="./favorite.php" class="icon-button">
                    <img src="./assets/images/heart.svg" alt="Wishlist" class="wishlist-icon">
                </a>
            </div>
        </div>
    </div>

    <div class="mobile-bottom-header">
        <div  class="mobile-bottom-container"> 
            <div class="search-bar">
                <form action="search_books.php" method="GET" style="display: flex; width: 100%;">
                    <input type="text" name="search" placeholder="Search for books..." style="flex-grow: 1;">
                    <button type="submit" class="search-button">
                        <img src="./assets/images/search.svg" alt="Search" class="search-icon">
                    </button>
                </form>
            </div>
        </div>
    </div>

    <nav class="mobile-nav">
        <div class="nav-container">
            <a href="index.php">
                <img src="./assets/images/black-logo.svg" alt="The Philosopher Bookstore" class="logo">
            </a>
            <?php if ($first_name) : ?>
                <div class="icon-button mobile-login-button">
                <a href="./user.php" class="user-link"> 
                    <img src="./assets/images/user.svg" alt="User" class="user-icon">
                    <?php echo $welcome_message; ?>
                     </a>
                </div>
                <a href="?logout" class="logout-button mobile-logout">Logout</a>
            <?php else : ?>
                <div class="icon-button ">
                    <button class="mobile-login-button" onclick="redirectToLoginPage()">
                        <img src="./assets/images/user.svg" alt="User" class="user-icon">
                        Login/Sign-Up
                    </button>
                </div>
            <?php endif; ?>
            <ul>
                <li><a href="./all_books.php">All Books</a></li>
                <li><a href="../client/genre/fiction.php">Fiction</a></li>
                <li><a href="../client/genre/mystery.php">Mystery</a></li>
                <li><a href="../client/genre/thriller.php">Thriller</a></li>
                <li><a href="../client/genre/non-fiction.php">Non-Fiction</a></li>
                <li><a href="../client/genre/horror.php">Horror</a></li>
                <li><a href="../client/genre/fantasy.php">Fantasy</a></li>
                <li><a href="../client/genre/biography.php">Biography</a></li>
                <li><a href="../client/genre/history.php">History</a></li>
                <li><a href="../client/genre/romance.php">Romance</a></li>
                <li><a href="../client/genre/poetry.php">Poetry</a></li>
            </ul>
            

            <div class="contact-info">
                <p>Email: contact@philosopherbooks.com</p>
                <p>Phone: (123) 456-7890</p>
                <p>Follow Us</p>
                <div class="social-icons">
                    <a href="#"><img src="./assets/images/social/facebook-black.svg" alt="Facebook" class="social-icon"></a> 
                    <a href="#"><img src="./assets/images/social/x-black.svg" alt="X" class="social-icon"></a> 
                    <a href="#"><img src="./assets/images/social/instagram-black.svg" alt="Instagram" class="social-icon"></a>
                </div>
            </div>
        </div>
        <button class="close-btn">
            <img src="./assets/images/close.svg" alt="Close" class="close-icon">
        </button>
    </nav>
</header>
