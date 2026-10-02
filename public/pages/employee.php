<?php

if(!isset($_SESSION['role']) || !in_array($_SESSION['role'],['employee', 'admin'])) {
    header('Location: index.php?page=Home');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = $_POST['status'];

    $allowedStatuses = ['new', 'cooking', 'ready', 'delivered', 'cancelled'];
    if (in_array($newStatus, $allowedStatuses)) {
        $updateStmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $updateStmt->execute([':status' => $newStatus, ':id' => $orderId]);
    }

    header('Location: index.php?page=Employee');
    exit;
}

$ordersStmt = $pdo->query("SELECT o.*, u.email as client_email, u.phone as client_phone FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
$allOrders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);

$itemsStmt = $pdo->prepare("SELECT oi.quantity, p.name FROM order_items oi JOIN pizzas p ON oi.pizza_id = p.id WHERE oi.order_id = :order_id");

foreach ($allOrders as &$ord) {
    $itemsStmt->execute([':order_id' => $ord['id']]);
    $ord['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
}
unset($ord);
?>

<div class="menu-container">
    <h1>Employee dashboard - Manage orders</h1>

    <?php if (empty($allOrders)): ?>
        <div class="alert alert-danger">
            No orders yet.
        </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($allOrders as $ord): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <h3>Order #<?= htmlspecialchars($ord['id']) ?></h3>
                                <span class="order-date">
                                    <?= date('d.m.Y H:i', strtotime($ord['created_at'])) ?> | Client: <strong><?= htmlspecialchars($ord['client_email']) ?? 'Guest'?></strong>
                                    <?php if (!empty($ord['client_phone'])): ?>
                                        (Phone: <?= htmlspecialchars($ord['client_phone']) ?>)
                                        <?php endif; ?>
                                </span>
                            </div>
                            <form class="status-form" action="index.php?page=Employee" method="POST">
                                        <input type="hidden" name="order_id" value="<?= $ord['id'] ?>">
                                        <select class="status-select" name="status">
                                            <option value="new" <?= $ord['status'] === 'new' ? 'selected' : ''?>>NEW</option>
                                            <option value="cooking" <?= $ord['status'] === 'cooking' ? 'selected' : '' ?>>COOKING</option>
                                            <option value="ready" <?= $ord['status'] === 'ready' ? 'selected' : '' ?>>READY</option>
                                            <option value="delivered" <?= $ord['status'] === 'delivered' ? 'selected' : '' ?>>DELIVERED</option>
                                            <option value="cancelled" <?= $ord['status'] === 'cancelled' ? 'selected' : '' ?>>CANCELLED</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn-order">Change</button>
                            </form>
                        </div>
                        <ul class="order-items-list">
                            <?php foreach ($ord['items'] as $item): ?>
                                <li class="order-item-row">
                                    <span>
                                        <?= $item['quantity'] ?>x <?= htmlspecialchars($item['name']) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="order-footer">
                            <span class="order-address">
                                Address: <?= htmlspecialchars($ord['delivery_address']) ?>
                                <?php if (!empty($ord['comment'])): ?>
                                    <br><small>Comment: <?= htmlspecialchars($ord['comment']) ?></small>
                                    <?php endif; ?>
                            </span>
                            <span class="order-total">
                                Total: <?= number_format($ord['total_price'], 2) ?> zl
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
</div>
