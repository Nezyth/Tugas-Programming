<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC");
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books List | Lumina Admin</title>
    <link rel="stylesheet" href="/Lumina/assets/css/style.css">
</head>
<body>

<div class="forum-header">

    <h1 class="forum-title">
        My Borrowed Books
    </h1>

    <div class="forum-actions">
        <a href="/Lumina/admin/dashboard.php" class="btn btn-secondary">
            ← Back to Dashboard
        </a>

        <span class="welcome-text">
            Welcome, <?= htmlspecialchars($_SESSION['name']) ?>
        </span>

        <a href="/Lumina/auth/logout.php" class="btn btn-secondary">
            Logout
        </a>
    </div>

</div>


    <a href="books_add.php"
       class="btn btn-primary"
       id="add-book-btn">
        + Add New Book
    </a>

    <table class="admin-table" id="books-table" border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td>
                    <img src="../uploads/covers/<?= $book['cover_image'] ?>"
                         width="80"
                         alt="<?= $book['title'] ?>">
                </td>
                <td><?= $book['title'] ?></td>
                <td><?= $book['author'] ?></td>
                <td><?= $book['category'] ?></td>
                <td><?= $book['stock'] ?></td>
                <td>
                    <a href="books_edit.php?id=<?= $book['book_id'] ?>"
                       class="btn btn-secondary"
                       id="edit-book-<?= $book['book_id'] ?>">
                        Edit
                    </a>

                    <a href="books_delete.php?id=<?= $book['book_id'] ?>"
                       class="btn btn-danger"
                       id="delete-book-<?= $book['book_id'] ?>"
                       onclick="return confirm('Delete this book?')">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>

    </table>

</div>

</body>
</html>
