<?php
session_start();
require 'config/db.php';
include 'includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    die("Book not found");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $book['title'] ?> | Lumina</title>
    <link rel="stylesheet" href="/Lumina/assets/css/style.css">
</head>
<body>

<div class="book-detail" id="book-detail">

    <img src="uploads/covers/<?= $book['cover_image'] ?>"
         class="detail-cover">

    <div class="detail-info">
        <h2><?= $book['title'] ?></h2>
        <p><strong>Author:</strong> <?= $book['author'] ?></p>
        <p><strong>Category:</strong> <?= $book['category'] ?></p>
        <p><?= $book['description'] ?></p>

        <?php if ($book['stock'] > 0): ?>
            <a href="borrow.php?id=<?= $book['book_id'] ?>"
               class="btn btn-primary">
                Borrow Book
            </a>
        <?php else: ?>
            <p class="out-of-stock">Out of stock</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
