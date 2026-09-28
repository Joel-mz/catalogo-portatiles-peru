<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\DeviceModel;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function index()
    {
        $categoriesCount = Category::count();
        $brandsCount = Brand::count();
        $productsCount = Product::count();

        return view('admin.imports.index', compact('categoriesCount', 'brandsCount', 'productsCount'));
    }

    /**
     * Download rich Excel template with all product fields and technical specs.
     */
    public function template()
    {
        $headers = [
            'Content-type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=plantilla_productos_completa.xls',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <style>
                th { background-color: #4338CA; color: #ffffff; font-weight: bold; font-family: Arial; font-size: 11pt; text-align: center; border: 1px solid #312E81; padding: 6px; }
                th.specs { background-color: #059669; border-color: #065F46; }
                th.price { background-color: #D97706; border-color: #92400E; }
                td { font-family: Arial; font-size: 10pt; border: 1px solid #E5E7EB; padding: 5px; }
            </style>
        </head>
        <body>
            <table border="1" cellpadding="6" cellspacing="0">
                <thead>
                    <tr>
                        <!-- 1. Identificación -->
                        <th>code</th>
                        <th>sku</th>
                        <th>serial_number</th>
                        
                        <!-- 2. Información Principal -->
                        <th>name</th>
                        <th>category</th>
                        <th>subcategory</th>
                        <th>brand</th>
                        <th>device_model</th>
                        <th>control_type</th>
                        <th>description</th>

                        <!-- 3. Precios e Inventario -->
                        <th class="price">price</th>
                        <th class="price">min_price</th>
                        <th class="price">offer_price</th>
                        <th class="price">stock</th>
                        <th class="price">state</th>
                        <th class="price">warranty</th>

                        <!-- 4. Especificaciones Técnicas -->
                        <th class="specs">processor</th>
                        <th class="specs">ram</th>
                        <th class="specs">storage</th>
                        <th class="specs">screen</th>
                        <th class="specs">graphics</th>
                        <th class="specs">operating_system</th>

                        <!-- 5. Imagen & Estado -->
                        <th>image_url</th>
                        <th>status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">LAP-LEN-001</td>
                        <td>SKU-LENOVO-01</td>
                        <td>PF5WQYD7</td>
                        <td>Laptop Lenovo IdeaPad Slim 3 15.6" Core i7 16GB 512GB</td>
                        <td>Laptops</td>
                        <td>Laptops Oficina</td>
                        <td>Lenovo</td>
                        <td>IdeaPad Slim 3</td>
                        <td>Por Cantidad</td>
                        <td>Equipo portátil de alto rendimiento para productividad, estudios y trabajo remoto.</td>
                        <td style="text-align: right;">2899.00</td>
                        <td style="text-align: right;">2699.00</td>
                        <td style="text-align: right;">2799.00</td>
                        <td style="text-align: center;">10</td>
                        <td>Nuevo</td>
                        <td>1 año</td>
                        <td>Intel Core i7-13620H</td>
                        <td>16GB DDR5 5200MHz</td>
                        <td>512GB SSD M.2 NVMe</td>
                        <td>15.6" FHD (1920x1080) IPS</td>
                        <td>Intel Iris Xe Graphics</td>
                        <td>Windows 11 Home</td>
                        <td>https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800</td>
                        <td style="text-align: center;">1</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">LAP-ASU-002</td>
                        <td>SKU-ASUS-02</td>
                        <td>ASUS8921X</td>
                        <td>Laptop ASUS TUF Gaming A15 RTX 4060 Ryzen 7 16GB 1TB</td>
                        <td>Laptops</td>
                        <td>Gamer</td>
                        <td>ASUS</td>
                        <td>TUF Gaming A15</td>
                        <td>Por Cantidad</td>
                        <td>Laptop Gamer diseñada para juegos exigentes y creación de contenido en alta resolución.</td>
                        <td style="text-align: right;">4599.00</td>
                        <td style="text-align: right;">4299.00</td>
                        <td style="text-align: right;">4399.00</td>
                        <td style="text-align: center;">8</td>
                        <td>Nuevo</td>
                        <td>1 año</td>
                        <td>AMD Ryzen 7 7735HS</td>
                        <td>16GB DDR5 4800MHz</td>
                        <td>1TB SSD M.2 NVMe</td>
                        <td>15.6" FHD 144Hz IPS</td>
                        <td>NVIDIA GeForce RTX 4060 8GB GDDR6</td>
                        <td>Windows 11 Home</td>
                        <td>https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800</td>
                        <td style="text-align: center;">1</td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>';
        
        return response($html, 200, $headers);
    }

    /**
     * Export all products with full attributes to Excel.
     */
    public function export()
    {
        $headers = [
            'Content-type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=productos_exportados_completo.xls',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <style>
                    th { background-color: #059669; color: #ffffff; font-weight: bold; font-family: Arial; font-size: 11pt; border: 1px solid #065F46; padding: 6px; }
                    td { font-family: Arial; font-size: 10pt; border: 1px solid #E5E7EB; padding: 5px; }
                </style>
            </head>
            <body>
                <table border="1" cellpadding="6" cellspacing="0">
                    <thead>
                        <tr>
                            <th>code</th>
                            <th>sku</th>
                            <th>serial_number</th>
                            <th>name</th>
                            <th>category</th>
                            <th>subcategory</th>
                            <th>brand</th>
                            <th>device_model</th>
                            <th>control_type</th>
                            <th>description</th>
                            <th>price</th>
                            <th>min_price</th>
                            <th>offer_price</th>
                            <th>stock</th>
                            <th>state</th>
                            <th>warranty</th>
                            <th>processor</th>
                            <th>ram</th>
                            <th>storage</th>
                            <th>screen</th>
                            <th>graphics</th>
                            <th>operating_system</th>
                            <th>image_url</th>
                            <th>status</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            Product::with(['category', 'subcategory', 'brand', 'deviceModel', 'images'])->chunk(100, function($products) {
                foreach ($products as $p) {
                    $specs = is_array($p->technical_specs) ? $p->technical_specs : [];
                    $mainImage = $p->images->firstWhere('is_main', true) ?? $p->images->first();
                    $imageUrl = $mainImage ? asset('storage/' . $mainImage->image_path) : '';

                    echo '<tr>';
                    echo '<td style="text-align:center;">' . htmlspecialchars($p->code ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($p->sku ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($p->serial_number ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($p->name ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($p->category->name ?? 'Laptops') . '</td>';
                    echo '<td>' . htmlspecialchars($p->subcategory->name ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($p->brand->name ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($p->deviceModel->name ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($p->control_type ?? 'Por Cantidad') . '</td>';
                    echo '<td>' . htmlspecialchars($p->description ?? '') . '</td>';
                    echo '<td style="text-align:right;">' . number_format((float) $p->price, 2, '.', '') . '</td>';
                    echo '<td style="text-align:right;">' . number_format((float) ($p->min_price ?? $p->price * 0.9), 2, '.', '') . '</td>';
                    echo '<td style="text-align:right;">' . ($p->offer_price ? number_format((float) $p->offer_price, 2, '.', '') : '') . '</td>';
                    echo '<td style="text-align:center;">' . intval($p->stock) . '</td>';
                    echo '<td>' . htmlspecialchars($p->state ?? 'Nuevo') . '</td>';
                    echo '<td>' . htmlspecialchars($p->warranty ?? '1 año') . '</td>';
                    echo '<td>' . htmlspecialchars($specs['Procesador'] ?? $specs['processor'] ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($specs['Memoria RAM'] ?? $specs['ram'] ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($specs['Almacenamiento'] ?? $specs['storage'] ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($specs['Pantalla'] ?? $specs['screen'] ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($specs['Gráficos'] ?? $specs['graphics'] ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($specs['Sistema Operativo'] ?? $specs['operating_system'] ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($imageUrl) . '</td>';
                    echo '<td style="text-align:center;">' . ($p->status ? '1' : '0') . '</td>';
                    echo '</tr>';
                }
            });
            
            echo '</tbody></table></body></html>';
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process bulk import from Excel / JSON payload.
     */
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|string'
        ]);

        try {
            $products = json_decode($request->input('products'), true);
            if (!is_array($products) || empty($products)) {
                return redirect()->back()->with('error', 'El archivo no contiene productos válidos.');
            }

            $count = 0;
            
            foreach ($products as $row) {
                $code = trim($row['code'] ?? '');
                $name = trim($row['name'] ?? '');

                if (empty($code) && empty($name)) {
                    continue;
                }

                if (empty($code)) {
                    $code = 'PROD-' . strtoupper(Str::random(6));
                }

                // 1. Resolve Category
                $categoryId = 1;
                $catVal = trim($row['category'] ?? $row['category_id'] ?? $row['categoria'] ?? '');
                if (!empty($catVal)) {
                    if (is_numeric($catVal)) {
                        $categoryId = intval($catVal);
                    } else {
                        $category = Category::firstOrCreate(
                            ['name' => $catVal],
                            ['slug' => Str::slug($catVal), 'status' => true]
                        );
                        $categoryId = $category->id;
                    }
                } else {
                    $firstCat = Category::first();
                    if ($firstCat) $categoryId = $firstCat->id;
                }

                // 2. Resolve Brand
                $brandId = 1;
                $brandVal = trim($row['brand'] ?? $row['brand_id'] ?? $row['marca'] ?? '');
                if (!empty($brandVal)) {
                    if (is_numeric($brandVal)) {
                        $brandId = intval($brandVal);
                    } else {
                        $brand = Brand::firstOrCreate(
                            ['name' => $brandVal],
                            ['slug' => Str::slug($brandVal), 'status' => true]
                        );
                        $brandId = $brand->id;
                    }
                } else {
                    $firstBrand = Brand::first();
                    if ($firstBrand) $brandId = $firstBrand->id;
                }

                // 3. Resolve Subcategory (optional)
                $subcategoryId = null;
                $subcatVal = trim($row['subcategory'] ?? $row['subcategory_id'] ?? $row['subcategoria'] ?? '');
                if (!empty($subcatVal)) {
                    if (is_numeric($subcatVal)) {
                        $subcategoryId = intval($subcatVal);
                    } else {
                        $subcat = Subcategory::firstOrCreate(
                            ['name' => $subcatVal, 'category_id' => $categoryId],
                            ['slug' => Str::slug($subcatVal), 'status' => true]
                        );
                        $subcategoryId = $subcat->id;
                    }
                }

                // 4. Resolve Device Model (optional)
                $deviceModelId = null;
                $modelVal = trim($row['device_model'] ?? $row['device_model_id'] ?? $row['modelo'] ?? '');
                if (!empty($modelVal)) {
                    if (is_numeric($modelVal)) {
                        $deviceModelId = intval($modelVal);
                    } else {
                        $devModel = DeviceModel::firstOrCreate(
                            ['name' => $modelVal, 'brand_id' => $brandId],
                            ['slug' => Str::slug($modelVal), 'status' => true]
                        );
                        $deviceModelId = $devModel->id;
                    }
                }

                // 5. Build Technical Specs Array
                $specs = [];
                $specFields = [
                    'Procesador' => $row['processor'] ?? $row['procesador'] ?? '',
                    'Memoria RAM' => $row['ram'] ?? $row['memoria_ram'] ?? '',
                    'Almacenamiento' => $row['storage'] ?? $row['almacenamiento'] ?? '',
                    'Pantalla' => $row['screen'] ?? $row['pantalla'] ?? '',
                    'Gráficos' => $row['graphics'] ?? $row['graficos'] ?? '',
                    'Sistema Operativo' => $row['operating_system'] ?? $row['sistema_operativo'] ?? '',
                ];

                foreach ($specFields as $specKey => $specVal) {
                    if (!empty(trim((string)$specVal))) {
                        $specs[$specKey] = trim((string)$specVal);
                    }
                }

                // Parse if technical_specs was passed as raw JSON or key-value string
                if (empty($specs) && !empty($row['technical_specs'])) {
                    if (is_array($row['technical_specs'])) {
                        $specs = $row['technical_specs'];
                    } elseif (is_string($row['technical_specs'])) {
                        $decoded = json_decode($row['technical_specs'], true);
                        if (is_array($decoded)) {
                            $specs = $decoded;
                        }
                    }
                }

                // Prices calculation
                $price = isset($row['price']) ? floatval($row['price']) : 0;
                $minPrice = isset($row['min_price']) && !empty($row['min_price']) ? floatval($row['min_price']) : ($price * 0.9);
                $offerPrice = isset($row['offer_price']) && !empty($row['offer_price']) ? floatval($row['offer_price']) : null;
                $isOffer = !empty($offerPrice) && $offerPrice < $price;

                $product = Product::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => $name,
                        'slug' => Str::slug($name) . '-' . strtolower(Str::random(5)),
                        'sku' => !empty($row['sku']) ? trim($row['sku']) : 'SKU-' . strtoupper(Str::random(6)),
                        'serial_number' => !empty($row['serial_number']) ? trim($row['serial_number']) : null,
                        'category_id' => $categoryId,
                        'subcategory_id' => $subcategoryId,
                        'brand_id' => $brandId,
                        'device_model_id' => $deviceModelId,
                        'control_type' => !empty($row['control_type']) ? trim($row['control_type']) : 'Por Cantidad',
                        'description' => !empty($row['description']) ? trim($row['description']) : 'Equipo en excelente estado disponible para entrega inmediata.',
                        'technical_specs' => $specs,
                        'price' => $price,
                        'min_price' => $minPrice,
                        'offer_price' => $offerPrice,
                        'is_offer' => $isOffer,
                        'stock' => isset($row['stock']) ? intval($row['stock']) : 10,
                        'state' => !empty($row['state']) ? trim($row['state']) : 'Nuevo',
                        'warranty' => !empty($row['warranty']) ? trim($row['warranty']) : '1 año',
                        'status' => isset($row['status']) ? (bool)$row['status'] : true,
                        'is_featured' => isset($row['is_featured']) ? (bool)$row['is_featured'] : false,
                        'is_new' => isset($row['is_new']) ? (bool)$row['is_new'] : true,
                    ]
                );

                // 6. Handle Image URL if provided
                $imageUrl = trim($row['image_url'] ?? $row['imagen'] ?? $row['image'] ?? '');
                if (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    // Check if already has this image
                    $existingImg = ProductImage::where('product_id', $product->id)->where('image_path', $imageUrl)->first();
                    if (!$existingImg) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imageUrl,
                            'is_main' => ProductImage::where('product_id', $product->id)->count() === 0,
                        ]);
                    }
                }

                $count++;
            }
            
            return redirect()->back()->with('success', "¡Excelente! Se procesaron y actualizaron exitosamente $count productos con todas sus especificaciones técnicas e inventario.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }
}
