<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Services\PdfService;

$products = Product::with('images')->latest()->take(20)->get();
foreach ($products as $p) {
    echo "Product: {$p->name}\n";
    $img = $p->images->first();
    if ($img) {
        echo "  - DB Image Path: {$img->image_path}\n";
        $b64 = PdfService::getProductMainImageBase64($p);
        echo "  - Base64 length: " . ($b64 ? strlen($b64) : 'NULL') . "\n";
    } else {
        echo "  - No images in DB\n";
    }
}
