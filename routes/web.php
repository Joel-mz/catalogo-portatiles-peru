<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DeviceModelController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PdfController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/catalogo', [FrontController::class, 'catalog'])->name('catalog');
Route::get('/producto/{slug}', [FrontController::class, 'show'])->name('product.show');
Route::get('/producto/{slug}/pdf', [FrontController::class, 'downloadPdf'])->name('product.pdf');
Route::get('/sitemap.xml', [FrontController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [FrontController::class, 'robots'])->name('robots');
Route::get('/google{code}.html', function (string $code) {
    return response("google-site-verification: google{$code}.html\n", 200, ['Content-Type' => 'text/html; charset=UTF-8']);
})->where('code', '[a-zA-Z0-9_-]+')->name('google.verify');
Route::post('/api/checkout', [FrontController::class, 'checkout'])->middleware('throttle:10,1')->name('api.checkout');
Route::post('/producto/{slug}/opiniones', [FrontController::class, 'review'])->middleware('throttle:5,1')->name('product.review');

// Fallback directo para servir archivos de storage en servidores locales (evita bloqueos 403 de Apache/symlinks)
Route::get('/storage/{path}', function (string $path) {
    $normalizedPath = str_replace('\\', '/', $path);
    $segments = explode('/', $normalizedPath);

    if (in_array('..', $segments, true) || str_contains($normalizedPath, "\0")) {
        abort(404);
    }

    $storageRoots = array_values(array_filter([
        realpath(storage_path('app/public')),
        realpath(public_path('storage')),
    ]));
    $candidates = [
        realpath(storage_path('app/public/' . $normalizedPath)),
        realpath(public_path('storage/' . $normalizedPath)),
    ];
    $fullPath = null;

    foreach ($candidates as $candidate) {
        if ($candidate === false || !is_file($candidate)) {
            continue;
        }

        foreach ($storageRoots as $storageRoot) {
            if (str_starts_with($candidate, $storageRoot . DIRECTORY_SEPARATOR)) {
                $fullPath = $candidate;
                break 2;
            }
        }
    }

    if ($fullPath === null) {
        abort(404);
    }

    return response()->file($fullPath);
})->where('path', '.*')->name('storage.local');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('models', DeviceModelController::class);
    Route::get('products/search-by-code', [ProductController::class, 'searchByCode'])->name('products.searchByCode');
    Route::get('products/qrcodes', [ProductController::class, 'qrCodes'])->name('products.qrcodes');
    Route::delete('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk_delete');
    Route::resource('products', ProductController::class);
    Route::resource('sliders', SliderController::class);
    
    Route::put('banners/promotions', [\App\Http\Controllers\Admin\BannerController::class, 'updatePromotions'])->name('banners.updatePromotions');
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
    Route::resource('publicidad', \App\Http\Controllers\Admin\AdvertisementController::class);

    Route::resource('clients', ClientController::class);
    Route::resource('orders', OrderController::class);
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('orders/{order}/pdf', [OrderController::class, 'downloadPdf'])->name('orders.pdf');
    Route::resource('users', UserController::class);

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('imports', [ImportController::class, 'index'])->name('imports.index');
    Route::post('imports', [ImportController::class, 'store'])->name('imports.store');
    Route::get('imports/template', [ImportController::class, 'template'])->name('imports.template');
    Route::get('imports/export', [ImportController::class, 'export'])->name('imports.export');

    Route::get('pdf', [PdfController::class, 'index'])->name('pdf.index');
    Route::post('pdf/generate', [PdfController::class, 'generate'])->name('pdf.generate');

    Route::get('backups', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backups.index');
    Route::post('backups/generate', [\App\Http\Controllers\Admin\BackupController::class, 'generate'])->name('backups.generate');
    Route::post('backups/upload', [\App\Http\Controllers\Admin\BackupController::class, 'upload'])->name('backups.upload');
    Route::get('backups/{filename}/download', [\App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backups.download');
    Route::post('backups/{filename}/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backups.restore');
    Route::delete('backups/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('backups.delete');
});

require __DIR__.'/auth.php';
