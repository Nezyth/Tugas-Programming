<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$comment_id = $_GET['id'] ?? null;
$forum_id   = $_GET['forum'] ?? null;

if (!$comment_id || !$forum_id) {
    header("Location: index.php");
    exit;
}


$stmt = $pdo->prepare("
    SELECT user_id
    FROM forum_comments
    WHERE comment_id = ?
");
$stmt->execute([$comment_id]);
$comment = $stmt->fetch();

if (!$comment) {
    header("Location: view.php?id=$forum_id");
    exit;
}


if (
    $_SESSION['user_id'] != $comment['user_id'] &&
    ($_SESSION['role'] ?? '') !== 'admin'
) {
    die("Unauthorized");
}


$delete = $pdo->prepare("
    DELETE FROM forum_comments
    WHERE comment_id = ?
");
$delete->execute([$comment_id]);


header("Location: view.php?id=$forum_id");
exit;
