<?php
$host = 'localhost';  
$username = 'mperdue_admin';  
$password = 'Z@dmin99';  
$database = 'mperdue_133p';  

try {
   
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
} catch (PDOException $e) {
   
    die("Connection failed: " . $e->getMessage());
}
?>
