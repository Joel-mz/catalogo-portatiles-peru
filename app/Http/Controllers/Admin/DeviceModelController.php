<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceModel;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceModelController extends Controller
{
    public function index()
    {
        $models = DeviceModel::with('brand')->latest()->paginate(10);
        return view('admin.models.index', compact('models'));
    }

    public function create()
    {
        $brands = Brand::where('status', true)->get();
        return view('admin.models.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'status' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');

        DeviceModel::create($validated);

        return redirect()->route('admin.models.index')->with('success', 'Modelo creado exitosamente.');
    }

    public function edit(DeviceModel $model)
    {
        $brands = Brand::where('status', true)->get();
        return view('admin.models.edit', compact('model', 'brands'));
    }

    public function update(Request $request, DeviceModel $model)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');

        $model->update($validated);

        return redirect()->route('admin.models.index')->with('success', 'Modelo actualizado exitosamente.');
    }

    public function destroy(DeviceModel $model)
    {
        $model->delete();
        return redirect()->route('admin.models.index')->with('success', 'Modelo eliminado exitosamente.');
    }
}
