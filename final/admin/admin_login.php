<?php
include 'connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$error_message = '';


// Check if the admin table exists, if not, create it
$query_check_table = "SHOW TABLES LIKE 'admin'";
$statement_check_table = $pdo->prepare($query_check_table);
$statement_check_table->execute();
$table_exists = $statement_check_table->rowCount() > 0;

if (!$table_exists) {
    $query_create_table = "CREATE TABLE admin (
                            id INT(11) AUTO_INCREMENT PRIMARY KEY,
                            username VARCHAR(255) NOT NULL,
                            password VARCHAR(255) NOT NULL
                          )";
    $statement_create_table = $pdo->prepare($query_create_table);
    if (!$statement_create_table->execute()) {
        $error_message = "Error creating admin table: " . $statement_create_table->errorInfo()[2];
    }
}


// Check if the default admin user exists if not create it
$query_check_admin = "SELECT * FROM admin WHERE username = 'admin'";
$statement_check_admin = $pdo->prepare($query_check_admin);
$statement_check_admin->execute();
$admin_exists = $statement_check_admin->rowCount() > 0;

if (!$admin_exists) {
    $query_create_admin = "INSERT INTO admin (username, password) VALUES ('admin', :password)";
    $statement_create_admin = $pdo->prepare($query_create_admin);
    $hashed_password = password_hash('12345678', PASSWORD_DEFAULT);
    $statement_create_admin->bindParam(':password', $hashed_password);
    $statement_create_admin->execute();
}



// Handle admin login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    try {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $query = "SELECT * FROM admin WHERE username = :username";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->execute();
        $admin = $statement->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_username'] = $username;
            header("Location: index.php");
            exit;
        } else {
            $error_message = "Incorrect username or password. Please try again.";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="./css/login.css">   
    <link rel="icon" type="image/png" href="./assets/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
        <img src="./assests/black-logo.svg" alt="Logo" class="logo">
        <h1>Admin Login</h1>
        <?php if (!empty($error_message)) : ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <i class="fas fa-user icon"></i>
                <input type="text" id="username" name="username" required>
                <div id="username-error" class="input-error"></div>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <i class="fas fa-lock icon"></i>
                <input type="password" id="password" name="password" required>
                <div id="password-error" class="input-error"></div>
            </div>

            <button type="submit" name="login">Login</button>
        </form>
    </div>

    <script>

// Admin login validation
       $(document).ready(function() {
            function validateField(field, errorDiv, errorMessage) {
                if (field.val().trim() === '') {
                    errorDiv.text(errorMessage);
                    field.addClass('error');
                    return false;
                } else {
                    errorDiv.text('');
                    field.removeClass('error');
                    return true;
                }
            }

            $('#username').on('blur change', function() {
                validateField($(this), $('#username-error'), 'Username is required.');
            });

            $('#password').on('blur change', function() {
                validateField($(this), $('#password-error'), 'Password is required.');
            });

            $('#loginForm').on('submit', function(event) {
                var validUsername = validateField($('#username'), $('#username-error'), 'Username is required.');
                var validPassword = validateField($('#password'), $('#password-error'), 'Password is required.');

                if (!validUsername || !validPassword) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
