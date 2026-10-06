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
        $previewProducts = Product::with('images')->latest()->take(2)->get();
        return view('admin.pdf.index', compact('previewProducts'));
    }

    public function generate(Request $request)
    {
        $query = Product::with(['brand', 'category', 'images'])->where('status', true);
        
        if ($request->has('only_offers')) {
            $query->where('is_offer', true);
        }
        
        if (!$request->has('include_out_of_stock')) {
            $query->where('stock', '>', 0);
        }
        
        $products = $query->latest()->get();
            
        $settings = Setting::pluck('value', 'key');
        
        $options = [
            'show_description' => $request->boolean('show_description', false),
            'show_specs'       => $request->boolean('show_specs', false),
            'show_price'       => $request->boolean('show_price', false),
            'show_min_price'   => $request->boolean('show_min_price', false),
            'show_stock'       => $request->boolean('show_stock', false),
            'color_theme'      => $request->input('color_theme', 'red'),
            'custom_title'     => $request->input('custom_title'),
            'bank_accounts'    => $request->input('bank_accounts'),
            'yape_plin'        => $request->input('yape_plin'),
            'store_address'    => $request->input('store_address'),
        ];
        
        $pdf = Pdf::loadView('admin.pdf.template', compact('products', 'settings', 'options'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'chroot' => [base_path(), storage_path(), public_path()],
            ]);
        
        return $pdf->download('catalogo_' . date('Y-m-d') . '.pdf');
    }
}
