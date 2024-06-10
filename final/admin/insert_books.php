<?php
include 'connect.php';

// mock books for testing
$books = [
    [
        'title' => 'Fiction Book',
        'author' => 'Author Fiction',
        'description' => 'A captivating fiction story.',
        'quantity' => 10,
        'price' => 9.99,
        'genre' => 'Fiction',
        'image_path' => './uploads/book.jpg'
    ],
    [
        'title' => 'Mystery Book',
        'author' => 'Author Mystery',
        'description' => 'A thrilling mystery novel.',
        'quantity' => 10,
        'price' => 12.99,
        'genre' => 'Mystery',
        'image_path' => './uploads/book.jpg'
    ],
    [
        'title' => 'Thriller Book',
        'author' => 'Author Thriller',
        'description' => 'A spine-chilling thriller.',
        'quantity' => 10,
        'price' => 11.99,
        'genre' => 'Thriller',
        'image_path' => './uploads/book.jpg'
    ],
    [
        'title' => 'Non-Fiction Book',
        'author' => 'Author Non-Fiction',
        'description' => 'An insightful non-fiction book.',
        'quantity' => 10,
        'price' => 14.99,
        'genre' => 'Non-Fiction',
        'image_path' => './uploads/book.jpg'
    ],
    [
        'title' => 'Horror Book',
        'author' => 'Author Horror',
        'description' => 'A terrifying horror story.',
        'quantity' => 10,
        'price' => 8.99,
        'genre' => 'Horror',
        'image_path' => './uploads/book.jpg'
    ],
    [
        'title' => 'Fantasy Book',
        'author' => 'Author Fantasy',
        'description' => 'A magical fantasy adventure.',
        'quantity' => 10,
        'price' => 15.99,
        'genre' => 'Fantasy',
        'image_path' => './uploads/book.jpg'
    ],
    [
        'title' => 'Biography Book',
        'author' => 'Author Biography',
        'description' => 'An inspiring biography.',
        'quantity' => 10,
        'price' => 13.99,
        'genre' => 'Biography',
        'image_path' => './uploads/book.jpg'
    ],
    [
        'title' => 'History Book',
        'author' => 'Author History',
        'description' => 'A comprehensive history book.',
        'quantity' => 10,
        'price' => 10.99,
        'genre' => 'History',
        'image_path' => './uploads/book.jpg'
    ],
    [
        'title' => 'Romance Book',
        'author' => 'Author Romance',
        'description' => 'A romantic love story.',
        'quantity' => 10,
        'price' => 7.99,
        'genre' => 'Romance',
        'image_path' => './uploads/book.jpg'
    ],
    [
        'title' => 'Poetry Book',
        'author' => 'Author Poetry',
        'description' => 'A collection of beautiful poems.',
        'quantity' => 10,
        'price' => 5.99,
        'genre' => 'Poetry',
        'image_path' => './uploads/book.jpg'
    ]
];

function insertBooks($pdo, $books) {
    foreach ($books as $book) {
        $insertQuery = "INSERT INTO books (title, author, description, quantity, price, genre, image_path) 
                        VALUES (:title, :author, :description, :quantity, :price, :genre, :image_path)";
        $statement = $pdo->prepare($insertQuery);
        $statement->bindParam(':title', $book['title']);
        $statement->bindParam(':author', $book['author']);
        $statement->bindParam(':description', $book['description']);
        $statement->bindParam(':quantity', $book['quantity']);
        $statement->bindParam(':price', $book['price']);
        $statement->bindParam(':genre', $book['genre']);
        $statement->bindParam(':image_path', $book['image_path']);
        $statement->execute();
    }
}

insertBooks($pdo, $books);

echo "Books inserted successfully!";
?>
