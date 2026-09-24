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
        $products = Product::with(['brand', 'category'])
            ->where('status', true)
            ->latest()
            ->get();
            
        $settings = Setting::pluck('value', 'key');
        
        $pdf = Pdf::loadView('admin.pdf.template', compact('products', 'settings'));
        
        return $pdf->download('catalogo_moyotech_' . date('Y-m-d') . '.pdf');
    }
}
