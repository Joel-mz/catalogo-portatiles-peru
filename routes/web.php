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
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/catalogo', [FrontController::class, 'catalog'])->name('catalog');
Route::get('/producto/{slug}', [FrontController::class, 'show'])->name('product.show');
Route::get('/producto/{slug}/pdf', [FrontController::class, 'downloadPdf'])->name('product.pdf');
Route::post('/api/checkout', [FrontController::class, 'checkout'])->middleware('throttle:10,1')->name('api.checkout');
Route::post('/producto/{slug}/opiniones', [FrontController::class, 'review'])->middleware('throttle:5,1')->name('product.review');

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
    Route::resource('brands', BrandController::class);
    Route::resource('models', DeviceModelController::class);
    Route::get('products/search-by-code', [ProductController::class, 'searchByCode'])->name('products.searchByCode');
    Route::resource('products', ProductController::class);
    Route::resource('sliders', SliderController::class);
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

    Route::get('pdf', [PdfController::class, 'index'])->name('pdf.index');
    Route::post('pdf/generate', [PdfController::class, 'generate'])->name('pdf.generate');
});

require __DIR__.'/auth.php';
