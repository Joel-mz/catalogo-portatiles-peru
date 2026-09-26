<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        $subcategories = Subcategory::with('category')->latest()->paginate(10);
        $categories = Category::all();
        
        $editingSubcategory = null;
        if ($request->has('edit')) {
            $editingSubcategory = Subcategory::findOrFail($request->edit);
        }

        return view('admin.subcategories.index', compact('subcategories', 'editingSubcategory', 'categories'));
    }

    public function create()
    {
        return view('admin.subcategories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');

        Subcategory::create($validated);

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategoría creada exitosamente.');
    }

    public function edit(Subcategory $subcategory)
    {
        return view('admin.subcategories.edit', compact('subcategory'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');

        $subcategory->update($validated);

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategoría actualizada exitosamente.');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategoría eliminada exitosamente.');
    }
}
