<?php
session_start();
require '../config/db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = $_POST['title'];
    $author      = $_POST['author'];
    $category    = $_POST['category'];
    $description = $_POST['description'];
    $stock       = $_POST['stock'];


    $coverName = $_FILES['cover_image']['name'];
    $coverTmp  = $_FILES['cover_image']['tmp_name'];
    move_uploaded_file($coverTmp, "../uploads/covers/" . $coverName);


    $bookFileName = $_FILES['book_file']['name'];
    $bookTmp      = $_FILES['book_file']['tmp_name'];
    move_uploaded_file($bookTmp, "../uploads/books/" . $bookFileName);


    $sql = "INSERT INTO books 
            (title, author, category, description, stock, cover_image, file_path)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $title,
        $author,
        $category,
        $description,
        $stock,
        $coverName,
        $bookFileName
    ]);

    $success = "Book added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book | Lumina Admin</title>
    <link rel="stylesheet" href="/Lumina/assets/css/style.css">

</head>
<body>

<div class="admin-container" id="add-book-container">

<div class="forum-header">

    <h1 class="forum-title">
        My Borrowed Books
    </h1>

    <div class="forum-actions">
        <a href="/Lumina/admin/books_list.php" class="btn btn-secondary">
            ← Back to Manage Book
        </a>

        <span class="welcome-text">
            Welcome, <?= htmlspecialchars($_SESSION['name']) ?>
        </span>

        <a href="/Lumina/auth/logout.php" class="btn btn-secondary">
            Logout
        </a>
    </div>

</div>

    <?php if (isset($success)): ?>
        <p class="success-message" id="add-book-success"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST"
          enctype="multipart/form-data"
          class="admin-form"
          id="add-book-form">

        <div class="form-group">
            <input type="text"
                   name="title"
                   class="form-input"
                   id="book-title"
                   placeholder="Book Title"
                   required>
        </div>

        <div class="form-group">
            <input type="text"
                   name="author"
                   class="form-input"
                   id="book-author"
                   placeholder="Author"
                   required>
        </div>

        <div class="form-group">
            <input type="text"
                   name="category"
                   class="form-input"
                   id="book-category"
                   placeholder="Category"
                   required>
        </div>

        <div class="form-group">
            <textarea name="description"
                      class="form-input"
                      id="book-description"
                      placeholder="Book Description"
                      rows="4"
                      required></textarea>
        </div>

        <div class="form-group">
            <input type="number"
                   name="stock"
                   class="form-input"
                   id="book-stock"
                   placeholder="Stock"
                   min="0"
                   required>
        </div>

        <div class="form-group">
            <label class="form-label" for="cover-image">
                Book Cover (JPG / PNG)
            </label>
            <input type="file"
                   name="cover_image"
                   class="form-file"
                   id="cover-image"
                   accept="image/*"
                   required>
        </div>

        <div class="form-group">
            <label class="form-label" for="book-file">
                Book File (PDF)
            </label>
            <input type="file"
                   name="book_file"
                   class="form-file"
                   id="book-file"
                   accept="application/pdf"
                   required>
        </div>

        <button type="submit"
                class="btn btn-primary"
                id="add-book-submit">
            Add Book
        </button>

    </form>

    <a href="../index.php"
       class="btn btn-secondary"
       id="back-home">
        Back to Home
    </a>

</div>

</body>
</html>
