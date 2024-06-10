<?php
ob_start();  

include 'connect.php';
session_start();

if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit;
}
$admin_username = $_SESSION['admin_username'];

ini_set('display_errors', 1);
error_reporting(E_ALL);


// This creates the books table if not already created
function ensureBooksTableExists($pdo) {
    $sql = "CREATE TABLE IF NOT EXISTS books (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        description TEXT,
        quantity INT(11) NOT NULL DEFAULT 0,
        price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
        genre VARCHAR(255) NOT NULL,
        image_path VARCHAR(255) NOT NULL
    )";
    $pdo->exec($sql);
}

ensureBooksTableExists($pdo);

$search = $_GET['search'] ?? '';


// Fetch books with optional search criteria
$query_books = "SELECT * FROM books";
$params = [];
if (!empty($search)) {
    $query_books .= " WHERE title LIKE :search OR author LIKE :search OR genre LIKE :search OR description LIKE :search";
    $params['search'] = "%$search%";
}
$statement_books = $pdo->prepare($query_books);
$statement_books->execute($params);

$books = $statement_books->fetchAll(PDO::FETCH_ASSOC);



// Function to upload an image
function uploadImage($inputName) {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES[$inputName]['tmp_name'];
        $fileName = $_FILES[$inputName]['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg', 'webp']; // Added webp here
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                return $dest_path;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    return '';
}



// Function to handle book creation
function handleCreateBook($pdo) {
    $title = htmlspecialchars($_POST["title"]);
    $author = htmlspecialchars($_POST["author"]);
    $description = htmlspecialchars($_POST["description"]);
    $quantity = intval($_POST["quantity"]);
    $price = floatval($_POST["price"]);
    $genre = htmlspecialchars($_POST["genre"]);

    $imagePath = uploadImage('bookImage');
    if ($imagePath) {
        $insertQuery = "INSERT INTO books (title, author, description, quantity, price, genre, image_path) VALUES (:title, :author, :description, :quantity, :price, :genre, :image_path)";
        $statement = $pdo->prepare($insertQuery);
        $statement->bindParam(':title', $title);
        $statement->bindParam(':author', $author);
        $statement->bindParam(':description', $description);
        $statement->bindParam(':quantity', $quantity);
        $statement->bindParam(':price', $price);
        $statement->bindParam(':genre', $genre);
        $statement->bindParam(':image_path', $imagePath);
        if ($statement->execute()) {
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Failed to add book. Please try again.";
        }
    } else {
        echo "Error: File upload needed.";
    }
}


// Function to handle book editing
function handleEditBook($pdo) {
    $editBookId = $_POST['editBookId'];
    $editTitle = htmlspecialchars($_POST['editTitle']);
    $editAuthor = htmlspecialchars($_POST['editAuthor']);
    $editGenre = htmlspecialchars($_POST['editGenre']);
    $editDescription = htmlspecialchars($_POST['editDescription']);
    $editQuantity = intval($_POST['editQuantity']);
    $editPrice = floatval($_POST['editPrice']);

    if (isset($_POST['deleteBook']) && $_POST['deleteBook'] === 'true') {
        $query = "DELETE FROM books WHERE id = :id";
        $statement = $pdo->prepare($query);
        if ($statement->execute([':id' => $editBookId])) {
            ob_end_clean(); // Clear the buffer before sending JSON response
            $response = ['success' => true, 'message' => 'Book deleted successfully.'];
        } else {
            ob_end_clean(); // Clear the buffer before sending JSON response
            $response = ['success' => false, 'message' => 'Error deleting book.'];
        }
        echo json_encode($response);
        exit;
    }

    $imagePath = uploadImage('editBookImage');
    $query = "UPDATE books SET 
        title = :title, 
        author = :author, 
        genre = :genre, 
        description = :description, 
        quantity = :quantity, 
        price = :price";
    if ($imagePath) {
        $query .= ", image_path = :image_path";
    }
    $query .= " WHERE id = :id";

    $statement = $pdo->prepare($query);
    $params = [
        ':id' => $editBookId,
        ':title' => $editTitle,
        ':author' => $editAuthor,
        ':genre' => $editGenre,
        ':description' => $editDescription,
        ':quantity' => $editQuantity,
        ':price' => $editPrice
    ];
    if ($imagePath) {
        $params[':image_path'] = $imagePath;
    }

    if ($statement->execute($params)) {


 // Clear the buffer before sending JSON response
        ob_end_clean(); 


        $response = ['success' => true, 'message' => 'Book updated successfully.'];
    } else {
        
// Clear the buffer before sending JSON response
        ob_end_clean(); 
       
        $response = ['success' => false, 'message' => 'Error updating book.'];
    }
    echo json_encode($response);
    exit;
}


// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["title"])) {
        handleCreateBook($pdo);
    } elseif (isset($_POST['editBookId'])) {
        handleEditBook($pdo);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Books</title>
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="./css/books.css">
    <link rel="stylesheet" type="text/css" href="./css/modal.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
</head>
<body>
<div class="container">
        <?php include './includes/sidebar.php'; ?>
        <div class="content">
           
            <div class="search-container">
            <form class="search-form" action="" method="GET">
                    <input type="text" placeholder="Search books..." name="search" value="<?= htmlspecialchars($search) ?>">
                    <button type="submit">Search</button>
                </form>
                <button class="create-book-btn" onclick="openBookModal()">Create Book</button>
                <?php include './includes/create_book_modal.php'; ?>
            </div>
            <h3>Books</h3>
            <div class="books-container">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($books) > 0): ?>
                            <?php foreach ($books as $book): ?>
                                <tr id="book-row-<?php echo htmlspecialchars($book['id']); ?>">
                                    <td><img src="<?php echo htmlspecialchars($book['image_path']); ?>" alt="Book Image" style="width: 100px; height: auto;"></td>
                                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                                    <td><?php echo htmlspecialchars($book['genre']); ?></td>
                                    <td><?php echo htmlspecialchars($book['description']); ?></td>
                                    <td><?php echo htmlspecialchars($book['quantity']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($book['price'], 2)); ?></td>
                                    <td>
                                        <button class="edit-book-btn" onclick="openEditBookModal(
                                            '<?php echo addslashes(htmlspecialchars($book['id'])); ?>',
                                            '<?php echo addslashes(htmlspecialchars($book['title'])); ?>',
                                            '<?php echo addslashes(htmlspecialchars($book['author'])); ?>',
                                            '<?php echo addslashes(htmlspecialchars($book['genre'])); ?>',
                                            `<?php echo addslashes(htmlspecialchars($book['description'])); ?>`,
                                            '<?php echo $book['quantity']; ?>',
                                            '<?php echo $book['price']; ?>'
                                        )">EDIT</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No books found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include './includes/edit_book_modal.php'; ?>

    <script>
        function closeEditBookModal() {
            $('#editBookModal').hide();
        }


// Function to open the edit book modal and populate fields
        function openEditBookModal(bookId, title, author, genre, description, quantity, price) {
    $('#editBookId').val(bookId);
    $('#editTitle').val(decodeURIComponent(title));
    $('#editAuthor').val(decodeURIComponent(author));
    $('#editGenre').val(decodeURIComponent(genre));
    $('#editDescription').val(decodeURIComponent(description));
    $('#editQuantity').val(quantity);
    $('#editPrice').val(price);
    $('#editBookModal').show();
}

        function confirmDelete(bookId) {
            const confirmed = window.confirm("Are you sure you want to delete this book?");
            if (confirmed) {
// Send an AJAX request to delete the book
                $.ajax({
                    url: 'books.php',
                    type: 'POST',
                    data: {
                        editBookId: bookId,
                        deleteBook: 'true'
                    },
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.success) {
                                $('#book-row-' + bookId).remove();
                            } else {
                                console.error(data.message);
                            }
                        } catch (e) {
                            console.error('Unexpected response from server: ' + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting book. Please try again.', status, error);
                    }
                });
            }
        }


// Function to open the create book modal
        function openBookModal() {
            $('#createBookModal').show();
        }

        function closeBookModal() {
            $('#createBookModal').hide();
        }

        $(document).ready(function() {
            $('#editBookForm').on('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);


// Send an AJAX request to edit the book
                $.ajax({
                    url: 'books.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.success) {
                                $('#editBookModal').hide();
                                location.reload();
                            } else {
                                console.error(data.message);
                            }
                        } catch (e) {
                            console.error('Unexpected response from server: ' + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error editing book. Please try again.', status, error);
                    }
                });
            });

            $('#createBookForm').on('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);


 // Send an AJAX request to create the book
                $.ajax({
                    url: 'books.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.success) {
                                $('#createBookModal').hide();
                                location.reload();
                            } else {
                                console.error(data.message);
                            }
                        } catch (e) {
                            console.error('Unexpected response from server: ' + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error creating book. Please try again.', status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>