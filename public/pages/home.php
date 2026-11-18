<?php
    $stmt = $pdo->query("SELECT * FROM pizzas WHERE is_available = 1 ORDER BY id ASC LIMIT 3");
    $featuredPizzas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="hero-section">
    <h1 class="hero-title">
        The real Pizza. <br>No excuses.
    </h1>
    <p class="hero-subtitle">
        Try the authentic taste of Italian beauty. Order in a few clicks.
    </p>
    <a href="index.php?page=Menu" class="btn-hero">Go to Menu and order.</a>
</div>

<div class="menu-container">
    <div class="features-grid">
        <div class="feature-box">
            <div class="feature-icon">🔥</div>
            <h3 class="feature-title">Furnace 450°C</h3>
            <p class="feature-desc">Authentic Neapolitan pizzas baked in a traditional wood-fired oven at 450°C for a crispy crust and perfect melt.</p>
        </div>
        <div class="feature-box">
            <div class="feature-icon">🍅</div>
            <h3 class="feature-title">Italian ingredients</h3>
            <p class="feature-desc">San Marzano DOP tomatoes, Fior di Latte mozzarella, and Caputo flour straight from Naples.</p>
        </div>
        <div class="feature-box">
            <div class="feature-icon">🛵</div>
            <h3 class="feature-title">Fast Delivery</h3>
            <p class="feature-desc">Our packaging keeps the pizza at the right temperature so it arrives hot and crispy.</p>
        </div>
    </div>

    <h2 class="bestseller-title">Fresh from the oven</h2>

    <div class="pizza-grid">
        <?php foreach($featuredPizzas as $pizza): ?>
            <div class="pizza-card">
                <div class="pizza-img-wrap">
                    <img src="<?= htmlspecialchars($pizza['image_url'])?>" alt="<?= htmlspecialchars($pizza['name'])?>">
                </div>
                <div class="pizza-details">
                    <h3><?= htmlspecialchars($pizza['name'])?></h3>
                    <p class="desc"><?= htmlspecialchars($pizza['description'])?></p>
                    <div class="pizza-footer">
                        <span class="price"><?= number_format($pizza['price'], 2)?>zł</span>
                        <a href="index.php?page=Cart&add=<?=$pizza['id']?>" class="btn-order">Add to cart</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
