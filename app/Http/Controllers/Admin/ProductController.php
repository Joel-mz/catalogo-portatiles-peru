<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\DeviceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'images'])->latest()->paginate(10);
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $models = DeviceModel::where('status', true)->get();
        
        return view('admin.products.index', compact('products', 'categories', 'brands', 'models'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();
        $subcategories = Subcategory::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $models = DeviceModel::where('status', true)->get();

        return view('admin.products.create', compact('categories', 'subcategories', 'brands', 'models'));
    }

    public function store(Request $request)
    {
        if (empty($request->code)) {
            do {
                $candidateCode = '775' . str_pad((string)mt_rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
            } while (Product::where('code', $candidateCode)->exists());
            $request->merge(['code' => $candidateCode]);
        }

        if (empty($request->sku)) {
            do {
                $candidateSku = 'SKU-' . strtoupper(Str::random(6));
            } while (Product::where('sku', $candidateSku)->exists());
            $request->merge(['sku' => $candidateSku]);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'brand_id' => 'required|exists:brands,id',
            'device_model_id' => 'nullable|exists:device_models,id',
            'code' => 'required|string|max:255|unique:products,code',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'serial_number' => 'nullable|string|max:255|unique:products,serial_number',
            'control_type' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'warranty' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'image_files' => 'nullable|array|max:10',
            'image_files.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'images_files' => 'nullable|array|max:10',
            'images_files.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_urls' => 'nullable|array|max:10',
            'image_urls.*' => ['nullable', 'url', 'max:2048', 'regex:/^https?:\/\//i'],
            'images_urls' => 'nullable|array|max:10',
            'images_urls.*' => ['nullable', 'url', 'max:2048', 'regex:/^https?:\/\//i'],
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['status'] = $request->has('status');
        $validated['is_offer'] = $request->has('is_offer');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_new'] = $request->has('is_new');
        $validated['min_price'] = !empty($validated['min_price']) ? floatval($validated['min_price']) : (floatval($validated['price']) * 0.9);
        $validated['state'] = !empty($validated['state']) ? $validated['state'] : 'Nuevo';
        $validated['control_type'] = !empty($validated['control_type']) ? $validated['control_type'] : 'Por Cantidad';

        // Generar SKU automático si está vacío
        if (empty($validated['sku'])) {
            $validated['sku'] = 'SKU-' . strtoupper(Str::random(6)) . time();
        }

        // Procesar especificaciones dinámicas
        $specs = [];
        if ($request->has('specs') && is_array($request->input('specs'))) {
            foreach ($request->input('specs') as $spec) {
                $k = trim($spec['name'] ?? $spec['key'] ?? '');
                $v = trim($spec['value'] ?? '');
                if (!empty($k) && !empty($v)) {
                    $specs[$k] = $v;
                }
            }
        }
        $validated['technical_specs'] = $specs;

        $product = Product::create($validated);

        // Procesar imágenes (URLs y Archivos combinados)
        $this->processImages($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', true)->get();
        $subcategories = Subcategory::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $models = DeviceModel::where('status', true)->get();

        $formattedSpecs = [];
        if (is_array($product->technical_specs)) {
            foreach ($product->technical_specs as $key => $value) {
                if (is_array($value) && isset($value['name'])) {
                    $formattedSpecs[] = [
                        'name' => (string)$value['name'],
                        'value' => (string)($value['value'] ?? '')
                    ];
                } else {
                    $formattedSpecs[] = [
                        'name' => (string)$key,
                        'value' => (string)$value
                    ];
                }
            }
        }

        if (empty($formattedSpecs)) {
            $formattedSpecs = [
                ['name' => 'Procesador', 'value' => ''],
                ['name' => 'Memoria RAM', 'value' => ''],
                ['name' => 'Almacenamiento', 'value' => ''],
                ['name' => 'Pantalla', 'value' => ''],
                ['name' => 'Sistema Operativo', 'value' => '']
            ];
        }

        return view('admin.products.edit', compact('product', 'categories', 'subcategories', 'brands', 'models', 'formattedSpecs'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'brand_id' => 'required|exists:brands,id',
            'device_model_id' => 'nullable|exists:device_models,id',
            'code' => 'required|string|max:255|unique:products,code,' . $product->id,
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'serial_number' => 'nullable|string|max:255|unique:products,serial_number,' . $product->id,
            'control_type' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'warranty' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'image_files' => 'nullable|array|max:10',
            'image_files.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'images_files' => 'nullable|array|max:10',
            'images_files.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_urls' => 'nullable|array|max:10',
            'image_urls.*' => ['nullable', 'url', 'max:2048', 'regex:/^https?:\/\//i'],
            'images_urls' => 'nullable|array|max:10',
            'images_urls.*' => ['nullable', 'url', 'max:2048', 'regex:/^https?:\/\//i'],
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['status'] = $request->has('status');
        $validated['is_offer'] = $request->has('is_offer');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_new'] = $request->has('is_new');
        $validated['min_price'] = !empty($validated['min_price']) ? floatval($validated['min_price']) : (floatval($validated['price']) * 0.9);
        $validated['state'] = !empty($validated['state']) ? $validated['state'] : 'Nuevo';
        $validated['control_type'] = !empty($validated['control_type']) ? $validated['control_type'] : 'Por Cantidad';

        // Generar SKU automático si está vacío
        if (empty($validated['sku'])) {
            $validated['sku'] = 'SKU-' . strtoupper(Str::random(6)) . time();
        }

        // Procesar especificaciones (soporta specs[] array y spec_keys/spec_values)
        $specs = [];
        if ($request->has('specs') && is_array($request->input('specs'))) {
            foreach ($request->input('specs') as $spec) {
                $k = trim($spec['name'] ?? $spec['key'] ?? '');
                $v = trim($spec['value'] ?? '');
                if (!empty($k) && !empty($v)) {
                    $specs[$k] = $v;
                }
            }
        } elseif ($request->has('spec_keys') && is_array($request->input('spec_keys'))) {
            $keys = $request->input('spec_keys');
            $values = (array)$request->input('spec_values', []);
            foreach ($keys as $idx => $k) {
                $k = trim((string)$k);
                $v = trim((string)($values[$idx] ?? ''));
                if (!empty($k) && !empty($v)) {
                    $specs[$k] = $v;
                }
            }
        }
        $validated['technical_specs'] = $specs;

        $product->update($validated);

        // Si envían imágenes nuevas, reemplazamos las anteriores
        $hasNewFiles = $request->hasFile('image_files') || $request->hasFile('images_files');
        $hasNewUrls = ($request->has('image_urls') && array_filter((array)$request->input('image_urls'))) || 
                      ($request->has('images_urls') && array_filter((array)$request->input('images_urls')));

        if ($hasNewFiles || $hasNewUrls) {
            // Eliminar imágenes antiguas del storage y de BD
            foreach ($product->images as $img) {
                if (!filter_var($img->image_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($img->image_path);
                }
                $img->delete();
            }
            $this->processImages($request, $product);
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $img) {
            if (!filter_var($img->image_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado exitosamente.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $request->ids)->get();
        foreach ($products as $product) {
            foreach ($product->images as $img) {
                if (!filter_var($img->image_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }
            $product->delete();
        }

        return redirect()->route('admin.products.index')->with('success', count($products) . ' productos eliminados exitosamente.');
    }

    private function processImages(Request $request, Product $product)
    {
        $isFirst = true;

        $files = $request->file('image_files') ?? $request->file('images_files') ?? [];
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_main' => $isFirst
                ]);
                $isFirst = false;
            }
        }

        $urls = $request->input('image_urls') ?? $request->input('images_urls') ?? [];
        if (!is_array($urls)) {
            $urls = [$urls];
        }

        foreach ($urls as $url) {
            if (!empty($url)) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => trim($url),
                    'is_main' => $isFirst
                ]);
                $isFirst = false;
            }
        }
    }

    public function searchByCode(Request $request)
    {
        $code = trim($request->query('code', ''));
        
        if (!$code) {
            return response()->json(['found' => false, 'message' => 'Código no proporcionado'], 400);
        }

        // If a full URL was scanned (e.g. QR code pointing to website URL)
        if (filter_var($code, FILTER_VALIDATE_URL)) {
            $path = parse_url($code, PHP_URL_PATH);
            $segments = array_values(array_filter(explode('/', $path)));
            $lastSegment = end($segments);
            if ($lastSegment) {
                $code = $lastSegment;
            }
        }

        $product = Product::with(['category', 'brand', 'images'])
            ->where('code', $code)
            ->orWhere('sku', $code)
            ->orWhere('serial_number', $code)
            ->orWhere('slug', $code)
            ->orWhere('id', is_numeric($code) ? (int) $code : 0)
            ->first();

        if (!$product) {
            $product = Product::with(['category', 'brand', 'images'])
                ->where('code', 'like', "%{$code}%")
                ->orWhere('serial_number', 'like', "%{$code}%")
                ->first();
        }

        if ($product) {
            $isLaptop = $product->isLaptop();
            $laptopGen = $product->getLaptopGeneration();

            $price = (float) $product->price;
            $offerPrice = (float) ($product->offer_price ?? 0);
            $minPrice = (float) ($product->min_price ?? 0);

            $hasOffer = ($product->is_offer && $offerPrice > 0 && $offerPrice < $price);
            $discountAmount = $hasOffer ? ($price - $offerPrice) : 0;
            $discountPercent = ($hasOffer && $price > 0) ? round(($discountAmount / $price) * 100) : 0;

            $hasMinPrice = ($minPrice > 0 && $minPrice <= $price);
            $maxDiscountAmount = $hasMinPrice ? ($price - $minPrice) : 0;
            $maxDiscountPercent = ($hasMinPrice && $price > 0) ? round(($maxDiscountAmount / $price) * 100) : 0;

            // Formatted specs
            $normalizedSpecs = [];
            if (is_array($product->technical_specs)) {
                foreach ($product->technical_specs as $k => $v) {
                    if (is_array($v)) {
                        $normalizedSpecs[] = [
                            'name' => $v['name'] ?? (is_string($k) ? $k : 'Detalle'),
                            'value' => (string) ($v['value'] ?? ''),
                        ];
                    } else {
                        $normalizedSpecs[] = [
                            'name' => is_string($k) ? $k : 'Detalle',
                            'value' => (string) $v,
                        ];
                    }
                }
            }

            // Images with full URL
            $images = [];
            $mainImageUrl = null;
            foreach ($product->images as $img) {
                $url = str_starts_with($img->image_path, 'http') ? $img->image_path : asset('storage/' . $img->image_path);
                $images[] = [
                    'url' => $url,
                    'is_main' => (bool) $img->is_main,
                ];
                if ($img->is_main && !$mainImageUrl) {
                    $mainImageUrl = $url;
                }
            }
            if (!$mainImageUrl && count($images) > 0) {
                $mainImageUrl = $images[0]['url'];
            }

            return response()->json([
                'found' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'sku' => $product->sku,
                    'serial_number' => $product->serial_number,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'category_name' => $product->category?->name ?? 'General',
                    'brand_name' => $product->brand?->name ?? 'Genérica',
                    'stock' => (int) $product->stock,
                    'status' => (bool) $product->status,
                    'state' => $product->state ?? 'disponible',
                    'warranty' => $product->warranty,
                    'specs' => $normalizedSpecs,
                    'is_laptop' => $isLaptop,
                    'laptop_generation' => $laptopGen,
                    // Precios y Descuentos
                    'price' => $price,
                    'formatted_price' => 'S/ ' . number_format($price, 2, '.', ','),
                    'offer_price' => $offerPrice,
                    'formatted_offer_price' => $offerPrice > 0 ? ('S/ ' . number_format($offerPrice, 2, '.', ',')) : null,
                    'has_offer' => $hasOffer,
                    'discount_amount' => $discountAmount,
                    'formatted_discount_amount' => $discountAmount > 0 ? ('S/ ' . number_format($discountAmount, 2, '.', ',')) : null,
                    'discount_percentage' => $discountPercent,
                    'min_price' => $minPrice,
                    'formatted_min_price' => $minPrice > 0 ? ('S/ ' . number_format($minPrice, 2, '.', ',')) : null,
                    'has_min_price' => $hasMinPrice,
                    'max_discount_amount' => $maxDiscountAmount,
                    'formatted_max_discount_amount' => $maxDiscountAmount > 0 ? ('S/ ' . number_format($maxDiscountAmount, 2, '.', ',')) : null,
                    'max_discount_percentage' => $maxDiscountPercent,
                    // Media & Links
                    'main_image' => $mainImageUrl,
                    'images' => $images,
                    'edit_url' => route('admin.products.edit', $product),
                    'show_url' => route('product.show', $product->slug),
                ]
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'No se encontró ningún producto con el código: ' . $code
        ]);
    }

    public function qrCodes(Request $request)
    {
        $categoryId = $request->query('category_id');
        
        $query = Product::with(['category', 'brand']);
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        $products = $query->get();
        
        return view('admin.products.qrcodes', compact('products'));
    }
}
