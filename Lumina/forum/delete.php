<?php
session_start();
require '../config/db.php';

$forum_id = $_GET['id'] ?? null;
if (!$forum_id) die("Post not found");

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}


$sql = "
    DELETE FROM forum_posts
    WHERE forum_id = ?
      AND (user_id = ? OR ? = 'admin')
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $forum_id,
    $_SESSION['user_id'],
    $_SESSION['role'] ?? ''
]);

header("Location: index.php");
exit;
