<?php
session_start();
require 'products.php';

$slug = $_GET['product'] ?? '';
if (!isset($products[$slug])) { header('Location: index.php'); exit; }
$p = $products[$slug];

$sizes  = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
$types  = ['Men', 'Women', 'Kids'];
$errors = [];
$type = $size = $budget = '';
$qty = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type   = $_POST['customer_type'] ?? '';
    $size   = $_POST['size'] ?? '';
    $qty    = max(1, (int)($_POST['quantity'] ?? 1));
    $budget = trim($_POST['budget'] ?? '');

    if (!in_array($type, $types, true)) $errors['type'] = 'Please select a gender.';
    if (!in_array($size, $sizes, true)) $errors['size'] = 'Please choose a size.';

    // Quantity vs stock
    if ($qty > $p['stock']) {
        $errors['qty'] = "Only {$p['stock']} stocks left";
    }

    // Budget vs total
    if ($budget === '' || !is_numeric($budget)) {
        $errors['budget'] = 'Please enter your budget.';
    } elseif ($qty <= $p['stock'] && (float)$budget < $p['price'] * $qty) {
        // only computed when the quantity is valid, so a stock error alone never triggers it
        $errors['budget'] = 'Budget insufficient';
    }

    if (is_numeric($budget)) {
        $budget = number_format((float)$budget, 2, '.', '');   // 500 -> 500.00
    }

    if (!$errors) {
        $_SESSION['cart'][] = ['product' => $slug, 'type' => $type, 'size' => $size, 'qty' => $qty];
        $_SESSION['flash']  = 'Added to cart';
        header('Location: index.php');   // form closes on success
        exit;
    }
}

$pageTitle = $p['name'] . ' | GLAMOUR';
$bodyClass = 'modal-open';
include 'header.php';
?>
<main class="cards">
<?php foreach ($products as $s => $item): ?>
    <div class="card">
        <img src="<?= img($item['card']) ?>" alt="">
        <div class="card-info">
            <h2><?= htmlspecialchars($item['name']) ?></h2>
            <p><?= htmlspecialchars($item['tagline']) ?></p>
            <span class="price"><?= peso($item['price']) ?></span>
        </div>
    </div>
<?php endforeach; ?>
</main>
<?php include 'footer.php'; ?>

<!-- Click anywhere outside the modal to close -->
<a class="overlay" href="index.php" aria-label="Close"></a>

<div class="modal" role="dialog" aria-modal="true">
    <div class="modal-image">
        <div class="main-img">
            <img id="mainImg" src="<?= img($p['prefix'] . '1.jpg') ?>" alt=""
                 onerror="this.style.visibility='hidden'" onload="this.style.visibility='visible'">
        </div>
    </div>

    <form class="modal-form" method="post" action="form.php?product=<?= $slug ?>">
        <div class="title-row">
            <h2><?= htmlspecialchars($p['name']) ?></h2>
            <span><?= peso($p['price']) ?></span>
        </div>

        <label for="customer_type">Customer Type</label>
        <select name="customer_type" id="customer_type">
            <option value="" hidden <?= $type === '' ? 'selected' : '' ?>>Select gender</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= $t ?>" <?= $type === $t ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['type'])): ?><p class="error"><?= $errors['type'] ?></p><?php endif; ?>

        <label for="size">Select size</label>
        <select name="size" id="size">
            <option value="" hidden <?= $size === '' ? 'selected' : '' ?>>Choose a size</option>
            <?php foreach ($sizes as $s): ?>
                <option value="<?= $s ?>" <?= $size === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['size'])): ?><p class="error"><?= $errors['size'] ?></p><?php endif; ?>

        <label>Quantity</label>
        <div class="qty">
            <button type="button" id="minus" aria-label="Decrease"><img src="img/minus.jpg" alt=""></button>
            <span id="qtyVal"><?= $qty ?></span>
            <button type="button" id="plus" aria-label="Increase"><img src="img/plus.jpg" alt=""></button>
            <input type="hidden" name="quantity" id="qtyInput" value="<?= $qty ?>">
        </div>
        <?php if (isset($errors['qty'])): ?><p class="error"><?= $errors['qty'] ?></p><?php endif; ?>

        <label for="budget">Budget (PHP)</label>
        <input type="text" name="budget" id="budget" inputmode="decimal" value="<?= htmlspecialchars($budget) ?>">
        <?php if (isset($errors['budget'])): ?><p class="error"><?= $errors['budget'] ?></p><?php endif; ?>

        <button type="submit" class="add-btn">Add to Cart</button>

        <div class="thumbs">
            <?php for ($i = 1; $i <= $p['colors']; $i++): $src = img($p['prefix'] . $i . '.jpg'); ?>
                <button type="button" class="thumb" data-full="<?= $src ?>" aria-label="Colorway <?= $i ?>">
                    <img src="<?= $src ?>" alt="" onerror="this.style.visibility='hidden'">
                </button>
            <?php endfor; ?>
        </div>
    </form>
</div>

<script>
    // Quantity buttons (if-else)
    const qtyVal = document.getElementById('qtyVal');
    const qtyInput = document.getElementById('qtyInput');

    document.getElementById('minus').onclick = () => {
        let n = parseInt(qtyInput.value, 10);
        if (n > 1) {
            n = n - 1;
        } else {
            n = 1;
        }
        qtyVal.textContent = n;
        qtyInput.value = n;
    };

    document.getElementById('plus').onclick = () => {
        let n = parseInt(qtyInput.value, 10);
        if (n >= 1) {
            n = n + 1;
        } else {
            n = 1;
        }
        qtyVal.textContent = n;
        qtyInput.value = n;
    };

    // Budget: numbers/dot only, shown with two decimals (500 -> 500.00)
    const budget = document.getElementById('budget');
    budget.addEventListener('input', () => {
        budget.value = budget.value.replace(/[^0-9.]/g, '');
    });
    budget.addEventListener('blur', () => {
        const n = parseFloat(budget.value);
        if (isNaN(n)) {
            budget.value = '';
        } else {
            budget.value = n.toFixed(2);
        }
    });

    // Colorway thumbnails -> main image
    const mainImg = document.getElementById('mainImg');
    document.querySelectorAll('.thumb').forEach(t => {
        t.onclick = () => { mainImg.src = t.dataset.full; };
    });

    // Esc closes the form
    document.addEventListener('keydown', e => { if (e.key === 'Escape') location.href = 'index.php'; });
</script>