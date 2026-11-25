<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? 'GLAMOUR' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="<?= $bodyClass ?? '' ?>">
<header class="site-header">
    <a href="index.php" class="logo"><img src="img/logo.png" alt="Glamour"></a>
    <nav class="nav-links">
        <a href="index.php">HOME</a>
        <a href="index.php">SHOP</a>
    </nav>
    <form class="search" onsubmit="return false;">
        <input type="text" aria-label="Search">
        <button type="button" aria-label="Search"><img src="img/search.jpg" alt=""></button>
    </form>
    <div class="nav-icons">
        <a href="#" aria-label="Profile"><img src="img/profile.jpg" alt=""></a>
        <a class="move-icon" href="#" aria-label="Cart"><img src="img/cart.jpg" alt=""></a>
        <a class="move-icon"  href="#" aria-label="Menu"><img src="img/menu.jpg" alt=""></a>
    </div>
</header>
