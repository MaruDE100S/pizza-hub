<?php
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email              = trim($_POST['email']) ?? '';
        $phone              = trim($_POST['phone']) ?? '';
        $password           = $_POST['password'] ?? '';
        $confirmPassword    = $_POST['password_confirmation'] ?? '';



        if (empty($email) || empty($phone) || empty($password)) {
            $error = "All fields needed!";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Incorrect email";
        } else if ($password !== $confirmPassword) {
            $error = 'Passwords don\'t match';
        } else if (strlen($password) < 6) {
            $error = "Password must have 6 ";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $error = "Account with this email address alredy exist!";
            } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $insertStmt = $pdo->prepare("INSERT INTO users (email, phone, password_hash) VALUES (:email, :phone, :password)");
            $insertStmt->execute([
                ':email' => $email,
                ':phone' => $phone,
                ':password' => $password_hash
            ]);
            header('Location: index.php?page=Login&registered=1');
            exit;
        }
    }
}
?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?=htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<form method="POST" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="password_confirmation">Confirm Password:</label>
    <input type="password" id="password_confirmation" name="password_confirmation" required>

    <button type="submit">Register</button>
</form>


