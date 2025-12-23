<link rel="stylesheet" href="/Lumina/assets/css/style.css">

<?php
session_start();
require '../config/db.php';


$stmt = $pdo->query("
    SELECT p.forum_id, p.title, p.created_at, u.name
    FROM forum_posts p
    JOIN users u ON p.user_id = u.user_id
    ORDER BY p.created_at DESC
");
$posts = $stmt->fetchAll();
?>

<div class="forum-header">

    <h1 class="forum-title">
        Lumina Forum
    </h1>

    <div class="forum-actions">
        <a href="/Lumina/index.php" class="btn btn-secondary">
        ← Back to Home
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="create.php" class="btn btn-primary">
                + Create Post
            </a>
        <?php endif; ?>

        <span class="welcome-text">
            Welcome, <?= htmlspecialchars($_SESSION['name']) ?>
        </span>

        <a href="/Lumina/auth/logout.php" class="btn btn-secondary">
            Logout
        </a>
    </div>

</div>


<?php foreach ($posts as $post): ?>
    <div class="forum-post">

        <h3>
            <a href="view.php?id=<?= $post['forum_id'] ?>">
                <?= htmlspecialchars($post['title']) ?>
            </a>
        </h3>

        <small>
            by <?= htmlspecialchars($post['name']) ?>
            • <?= $post['created_at'] ?>
        </small>

    </div>
<?php endforeach; ?>
