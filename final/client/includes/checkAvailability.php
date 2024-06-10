<?php
session_start();
include 'connect.php';

 
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Check if username exists
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        if ($stmt->execute([$username])) {
            if ($stmt->rowCount() > 0) {
                echo "username_taken";
                exit();
            } else {
                echo "username_available";
                exit();
            }
        } else {
            echo "Error executing username query: " . $stmt->errorInfo()[2];  
            exit();
        }
    }

// Check if email exists
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        if ($stmt->execute([$email])) {
            if ($stmt->rowCount() > 0) {
                echo "email_taken";
                exit();
            } else {
                echo "email_available";
                exit();
            }
        } else {
            echo "Error executing email query: " . $stmt->errorInfo()[2]; 
            exit();
        }
    }

// If both username and email are available
    echo "available";
}
?>
