<link rel="stylesheet" href="/Lumina/assets/css/style.css">
<?php
session_start();
require '../config/db.php';

$forum_id = $_GET['id'] ?? null;
if (!$forum_id) die("Post not found");


$stmt = $pdo->prepare("SELECT * FROM forum_posts WHERE forum_id = ?");
$stmt->execute([$forum_id]);
$post = $stmt->fetch();

if (!$post) die("Post not found");


if (
    !isset($_SESSION['user_id']) ||
    (
        $_SESSION['user_id'] != $post['user_id'] &&
        ($_SESSION['role'] ?? '') !== 'admin'
    )
) {
    die("Unauthorized");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title && $content) {
        $update = $pdo->prepare("
            UPDATE forum_posts
            SET title = ?, content = ?
            WHERE forum_id = ?
        ");
        $update->execute([$title, $content, $forum_id]);

        header("Location: view.php?id=$forum_id");
        exit;
    }
}
?>

<h2>Edit Post</h2>

<form method="POST">
    <input type="text" name="title"
           value="<?= htmlspecialchars($post['title']) ?>"
           required>

    <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea>

    <button class="btn btn-primary">Save Changes</button>
    <a href="view.php?id=<?= $forum_id ?>" class="btn btn-secondary">Cancel</a>
</form>
