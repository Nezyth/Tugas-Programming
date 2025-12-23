<link rel="stylesheet" href="/Lumina/assets/css/style.css">

<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT br.borrow_id,
           br.book_id,
           b.title,
           b.author,
           br.borrow_date
    FROM borrowings br
    JOIN books b ON br.book_id = b.book_id
    WHERE br.user_id = ?
      AND br.return_date IS NULL
");

$stmt->execute([$_SESSION['user_id']]);
$books = $stmt->fetchAll();
?>

<div class="forum-header">

    <h1 class="forum-title">
        My Borrowed Books
    </h1>

    <div class="forum-actions">
        <a href="/Lumina/index.php" class="btn btn-secondary">
            ← Back to Home
        </a>

        <span class="welcome-text">
            Welcome, <?= htmlspecialchars($_SESSION['name']) ?>
        </span>

        <a href="/Lumina/auth/logout.php" class="btn btn-secondary">
            Logout
        </a>
    </div>

</div>


<?php if (count($books) === 0): ?>
    <p>You have no borrowed books.</p>
<?php endif; ?>

<?php foreach ($books as $book): ?>
    <div class="borrowed-book" id="borrow-<?= $book['borrow_id'] ?>">

        <p>
            <strong><?= $book['title'] ?></strong><br>
            Borrowed on <?= $book['borrow_date'] ?>
        </p>

        <a href="return.php?id=<?= $book['borrow_id'] ?>"
           class="btn btn-danger"
           onclick="return confirm('Return this book?')">
           Return
        </a>

        <a href="read.php?id=<?= $book['book_id'] ?>"
            class="btn btn-primary">
            Read
        </a>
    </div>
<?php endforeach; ?>

