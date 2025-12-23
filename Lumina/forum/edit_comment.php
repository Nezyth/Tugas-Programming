<?php
session_start();
require '../config/db.php';

$comment_id = $_GET['id'] ?? null;
$forum_id   = $_GET['forum'] ?? null;

if (!$comment_id || !$forum_id) die("Comment not found");


$stmt = $pdo->prepare("
    SELECT * FROM forum_comments
    WHERE comment_id = ?
");
$stmt->execute([$comment_id]);
$comment = $stmt->fetch();

if (!$comment) die("Comment not found");


if (
    !isset($_SESSION['user_id']) ||
    (
        $_SESSION['user_id'] != $comment['user_id'] &&
        ($_SESSION['role'] ?? '') !== 'admin'
    )
) {
    die("Unauthorized");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['comment']);

    if ($text) {
        $update = $pdo->prepare("
            UPDATE forum_comments
            SET comment = ?
            WHERE comment_id = ?
        ");
        $update->execute([$text, $comment_id]);

        header("Location: view.php?id=$forum_id");
        exit;
    }
}
?>
<link rel="stylesheet" href="/Lumina/assets/css/style.css">

<h2>Edit Comment</h2>

<form method="POST">
    <textarea name="comment" required><?= htmlspecialchars($comment['comment']) ?></textarea>

    <button class="btn btn-primary">Save Changes</button>
    <a href="view.php?id=<?= $forum_id ?>" class="btn btn-secondary">Cancel</a>
</form>
