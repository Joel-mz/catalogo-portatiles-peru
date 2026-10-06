<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

$products = Product::with('images')->get();
$output = "";
foreach ($products as $p) {
    $img = $p->images->first();
    $path = $img ? $img->image_path : 'NO IMG';
    $output .= "{$p->name} -> {$path}\n";
}
file_put_contents('all_products_dump.txt', $output);
echo "Dumped " . count($products) . " products.\n";
