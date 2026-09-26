<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function index()
    {
        return view('admin.pdf.index');
    }

    public function generate(Request $request)
    {
        $query = Product::with(['brand', 'category'])->where('status', true);
        
        if ($request->has('only_offers')) {
            $query->where('is_offer', true);
        }
        
        if (!$request->has('include_out_of_stock')) {
            $query->where('stock', '>', 0);
        }
        
        $products = $query->latest()->get();
            
        $settings = Setting::pluck('value', 'key');
        
        $options = [
            'show_specs' => $request->has('show_specs'),
            'show_price' => $request->has('show_price'),
            'custom_title' => $request->input('custom_title'),
            'bank_accounts' => $request->input('bank_accounts'),
            'yape_plin' => $request->input('yape_plin'),
            'store_address' => $request->input('store_address')
        ];
        
        $pdf = Pdf::loadView('admin.pdf.template', compact('products', 'settings', 'options'));
        
        return $pdf->download('catalogo_moyotech_' . date('Y-m-d') . '.pdf');
    }
}
