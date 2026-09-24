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
            fputcsv($file, $columns);
            // Filas de ejemplo
            fputcsv($file, ['PROD-001', 'Ejemplo Laptop', '1500.00', '10', '1', '1']);
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:5120'
        ], [
            'file.mimes' => 'El archivo debe ser un CSV válido.'
        ]);

        try {
            $file = $request->file('file');
            $handle = fopen($file->getRealPath(), 'r');
            
            $header = true;
            $count = 0;
            
            while ($row = fgetcsv($handle, 1000, ',')) {
                if ($header) {
                    $header = false;
                    continue; // Saltar cabecera
                }
                
                // Formato esperado: code, name, price, stock, category_id, brand_id
                if (count($row) >= 6) {
                    Product::updateOrCreate(
                        ['code' => trim($row[0])],
                        [
                            'name' => trim($row[1]),
                            'slug' => Str::slug(trim($row[1])) . '-' . Str::random(4),
                            'price' => floatval($row[2]),
                            'stock' => intval($row[3]),
                            'category_id' => intval($row[4]),
                            'brand_id' => intval($row[5]),
                            'status' => true
                        ]
                    );
                    $count++;
                }
            }
            
            fclose($handle);
            
            return redirect()->back()->with('success', "Se procesaron exitosamente $count productos desde el CSV.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al importar CSV: ' . $e->getMessage());
        }
    }
}
