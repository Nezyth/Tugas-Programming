<link rel="stylesheet" href="/Lumina/assets/css/style.css">

<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("
        INSERT INTO forum_posts (user_id, title, content)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$_SESSION['user_id'], $title, $content]);

    header("Location: index.php");
    exit;
}
?>

<h2>Create Forum Post</h2>

<form method="POST">
    <input type="text" name="title" placeholder="Post title" required><br><br>
    <textarea name="content" placeholder="Write your post..." required></textarea><br><br>
    <button class="btn btn-primary">Post</button>
</form>
