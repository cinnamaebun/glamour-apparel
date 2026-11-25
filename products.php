<?php
// Shared product data (used by index.php and form.php)
$products = [
    'bamboolinen'  => ['name' => 'Bamboo Linen',  'tagline' => 'Perfect for summer', 'price' => 499,  'card' => 'bamboolinen.png',  'prefix' => 'bamboolinen',  'colors' => 3, 'stock' => 2],
    'cottonshirt'  => ['name' => 'Cotton Shirt',  'tagline' => 'Sticking to Basics', 'price' => 399,  'card' => 'cottonshirt.png',  'prefix' => 'cottonshirt',  'colors' => 4, 'stock' => 7],
    'polesjeans'   => ['name' => 'Pole’s Jeans',  'tagline' => 'Collab on comfort',  'price' => 1299, 'card' => "pole'sjeans.png", 'prefix' => "pole'sjeans", 'colors' => 4, 'stock' => 7],
    'runningshirt' => ['name' => 'Running Shirt', 'tagline' => 'Breezy style',       'price' => 399,  'card' => 'runningshirt.png', 'prefix' => 'runningshirt', 'colors' => 4, 'stock' => 7],
];

// img('logo.png') -> img/logo.png (encodes the apostrophe in pole'sjeans)
function img($file) { return 'img/' . rawurlencode($file); }
function peso($n)   { return 'P' . number_format($n); }
