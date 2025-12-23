<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password']; 

    $sql = "INSERT INTO users (name, email, password)
            VALUES (?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $password]);

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Lumina</title>
    <link rel="stylesheet" href="/Lumina/assets/css/style.css">

</head>
<body>

<div class="auth-container" id="register-container">

    <h2 class="auth-title" id="register-title">Create Account</h2>

    <form method="POST" class="auth-form" id="register-form">

        <div class="form-group">
            <input type="text"
                   name="name"
                   class="form-input"
                   id="register-name"
                   placeholder="Full Name"
                   required>
        </div>

        <div class="form-group">
            <input type="email"
                   name="email"
                   class="form-input"
                   id="register-email"
                   placeholder="Email Address"
                   required>
        </div>

        <div class="form-group">
            <input type="password"
                   name="password"
                   class="form-input"
                   id="register-password"
                   placeholder="Password"
                   required>
        </div>

        <button type="submit"
                class="btn btn-primary"
                id="register-submit">
            Register
        </button>

    </form>

    <p class="auth-link">
        Already have an account?
        <a href="login.php" class="link" id="login-link">Login</a>
    </p>

</div>

</body>
</html>
