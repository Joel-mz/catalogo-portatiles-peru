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
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=plantilla_productos.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $columns = ['code', 'name', 'price', 'stock', 'category_id', 'brand_id'];
        
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            // Añadir BOM (Byte Order Mark) para que Excel reconozca UTF-8
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            // Usar punto y coma (;) para que Excel en español lo divida en columnas automáticamente
            fputcsv($file, $columns, ';');
            // Filas de ejemplo
            fputcsv($file, ['PROD-001', 'Ejemplo Laptop Gamer', '1500.00', '10', '1', '1'], ';');
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function export()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=productos_exportados.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $columns = ['code', 'sku', 'serial_number', 'name', 'price', 'stock', 'category_id', 'brand_id', 'description'];
        
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // UTF-8 BOM
            
            fputcsv($file, $columns, ';');
            
            Product::chunk(100, function($products) use ($file) {
                foreach ($products as $p) {
                    fputcsv($file, [
                        $p->code,
                        $p->sku,
                        $p->serial_number,
                        $p->name,
                        $p->price,
                        $p->stock,
                        $p->category_id,
                        $p->brand_id,
                        $p->description
                    ], ';');
                }
            });
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls|max:5120'
        ], [
            'file.mimes' => 'El archivo debe ser un CSV válido.'
        ]);

        try {
            $file = $request->file('file');
            $handle = fopen($file->getRealPath(), 'r');
            
            $header = true;
            $count = 0;
            
            while (($line = fgets($handle)) !== false) {
                $delimiter = strpos($line, ';') !== false ? ';' : ',';
                $row = str_getcsv($line, $delimiter);
                
                if ($header) {
                    $header = false;
                    continue; 
                }
                
                // Formato exportado/importado: code, sku, serial_number, name, price, stock, category_id, brand_id, description
                if (count($row) >= 8) {
                    Product::updateOrCreate(
                        ['code' => trim($row[0])],
                        [
                            'sku' => trim($row[1] ?? ''),
                            'serial_number' => trim($row[2] ?? ''),
                            'name' => trim($row[3]),
                            'slug' => Str::slug(trim($row[3])) . '-' . Str::random(4),
                            'price' => floatval($row[4]),
                            'stock' => intval($row[5]),
                            'category_id' => intval($row[6] ?? 1),
                            'brand_id' => intval($row[7] ?? 1),
                            'description' => isset($row[8]) ? trim($row[8]) : null,
                            'status' => true
                        ]
                    );
                    $count++;
                }
            }
            
            fclose($handle);
            
            return redirect()->back()->with('success', "Se procesaron exitosamente $count productos desde el CSV. (Se usó como copia de seguridad / actualización).");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al importar CSV: ' . $e->getMessage());
        }
    }
}
