<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = \App\Models\Product::all();
foreach ($products as $product) {
    $img = $product->images->first();
    if ($img) {
        echo "Product: {$product->name}\n";
        echo "Image: {$img->image_path}\n\n";
    }
}

