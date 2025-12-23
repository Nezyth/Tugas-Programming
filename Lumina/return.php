<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$borrow_id = $_GET['id'] ?? null;
$user_id   = $_SESSION['user_id'];

if (!$borrow_id) {
    header("Location: my_books.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT book_id
    FROM borrowings
    WHERE borrow_id = ?
      AND user_id = ?
      AND return_date IS NULL
");
$stmt->execute([$borrow_id, $user_id]);
$borrow = $stmt->fetch();

if (!$borrow) {
    die("Invalid return request");
}

$updateBorrow = $pdo->prepare("
    UPDATE borrowings
    SET return_date = NOW()
    WHERE borrow_id = ?
");
$updateBorrow->execute([$borrow_id]);

$updateBook = $pdo->prepare("
    UPDATE books
    SET stock = stock + 1
    WHERE book_id = ?
");
$updateBook->execute([$borrow['book_id']]);

header("Location: my_books.php");
exit;
