<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="main-header" id="site-header">

    <h1 class="site-title">
        <a href="/Lumina/index.php">Lumina Digital Library</a>
    </h1>

 
    <nav class="main-nav" id="main-nav">

   

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/Lumina/forum/index.php" class="btn btn-secondary">
                Forum
            </a>
        <?php endif; ?>


        <?php if (isset($_SESSION['user_id'])): ?>

  
            <a href="/Lumina/my_books.php" class="btn btn-secondary">
                My Books
            </a>


            <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="/Lumina/admin/dashboard.php" class="btn btn-danger">
                    Admin
                </a>
            <?php endif; ?>

            <span class="welcome-text">
                Welcome, <?= htmlspecialchars($_SESSION['name']) ?>
            </span>

            <a href="/Lumina/auth/logout.php" class="btn btn-secondary">
                Logout
            </a>

        <?php else: ?>

            <a href="/Lumina/auth/login.php" class="btn btn-primary">
                Login
            </a>

            <a href="/Lumina/auth/register.php" class="btn btn-secondary">
                Register
            </a>

        <?php endif; ?>

    </nav>
</header>
