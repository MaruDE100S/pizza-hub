<?php
    $error = '';
    $success = '';

    if (isset($_GET['registered'])) {
        $success = 'Account freshy created!';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']) ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "All fields needed!";
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => trim($_POST['email'])]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            if ($_SESSION['role'] === 'admin') {
                header('Location: index.php?page=Admin');
            } else if ($_SESSION['role'] === 'employee') {
                header('Location: index.php?page=Employee');
            } else {
                header('Location: index.php?page=Home');
            }
            exit;
        } else {
            $error = 'Invalid email or password!';
        }
    }
    }
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?=htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?=htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit">Login</button>
</form>
