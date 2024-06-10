<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_username = $_SESSION['admin_username'];

$search = '';
$users = [];



// Construct the query to fetch users
$query = "SELECT * FROM users";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $query .= " WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
}
$statement = $pdo->query($query);
$users = $statement->fetchAll(PDO::FETCH_ASSOC);


// Handle user edit request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editUserId'])) {
    $userId = $_POST['editUserId'];
    $firstName = htmlspecialchars($_POST['editUserFirstName']);
    $lastName = htmlspecialchars($_POST['editUserLastName']);
    $email = htmlspecialchars($_POST['editUserEmail']);
    $username = htmlspecialchars($_POST['editUserUsername']);

    $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, username = ? WHERE id = ?");
    if ($stmt->execute([$firstName, $lastName, $email, $username, $userId])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?error=update');
        exit;
    }
}



// Handle user delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteUserId'])) {
    $userId = $_POST['deleteUserId'];

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$userId])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?error=delete');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Users</title>
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="./css/users.css">
    <link rel="stylesheet" type="text/css" href="./css/modal.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <?php include './includes/sidebar.php' ?>
        <div class="content">
            <div class="search-container">
                <form class="search-form" action="" method="GET">
                    <input type="text" placeholder="Search users..." name="search" value="<?= $search ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
            <h3>Users</h3>
            <div class="users-container">
                <?php if (count($users) > 0): ?>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['first_name']) ?></td>
                                <td><?= htmlspecialchars($user['last_name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td>
                                    <button class="edit-user-btn" onclick="openEditUserModal(<?= $user['id'] ?>, '<?= addslashes(htmlspecialchars($user['first_name'])) ?>', '<?= addslashes(htmlspecialchars($user['last_name'])) ?>', '<?= addslashes(htmlspecialchars($user['email'])) ?>', '<?= addslashes(htmlspecialchars($user['username'])) ?>')">EDIT</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No users found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include './includes/edit_user_modal.php' ?>
    <script>


// modal vslidation
        const editUserModal = document.getElementById('editUserModal');

        function openEditUserModal(userId, firstName, lastName, email, username) {
            editUserModal.style.display = "block";
            document.getElementById("editUserId").value = userId;
            document.getElementById("editUserFirstName").value = firstName;
            document.getElementById("editUserLastName").value = lastName;
            document.getElementById("editUserEmail").value = email;
            document.getElementById("editUserUsername").value = username;
        }

        function closeEditUserModal() {
            editUserModal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == editUserModal) {
                closeEditUserModal();
            }
        }

        function validateEditUserForm() {
            const firstName = document.getElementById("editUserFirstName").value.trim();
            const lastName = document.getElementById("editUserLastName").value.trim();
            const email = document.getElementById("editUserEmail").value.trim();
            const username = document.getElementById("editUserUsername").value.trim();
            let isValid = true;

            document.getElementById("editUserFirstNameError").textContent = "";
            document.getElementById("editUserLastNameError").textContent = "";
            document.getElementById("editUserEmailError").textContent = "";
            document.getElementById("editUserUsernameError").textContent = "";

            if (firstName === "") {
                document.getElementById("editUserFirstNameError").textContent = "First name is required.";
                isValid = false;
            }

            if (lastName === "") {
                document.getElementById("editUserLastNameError").textContent = "Last name is required.";
                isValid = false;
            }

            if (email === "") {
                document.getElementById("editUserEmailError").textContent = "Email is required.";
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById("editUserEmailError").textContent = "Invalid email format.";
                isValid = false;
            }

            if (username === "") {
                document.getElementById("editUserUsernameError").textContent = "Username is required.";
                isValid = false;
            }

            return isValid;
        }

        $(document).ready(function() {


// Handle edit user form submission
            $('#editUserForm').on('submit', function(event) {
                event.preventDefault();
                if (!validateEditUserForm()) {
                    return;
                }
                const formData = $(this).serialize();
                
// Send an AJAX request to update the user
                $.ajax({
                    url: 'user.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        const data = JSON.parse(response);
                        alert(data.message);
                        if (data.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error updating user. Please try again.');
                    }
                });
            });


// Handle delete user button click
            $('.delete-user-btn').on('click', function() {
                const userId = document.getElementById("editUserId").value;
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: 'user.php',
                        type: 'POST',
                        data: { deleteUserId: userId },
                        success: function(response) {
                            const data = JSON.parse(response);
                            alert(data.message);
                            if (data.success) {
                                location.reload();
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Error deleting user. Please try again.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
