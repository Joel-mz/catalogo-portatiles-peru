<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::latest()->paginate(10);
        
        $editingBrand = null;
        if ($request->has('edit')) {
            $editingBrand = Brand::findOrFail($request->edit);
        }

        // Stats: Top brands by product count
        $topBrands = Brand::withCount('products')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();
            
        $totalProducts = \App\Models\Product::count();

        return view('admin.brands.index', compact('brands', 'editingBrand', 'topBrands', 'totalProducts'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');

        Brand::create($validated);

        return redirect()->route('admin.brands.index')->with('success', 'Marca creada exitosamente.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');

        $brand->update($validated);

        return redirect()->route('admin.brands.index')->with('success', 'Marca actualizada exitosamente.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Marca eliminada exitosamente.');
    }
}
