<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: books_list.php");
    exit;
}

$stmt = $pdo->prepare("SELECT cover_image, file_path FROM books WHERE book_id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if ($book) {
    @unlink("../uploads/covers/" . $book['cover_image']);
    @unlink("../uploads/books/" . $book['file_path']);

    $delete = $pdo->prepare("DELETE FROM books WHERE book_id = ?");
    $delete->execute([$id]);
}

header("Location: books_list.php");
exit;
