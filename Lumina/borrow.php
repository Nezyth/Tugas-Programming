<link rel="stylesheet" href="/Lumina/assets/css/style.css">

<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$book_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$book_id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT stock FROM books WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if (!$book || $book['stock'] <= 0) {
    die("Book not available");
}

$borrow = $pdo->prepare("
    INSERT INTO borrowings (user_id, book_id, borrow_date)
    VALUES (?, ?, NOW())
");
$borrow->execute([$user_id, $book_id]);

$update = $pdo->prepare("
    UPDATE books SET stock = stock - 1 WHERE book_id = ?
");
$update->execute([$book_id]);

header("Location: my_books.php");
exit;
