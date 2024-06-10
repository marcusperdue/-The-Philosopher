<?php
session_start();
include_once './connect.php';

$response = [
    'success' => false,
    'message' => '',
    'updatedFields' => []
];


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];
$first_name = $_POST["first_name"] ?? '';
$last_name = $_POST["last_name"] ?? '';
$email = $_POST["email"] ?? '';
$password = $_POST["password"] ?? '';


// Validate required fields
if (empty($first_name) || empty($last_name) || empty($email)) {
    $response['message'] = 'Please fill in all required fields.';
    echo json_encode($response);
    exit;
}

try {
    $update_fields = ['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email];
    $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email WHERE id = :user_id";

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_fields['password'] = $hashed_password;
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, password = :password WHERE id = :user_id";
    }

// Prepare and execute the update query
    $stmt = $pdo->prepare($sql);
    foreach ($update_fields as $field => $value) {
        $stmt->bindValue(":$field", $value);
    }
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $response['success'] = true;
    $response['message'] = 'Profile updated successfully.';
    $response['updatedFields'] = array_keys($update_fields);
} catch (PDOException $e) {
    $response['message'] = 'Error updating user information: ' . $e->getMessage();
}

echo json_encode($response);
?>
