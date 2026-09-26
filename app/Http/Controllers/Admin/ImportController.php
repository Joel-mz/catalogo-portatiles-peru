<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.imports.index');
    }

    public function template()
    {
        $headers = [
            'Content-type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename=plantilla_productos.xls',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head><meta charset="utf-8"></head>
        <body>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th style="background-color: #4F46E5; color: #ffffff; font-weight: bold; width: 150px; text-align: center;">code</th>
                        <th style="background-color: #4F46E5; color: #ffffff; font-weight: bold; width: 300px; text-align: center;">name</th>
                        <th style="background-color: #4F46E5; color: #ffffff; font-weight: bold; width: 100px; text-align: center;">price</th>
                        <th style="background-color: #4F46E5; color: #ffffff; font-weight: bold; width: 100px; text-align: center;">stock</th>
                        <th style="background-color: #4F46E5; color: #ffffff; font-weight: bold; width: 120px; text-align: center;">category_id</th>
                        <th style="background-color: #4F46E5; color: #ffffff; font-weight: bold; width: 120px; text-align: center;">brand_id</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center; border: 1px solid #d1d5db;">PROD-001</td>
                        <td style="border: 1px solid #d1d5db;">Ejemplo Laptop Gamer RTX 4060</td>
                        <td style="text-align: right; border: 1px solid #d1d5db;">3500.00</td>
                        <td style="text-align: center; border: 1px solid #d1d5db;">10</td>
                        <td style="text-align: center; border: 1px solid #d1d5db;">1</td>
                        <td style="text-align: center; border: 1px solid #d1d5db;">1</td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>';
        
        return response($html, 200, $headers);
    }

    public function export()
    {
        $headers = [
            'Content-type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename=productos_exportados.xls',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head><meta charset="utf-8"></head>
            <body>
                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="background-color: #10B981; color: #ffffff; font-weight: bold;">code</th>
                            <th style="background-color: #10B981; color: #ffffff; font-weight: bold;">sku</th>
                            <th style="background-color: #10B981; color: #ffffff; font-weight: bold;">serial_number</th>
                            <th style="background-color: #10B981; color: #ffffff; font-weight: bold;">name</th>
                            <th style="background-color: #10B981; color: #ffffff; font-weight: bold;">price</th>
                            <th style="background-color: #10B981; color: #ffffff; font-weight: bold;">stock</th>
                            <th style="background-color: #10B981; color: #ffffff; font-weight: bold;">category_id</th>
                            <th style="background-color: #10B981; color: #ffffff; font-weight: bold;">brand_id</th>
                            <th style="background-color: #10B981; color: #ffffff; font-weight: bold;">description</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            Product::chunk(100, function($products) {
                foreach ($products as $p) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($p->code) . '</td>';
                    echo '<td>' . htmlspecialchars($p->sku ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($p->serial_number ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($p->name) . '</td>';
                    echo '<td>' . htmlspecialchars($p->price) . '</td>';
                    echo '<td>' . htmlspecialchars($p->stock) . '</td>';
                    echo '<td>' . htmlspecialchars($p->category_id) . '</td>';
                    echo '<td>' . htmlspecialchars($p->brand_id) . '</td>';
                    echo '<td>' . htmlspecialchars($p->description ?? '') . '</td>';
                    echo '</tr>';
                }
            });
            
            echo '</tbody></table></body></html>';
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|string'
        ]);

        try {
            $products = json_decode($request->input('products'), true);
            $count = 0;
            
            foreach ($products as $row) {
                // Ensure required fields exist
                if (!empty($row['code']) && !empty($row['name'])) {
                    Product::updateOrCreate(
                        ['code' => trim($row['code'])],
                        [
                            'name' => trim($row['name']),
                            'slug' => Str::slug(trim($row['name'])) . '-' . Str::random(4),
                            'price' => isset($row['price']) ? floatval($row['price']) : 0,
                            'min_price' => isset($row['price']) ? floatval($row['price']) * 0.8 : 0,
                            'stock' => isset($row['stock']) ? intval($row['stock']) : 0,
                            'category_id' => isset($row['category_id']) ? intval($row['category_id']) : 1,
                            'brand_id' => isset($row['brand_id']) ? intval($row['brand_id']) : 1,
                            'state' => 'Nuevo',
                            'status' => true
                        ]
                    );
                    $count++;
                }
            }
            
            return redirect()->back()->with('success', "Se importaron/actualizaron exitosamente $count productos desde el archivo.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }
}
