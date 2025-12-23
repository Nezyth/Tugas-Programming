<link rel="stylesheet" href="/Lumina/assets/css/style.css">

<?php
session_start();
require '../config/db.php';

$forum_id = $_GET['id'] ?? null;
if (!$forum_id) {
    die("Forum not found");
}

$pstmt = $pdo->prepare("
    SELECT p.forum_id, p.user_id, p.title, p.content, p.created_at, u.name
    FROM forum_posts p
    JOIN users u ON p.user_id = u.user_id
    WHERE p.forum_id = ?
");
$pstmt->execute([$forum_id]);
$post = $pstmt->fetch();

if (!$post) {
    die("Post not found");
}

$cstmt = $pdo->prepare("
    SELECT c.comment_id, c.user_id, c.comment, c.created_at, u.name
    FROM forum_comments c
    JOIN users u ON c.user_id = u.user_id
    WHERE c.forum_id = ?
    ORDER BY c.created_at ASC
");
$cstmt->execute([$forum_id]);
$comments = $cstmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $comment = trim($_POST['comment']);

    if ($comment !== '') {
        $add = $pdo->prepare("
            INSERT INTO forum_comments (forum_id, user_id, comment)
            VALUES (?, ?, ?)
        ");
        $add->execute([$forum_id, $_SESSION['user_id'], $comment]);
    }

    header("Location: view.php?id=$forum_id");
    exit;
}
?>


<div class="forum-header">

    <h1 class="forum-title">Forum</h1>

    <div class="forum-actions">
        <a href="/Lumina/forum/index.php" class="btn btn-secondary">
            ← Back to Lumina Forum
        </a>

        <?php if (isset($_SESSION['name'])): ?>
            <span class="welcome-text">
                Welcome, <?= htmlspecialchars($_SESSION['name']) ?>
            </span>

            <a href="/Lumina/auth/logout.php" class="btn btn-secondary">
                Logout
            </a>
        <?php endif; ?>
    </div>

</div>


<div class="forum-post-single">

    <h2><?= htmlspecialchars($post['title']) ?></h2>

    <div class="forum-post-content">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>

    <div class="forum-post-meta">
        by <?= htmlspecialchars($post['name']) ?>
        • <?= $post['created_at'] ?>
    </div>

    <?php if (
        isset($_SESSION['user_id']) &&
        (
            $_SESSION['user_id'] == $post['user_id'] ||
            ($_SESSION['role'] ?? '') === 'admin'
        )
    ): ?>
        <div class="post-actions">
            <a href="edit.php?id=<?= $forum_id ?>" class="btn btn-secondary">
                Edit Post
            </a>

            <a href="delete.php?id=<?= $forum_id ?>"
               class="btn btn-danger"
               onclick="return confirm('Delete this post?')">
                Delete Post
            </a>
        </div>
    <?php endif; ?>

</div>

<div class="forum-divider"></div>


<h3 class="forum-comments-title">Comments</h3>

<?php if (count($comments) === 0): ?>
    <p class="no-comments">No comments yet.</p>
<?php endif; ?>

<?php foreach ($comments as $c): ?>
    <div class="comment" id="comment-<?= $c['comment_id'] ?>">

        <p><?= nl2br(htmlspecialchars($c['comment'])) ?></p>

        <small>
            <?= htmlspecialchars($c['name']) ?>
            • <?= $c['created_at'] ?>
        </small>

        <?php if (
            isset($_SESSION['user_id']) &&
            (
                $_SESSION['user_id'] == $c['user_id'] ||
                ($_SESSION['role'] ?? '') === 'admin'
            )
        ): ?>
            <div class="comment-actions">
                <a href="edit_comment.php?id=<?= $c['comment_id'] ?>&forum=<?= $forum_id ?>"
                   class="btn btn-secondary">
                    Edit
                </a>

                <a href="delete_comment.php?id=<?= $c['comment_id'] ?>&forum=<?= $forum_id ?>"
                   class="btn btn-danger"
                   onclick="return confirm('Delete this comment?')">
                    Delete
                </a>
            </div>
        <?php endif; ?>

    </div>
<?php endforeach; ?>


<?php if (isset($_SESSION['user_id'])): ?>
    <div class="forum-divider"></div>

    <form method="POST">
        <textarea name="comment"
                  placeholder="Write a comment..."
                  required></textarea>

        <button class="btn btn-primary">
            Comment
        </button>
    </form>
<?php else: ?>
    <p>
        <a href="../auth/login.php">Login</a> to comment.
    </p>
<?php endif; ?>
