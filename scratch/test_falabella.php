<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$url = 'https://media.falabella.com/falabellaPE/21516950_01/w=1200,h=1200,fit=pad';
$response = \Illuminate\Support\Facades\Http::withoutVerifying()
    ->timeout(8)
    ->withHeaders([
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        'Accept' => 'image/jpeg,image/png,image/gif,image/webp,image/*;q=0.8',
    ])
    ->get($url);

echo "Status: " . $response->status() . "\n";
echo "Content-Type: " . $response->header('Content-Type') . "\n";
echo "Body size: " . strlen($response->body()) . "\n";
