<?php
session_start();
require 'products.php';

$flash = $_SESSION['flash'] ?? null;   // "Added to cart" after a successful form
unset($_SESSION['flash']);

$pageTitle = 'GLAMOUR';
include 'header.php';
?>
<main class="cards">
<?php foreach ($products as $slug => $p): ?>
    <a class="card" href="form.php?product=<?= $slug ?>">
        <img src="<?= img($p['card']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
        <div class="card-info">
            <h2><?= htmlspecialchars($p['name']) ?></h2>
            <p><?= htmlspecialchars($p['tagline']) ?></p>
            <span class="price"><?= peso($p['price']) ?></span>
        </div>
    </a>
<?php endforeach; ?>
</main>

<?php if ($flash): ?>
    <div class="toast"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<?php include 'footer.php'; ?>
