<?php
$_SESSION['cart'] = $_SESSION['cart'] ?? [];

$error = '';

if (isset($_GET['add'])) {
    $pizzaId = (int)($_GET['add'] ?? 0);

    if ($pizzaId > 0) {
        $_SESSION['cart'][$pizzaId] = ($_SESSION['cart'][$pizzaId] ?? 0) + 1;
    }

    header('Location: index.php?page=Cart');
    exit;
}

if (isset($_GET['decrease'])) {
    $pizzaId = (int)($_GET['decrease']);
    if (isset($_SESSION['cart'][$pizzaId])) {
        $_SESSION['cart'][$pizzaId]--;
        if ($_SESSION['cart'][$pizzaId] <= 0) {
            unset($_SESSION['cart'][$pizzaId]);
        }
    }

    header('Location: index.php?page=Cart');
    exit;
}

if (isset($_GET['remove'])) {
    $pizzaId = (int)($_GET['remove']);
    unset($_SESSION['cart'][$pizzaId]);

    header('Location: index.php?page=Cart');
    exit;
}

$pizzas = [];
$totalPrice = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM pizzas WHERE id IN ( $placeholders )");
    $stmt->execute($ids);
    $pizzas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
        $address = trim($_POST['delivery_address']) ?? '';
        $comment = trim($_POST['comment']) ?? '';
        $userId = $_SESSION['user_id'] ?? null;



        if (empty($address)) {
            $error = "Please enter a delivery address.";
        } elseif (empty($_SESSION['cart'])) {
            $error = "Your cart is empty.";
        } else {
            try {
                $pdo->beginTransaction();

                foreach ($pizzas as $pizza) {
                    $totalPrice += $pizza['price'] * ($_SESSION['cart'][$pizza['id']] ?? 0);
                }

                $orderStmt = $pdo->prepare("INSERT INTO orders (user_id, delivery_address, comment, total_price, status) VALUES (:user_id, :address, :comment, :total, 'new')");
                $orderStmt->execute([
                    ':user_id' => $userId,
                    ':address' => $address,
                    ':comment' => $comment,
                    ':total' => $totalPrice
                ]);

                $orderId = $pdo->lastInsertId();

                $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, pizza_id, quantity, price_at_time) VALUES (:order_id, :pizza_id, :qty, :price)");

                foreach ($pizzas as $pizza) {
                    $qty = $_SESSION['cart'][$pizza['id']];
                    $itemStmt->execute([
                        ':order_id' => $orderId,
                        ':pizza_id' => $pizza['id'],
                        ':qty' => $qty,
                        ':price' => $pizza['price']
                    ]);
                }

                $pdo->commit();

                unset($_SESSION['cart']);

                header("Location: index.php?page=Orders&success=1");
                exit;
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Order failed. Please try again." . $e->getMessage();
            }
        }

    }
}

?>

<div class="menu-container">
    <h1>Your cart</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($pizzas)): ?>
        <div class="alert alert-danger">
            Your cart is empty! <a href="index.php?page=Menu" style="color: white; font-weight: bold;">Go to menu</a>
        </div>
    <?php else: ?>
        <div class="cart-table-wrap">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Pizza</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pizzas as $pizza):
                        $qty = $_SESSION['cart'][$pizza['id']];
                        $subtotal = $qty * $pizza['price'];
                        $totalPrice += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($pizza['name']) ?></strong>
                        </td>
                        <td><?= number_format($pizza['price'], 2) ?> zł</td>
                        <td>
                            <a href="index.php?page=Cart&decrease=<?= $pizza['id'] ?>" class="btn-qty">-</a>
                            <span class="qty-count"><?= $qty ?></span>
                            <a href="index.php?page=Cart&add=<?= $pizza['id'] ?>" class="btn-qty">+</a>
                        </td>
                        <td><strong><?= number_format($subtotal, 2) ?> zł</td>
                        <td>
                            <a href="index.php?page=Cart&remove=<?= $pizza['id'] ?>" class="btn-remove">Remove</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form method="POST" action="index.php?page=Cart" class="checkout-form">
                <h3>Information to delivery</h3>

                <label for="address">Address:</label>
                <textarea name="delivery_address" id="address" required placeholder="Street, city, postal code..."></textarea>

                <label for="comment">Add comment (optional):</label>
                <input type="text" name="comment" id="comment" placeholder="Extra cheese? No onions?..."></input>

                <button type="submit" name="checkout" class="btn-checkout"> Order and pay</button>
            </form>

            <div class="cart-summary">
                <h2>Total price: <span><?= number_format($totalPrice, 2) ?> zł </span>
                </h2>
            </div>
        </div>
    <?php endif; ?>
</div>

