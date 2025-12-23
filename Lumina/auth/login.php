<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Lumina</title>
    <link rel="stylesheet" href="/Lumina/assets/css/style.css">
</head>
<body>

<div class="auth-container" id="login-container">

    <h2 class="auth-title" id="login-title">Login</h2>

    <?php if (isset($error)): ?>
        <p class="error-message" id="login-error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="auth-form" id="login-form">

        <div class="form-group">
            <input type="email"
                   name="email"
                   class="form-input"
                   id="login-email"
                   placeholder="Email Address"
                   required>
        </div>

        <div class="form-group">
            <input type="password"
                   name="password"
                   class="form-input"
                   id="login-password"
                   placeholder="Password"
                   required>
        </div>

        <button type="submit"
                class="btn btn-primary"
                id="login-submit">
            Login
        </button>

    </form>

    <p class="auth-link">
       <a href="register.php" class="link" id>Don’t have an account?</a>
