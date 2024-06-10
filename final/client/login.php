<?php
session_start();
include './includes/connect.php';

// Function to log in a user
function loginUser($pdo, $username, $password) {
    $query = "SELECT * FROM users WHERE username = :username";
    $statement = $pdo->prepare($query);
    $statement->execute(array(':username' => $username));
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];  
        return true;
    } else {
        throw new Exception("Incorrect username or password. Please try again.");
    }
}

// Function to sign up a new user
function signupUser($pdo, $first_name, $last_name, $email, $username, $password) {
    $query = "INSERT INTO users (first_name, last_name, email, username, password) 
              VALUES (:first_name, :last_name, :email, :username, :password)";
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $statement = $pdo->prepare($query);

    try {

// Execute the query with the user data
        $statement->execute(array(
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':username' => $username,
            ':password' => $hashed_password
        ));
        return true;
    } catch (PDOException $e) {
        throw new Exception("Error signing up: " . $e->getMessage());
    }
}

$error_message = "";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');

    if (isset($_POST["login"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        try {
            $login_result = loginUser($pdo, $username, $password);
            if ($login_result === true) {
                echo json_encode(['success' => true, 'redirect' => 'index.php']);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }


// Handle sign-up request
    } elseif (isset($_POST["signup"])) {
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        try {
            $signup_result = signupUser($pdo, $first_name, $last_name, $email, $username, $password);
            if ($signup_result === true) {
                $_SESSION['success_message'] = "Signup successful. You can now login.";
                header("Location: " . $_SERVER["PHP_SELF"]);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
}

include 'includes/login_form.php'; 
?>
