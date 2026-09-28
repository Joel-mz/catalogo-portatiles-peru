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
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'brand_id' => 'required|exists:brands,id',
            'device_model_id' => 'nullable|exists:device_models,id',
            'code' => 'required|string|unique:products,code|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'serial_number' => 'nullable|string|max:255|unique:products,serial_number',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_price' => 'required|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'warranty' => 'nullable|string|max:255',
            'state' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['status'] = $request->has('status');
        $validated['is_offer'] = $request->has('is_offer');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_new'] = $request->has('is_new');

        // Generar SKU automático si está vacío
        if (empty($validated['sku'])) {
            $validated['sku'] = 'SKU-' . strtoupper(Str::random(6)) . time();
        }

        // Procesar especificaciones dinámicas
        $specs = [];
        if ($request->has('specs')) {
            foreach ($request->input('specs') as $spec) {
                if (!empty($spec['name']) && !empty($spec['value'])) {
                    $specs[$spec['name']] = $spec['value'];
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
            'subcategory_id' => 'required|exists:subcategories,id',
            'brand_id' => 'required|exists:brands,id',
            'device_model_id' => 'nullable|exists:device_models,id',
            'code' => 'required|string|max:255|unique:products,code,' . $product->id,
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'serial_number' => 'nullable|string|max:255|unique:products,serial_number,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_price' => 'required|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'warranty' => 'nullable|string|max:255',
            'state' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['status'] = $request->has('status');
        $validated['is_offer'] = $request->has('is_offer');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_new'] = $request->has('is_new');

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
            return response()->json(['error' => 'Código no proporcionado'], 400);
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

        $product = Product::with(['category', 'brand'])
            ->where('code', $code)
            ->orWhere('sku', $code)
            ->orWhere('serial_number', $code)
            ->orWhere('slug', $code)
            ->orWhere('id', is_numeric($code) ? (int) $code : 0)
            ->first();

        if ($product) {
            return response()->json([
                'found' => true,
                'product' => $product
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'No se encontró ningún producto con ese código.'
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
