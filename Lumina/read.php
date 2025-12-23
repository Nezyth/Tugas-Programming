<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$book_id = $_GET['id'] ?? null;

if (!$book_id) {
    die("Invalid request");
}

$stmt = $pdo->prepare("
    SELECT b.file_path
    FROM borrowings br
    JOIN books b ON br.book_id = b.book_id
    WHERE br.user_id = ?
      AND br.book_id = ?
      AND br.return_date IS NULL
");
$stmt->execute([$user_id, $book_id]);
$book = $stmt->fetch();

if (!$book) {
    die("You are not allowed to read this book");
}

$file = "uploads/books/" . $book['file_path'];

if (!file_exists($file)) {
    die("Book file not found");
}

header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=\"" . basename($file) . "\"");
header("Content-Length: " . filesize($file));

readfile($file);
exit;
