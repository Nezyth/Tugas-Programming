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

/* Fetch existing book */
$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    die("Book not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = $_POST['title'];
    $author      = $_POST['author'];
    $category    = $_POST['category'];
    $description = $_POST['description'];
    $stock       = $_POST['stock'];

    $coverName = $book['cover_image'];
    if (!empty($_FILES['cover_image']['name'])) {
        $coverName = $_FILES['cover_image']['name'];
        move_uploaded_file(
            $_FILES['cover_image']['tmp_name'],
            "../uploads/covers/" . $coverName
        );
    }

    $bookFileName = $book['file_path'];
    if (!empty($_FILES['book_file']['name'])) {
        $bookFileName = $_FILES['book_file']['name'];
        move_uploaded_file(
            $_FILES['book_file']['tmp_name'],
            "../uploads/books/" . $bookFileName
        );
    }

    $sql = "UPDATE books SET
                title = ?, author = ?, category = ?, description = ?,
                stock = ?, cover_image = ?, file_path = ?
            WHERE book_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $title, $author, $category, $description,
        $stock, $coverName, $bookFileName, $id
    ]);

    header("Location: books_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book | Lumina Admin</title>
    <link rel="stylesheet" href="/Lumina/assets/css/style.css">
</head>
<body>

<div class="admin-container" id="edit-book-container">
    <h2 class="admin-title">Edit Book</h2>

    <form method="POST" enctype="multipart/form-data"
          class="admin-form" id="edit-book-form">

        <input class="form-input" name="title" value="<?= $book['title'] ?>" required>
        <input class="form-input" name="author" value="<?= $book['author'] ?>" required>
        <input class="form-input" name="category" value="<?= $book['category'] ?>" required>

        <textarea class="form-input" name="description" required><?= $book['description'] ?></textarea>

        <input class="form-input" type="number" name="stock"
               value="<?= $book['stock'] ?>" min="0" required>

        <label>Change Cover (optional)</label>
        <input class="form-file" type="file" name="cover_image">

        <label>Change Book File (optional)</label>
        <input class="form-file" type="file" name="book_file">

        <button class="btn btn-primary">Update Book</button>
        <a href="books_list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
