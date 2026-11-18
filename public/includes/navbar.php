<?php
    $role = $_SESSION['role'] ?? 'guest';
?>

<div class="nav">
    <a href="index.php?page=Home">🍕 PizzaHub</a>
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
