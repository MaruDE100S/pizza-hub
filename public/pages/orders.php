<?php
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header("Location: index.php?page=Login");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([':user_id' => $userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$itemsStmt = $pdo->prepare("SELECT oi.quantity, oi.price_at_time, p.name FROM order_items oi JOIN pizzas p ON oi.pizza_id = p.id WHERE oi.order_id = :order_id");

foreach ($orders as &$order) {
    $itemsStmt->execute([':order_id' => $order['id']]);
    $order['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
}

unset($order);
?>

<div class="menu-container">
    <h1>Your orders</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Order has been placed. Furnace getting hot!
        </div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="alert alert-danger">
            You haven't placed any orders yet. Order some pizza maybe? <a href="index.php?page=Menu">Order now</a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <h3>Order # <?= htmlspecialchars($order['id']) ?></h3>
                            <span class="order-date">
                                <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
                            </span>
                        </div>
                        <div>
                            <span class="status-badge status-<?= htmlspecialchars($order['status']) ?>">
                                <?= strtoupper(htmlspecialchars($order['status'])) ?>
                            </span>
                        </div>

                    </div>
                <ul class="order-items-list">
                    <?php foreach ($order['items'] as $item): ?>
                        <li class="order-item-row">
                            <span><?= $item['quantity'] ?>x <?= htmlspecialchars($item['name']) ?></span><strong><?= number_format($item['price_at_time'] * $item['quantity'], 2) ?>zl</strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
                    <div class="order-footer">
                        <span class="order-address">Delivery to: <?= htmlspecialchars($order['delivery_address']) ?></span>
                        <span class="order-total">
                            Total: <?= number_format($order['total_price'], 2) ?>zl
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
