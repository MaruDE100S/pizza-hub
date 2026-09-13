<?php
    $stmt = $pdo->prepare('SELECT * FROM pizzas WHERE is_available = 1');
    $stmt->execute();
    $pizzas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="menu-container">
    <h1>Our pizzas</h1>

    <div class="pizza-grid">
        <?php foreach ($pizzas as $pizza): ?>
            <div class="pizza-card">
            <div class="pizza-img-wrap">
            <img src="<?= htmlspecialchars($pizza['image_url']) ?>" alt="<?=htmlspecialchars($pizza['name']) ?>">
            </div>
                <div class="pizza-details">
                    <h3><?= htmlspecialchars($pizza['name']) ?></h3>
                    <p class="desc"><?= htmlspecialchars($pizza['description']) ?></p>
                        <div class="pizza-footer">
                            <span class="price"><?= number_format($pizza['price'], 2) ?> zł </span>
                            <a href="index.php?page=Cart&add=<?= $pizza['id'] ?>" class="btn-order">Add to Cart</a>
                        </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
