<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location:/Lumina/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Lumina</title>
    <link rel="stylesheet" href="/Lumina/assets/css/style.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="admin-container admin-dashboard">
    <h2>Admin Dashboard</h2>

    <div class="admin-grid">
        <a href="books_list.php" class="admin-card">Manage Books</a>
    </div>
</div>

</body>
</html>
