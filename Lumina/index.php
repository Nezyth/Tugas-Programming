<?php
session_start();
require 'config/db.php';
include 'includes/header.php';

$keyword = $_GET['q'] ?? '';
$books = [];

if (isset($_SESSION['user_id'])) {
    if ($keyword) {
        $stmt = $pdo->prepare("
            SELECT * FROM books
            WHERE title LIKE ?
               OR author LIKE ?
               OR category LIKE ?
            ORDER BY created_at DESC
        ");
        $search = "%$keyword%";
        $stmt->execute([$search, $search, $search]);
    } else {
        $stmt = $pdo->query("
            SELECT * FROM books
            ORDER BY created_at DESC
        ");
    }

    $books = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home | Lumina</title>
    <link rel="stylesheet" href="/Lumina/assets/css/style.css">
</head>
<body>

<main class="main-content" id="home-content">

    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="no-borrowed">
            <h3>Welcome to Lumina 📚</h3>
            <p>
                Please <strong>log in</strong> or <strong>register</strong>
                to browse the book catalog and access all features.
            </p>

            <a href="/Lumina/auth/login.php" class="btn btn-primary">
                Login
            </a>
            <a href="/Lumina/auth/register.php" class="btn btn-secondary">
                Register
            </a>
        </div>
    <?php else: ?>

        <p class="info-text">
            Welcome to Lumina — your digital reading space.
        </p>

        <form method="GET" class="search-form" id="search-form">
            <input
                type="text"
                name="q"
                class="search-input"
                placeholder="Search by title, author, category"
                value="<?= htmlspecialchars($keyword) ?>">

            <button class="btn btn-primary">
                Search
            </button>

            <?php if ($keyword): ?>
                <a href="index.php" class="btn btn-secondary">
                    Reset
                </a>
            <?php endif; ?>
        </form>

        <section class="catalog-section" id="catalog-section">
            <h2 class="section-title">Book Catalog</h2>

            <div class="book-grid" id="book-grid">

                <?php if (count($books) === 0): ?>
                    <p class="no-result">
                        No books found for "<?= htmlspecialchars($keyword) ?>"
                    </p>
                <?php endif; ?>

                <?php foreach ($books as $book): ?>
                    <a href="book_detail.php?id=<?= $book['book_id'] ?>"
                       class="book-card-link">

                        <div class="book-card" id="book-<?= $book['book_id'] ?>">

                            <img src="uploads/covers/<?= $book['cover_image'] ?>"
                                 class="book-cover"
                                 alt="<?= htmlspecialchars($book['title']) ?>">

                            <h3 class="book-title">
                                <?= htmlspecialchars($book['title']) ?>
                            </h3>

                            <p class="book-author">
                                <?= htmlspecialchars($book['author']) ?>
                            </p>

                        </div>
                    </a>
                <?php endforeach; ?>

            </div>
        </section>

    <?php endif; ?>

</main>

</body>
</html>
