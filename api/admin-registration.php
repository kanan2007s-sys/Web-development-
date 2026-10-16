
<?php
require_once __DIR__ . "/../api/auth.php";

requireLogin();
requireRole("admin");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../CSS/admin.css">
</head>
<body>
    <h1>Admin Dashboard</h1>

    <h2>
        Welcome, <?= htmlspecialchars($_SESSION["name"], ENT_QUOTES, "UTF-8") ?>
    </h2>

    <p>You are logged in as an administrator.</p>

    <a href="../api/logout.php">Logout</a>

    <script src="../Javascript/theme-switcher.js"></script>
    <script src="../Javascript/hamburger-menu.js"></script>
</body>
</html>
