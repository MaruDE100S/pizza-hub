<?php
    $role = $_SESSION['role'] ?? 'guest';
?>


<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page ?></title>
    <link rel="stylesheet" href="../public/style/style.css">
    <link rel="icon" type="image/x-icon" href="../assets/icon/favicon.ico">
</head>
<body>
    <div class="nav">
        <a href="index.php?page=Menu">Menu</a>

        <?php if ($role === 'guest'): ?>
            <a href="index.php?page=Login">Login</a>
            <a href="index.php?page=Register">Register</a>
        <?php else: ?>
            <a href="index.php?page=Cart">Cart</a>
            <a href="index.php?page=Orders">Orders</a>

            <?php if ($role === 'admin'): ?>
                <a href="index.php?page=Admin" class="badge">Admin</a>
            <?php elseif ($role === 'employee'): ?>
                <a href="index.php?page=Employee" class="badge">Staff</a>
            <?php endif; ?>

            <a href="index.php?logout=true" class="logout-btn">Logout</a>
        <?php endif; ?>
    </div>
