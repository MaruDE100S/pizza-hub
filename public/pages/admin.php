<?php
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pizza'])) {
        $name = trim($_POST['name']) ?? '';
        $description = trim($_POST['description']) ?? '';
        $price = (float)($_POST['price'] ?? 0);
        $category = trim($_POST['category']) ?? 'Classic';
        $imageUrl = trim($_POST['image_url']) ?? '';

        if (empty($name) || $price <= 0) {
            $error = "Name and price are required!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO pizzas (name, description, price, category, image_url, is_available) VALUES (:name, :desc, :price, :cat, :img, 1)");
            $stmt->execute([
                ':name' => $name,
                ':desc' => $description,
                ':price' => $price,
                ':cat' => $category,
                ':img' => $imageUrl
            ]);

            header('Location: index.php?page=Admin&added=1');
            exit;
        }
    }

    if (isset($_GET['toggle_pizza'])) {
        $pizzaId = (int)($_GET['toggle_pizza']);

        $stmt = $pdo->prepare("UPDATE pizzas SET is_available = NOT is_available WHERE id = :id");
        $stmt->execute([':id' => $pizzaId]);
        header('Location: index.php?page=Admin');
        exit;
    }

    $pizzas = $pdo->query("SELECT * FROM pizzas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

    $users = $pdo->query("SELECT id, email, full_name, phone, role, created_at FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="menu-container">
    <h1>Admin Panel</h1>

    <?php if(isset($_GET['added'])): ?>
        <div class="alert alert-success">
            New pizza added successfully!
        </div>
    <?php endif; ?>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="order-card admin-section">
        <h2 class="admin-section-title">Add new pizza</h2>
        <form action="index.php?page=Admin" method="post" class="admin-form">
            <div class="form-row">
                <div class="form-group form-group-flex-2">
                    <label for="name">Pizza Name:</label>
                    <input type="text" name="name" class="form-input" required placeholder="Pepperoni">
                </div>
                <div class="form-group form-group-flex-1">
                    <label for="price">Price (zł):</label>
                    <input type="number" name="price" class="form-input" required placeholder="39.99">
                </div>
                <div class="form-group form-group-flex-1">
                    <label for="category">Category:</label>
                    <input type="text" name="category" class="form-input" required placeholder="Classic">
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" rows="2" class="form-textarea" placeholder="Tomato sause, fresh mozzarella..."></textarea>
            </div>
            <div class="form-group">
                <label for="image_url">URL:</label>
                <input type="url" name="image_url" class="form-input" placeholder="https://...">
            </div>
            <button type="submit" name="add_pizza" class="btn-order">Add to menu</button>
        </form>
    </div>

    <h2 class="admin-section-title">Pizza Catalogue (Pizzas in database: <?= count($pizzas) ?>)</h2>
    <div class="cart-table-wrap admin-section">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Availability</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pizzas as $pizza): ?>
                    <tr>
                        <td><?=$pizza['id']?></td>
                        <td><strong><?=htmlspecialchars($pizza['name'])?></strong></td>
                        <td><?=number_format($pizza['price'], 2)?>zł</td>
                        <td><?=htmlspecialchars($pizza['category'])?></td>
                        <td><span class="status-badge <?= $pizza['is_available'] ? 'status-delivered' : 'status-cancelled'?>"><?=$pizza['is_available'] ? 'AVAILABLE' : 'NOT-AVAILABLE'?></span></td>
                        <td><a href="index.php?page=Admin&toggle_pizza=<?= $pizza['id'] ?>" class="btn-qty btn-toggle"><?= $pizza['is_available'] ? 'HIDE' : 'SHOW' ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h2 class="admin-section-title">Registered users (<?= count($users) ?>)</h2>
    <div class="cart-table-wrap">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Create Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><span class="status-badge <?= $user['role'] === 'admin' ? 'status-cooking' : ($user['role'] === 'employee' ? 'status-ready' : 'status-new') ?>"><?=strtoupper($user['role']) ?></span></td>
                        <td><?=date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

