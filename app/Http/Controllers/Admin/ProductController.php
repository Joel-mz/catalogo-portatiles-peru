<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
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
        $brands = Brand::where('status', true)->get();
        $models = DeviceModel::where('status', true)->get();

        return view('admin.products.create', compact('categories', 'brands', 'models'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'device_model_id' => 'nullable|exists:device_models,id',
            'code' => 'required|string|unique:products,code|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'serial_number' => 'nullable|string|max:255|unique:products,serial_number',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'warranty' => 'nullable|string|max:255',
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

        // Procesar especificaciones dinámicas (arrays paralelos spec_keys y spec_values)
        $specs = [];
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            $keys = $request->input('spec_keys');
            $values = $request->input('spec_values');
            foreach ($keys as $index => $key) {
                if (!empty($key) && !empty($values[$index])) {
                    $specs[$key] = $values[$index];
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
        $brands = Brand::where('status', true)->get();
        $models = DeviceModel::where('status', true)->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'models'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'device_model_id' => 'nullable|exists:device_models,id',
            'code' => 'required|string|max:255|unique:products,code,' . $product->id,
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'serial_number' => 'nullable|string|max:255|unique:products,serial_number,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'warranty' => 'nullable|string|max:255',
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

        // Procesar especificaciones
        $specs = [];
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            $keys = $request->input('spec_keys');
            $values = $request->input('spec_values');
            foreach ($keys as $index => $key) {
                if (!empty($key) && !empty($values[$index])) {
                    $specs[$key] = $values[$index];
                }
            }
        }
        $validated['technical_specs'] = $specs;

        $product->update($validated);

        // Si envían imágenes nuevas, reemplazamos las anteriores
        if ($request->has('image_types')) {
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

    private function processImages(Request $request, Product $product)
    {
        if (!$request->has('image_types')) return;

        $types = $request->input('image_types'); // 'url' o 'file'
        $urls = $request->input('image_urls');
        $files = $request->file('image_files');
        
        $isFirst = true;

        foreach ($types as $index => $type) {
            $path = null;
            
            if ($type === 'url' && !empty($urls[$index])) {
                $path = $urls[$index];
            } 
            elseif ($type === 'file' && isset($files[$index])) {
                $path = $files[$index]->store('products', 'public');
            }

            if ($path) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_main' => $isFirst
                ]);
                $isFirst = false;
            }
        }
    }
}
