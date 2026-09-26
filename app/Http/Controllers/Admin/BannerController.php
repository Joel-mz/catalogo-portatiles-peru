<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('id', 'desc')->get();
        $settings = Setting::pluck('value', 'key');
        return view('admin.banners.index', compact('banners', 'settings'));
    }

    public function updatePromotions(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : (string) $value]
            );
        }

        return redirect()->route('admin.banners.index')->with('success', 'Banners y secciones promocionales de Inicio actualizados correctamente.');
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:2048',
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'position' => 'required|string',
            'status' => 'boolean'
        ]);

        $validated['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image'] = $path;
        }

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner creado exitosamente.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|max:2048',
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'position' => 'required|string',
            'status' => 'boolean'
        ]);

        $validated['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $path = $request->file('image')->store('banners', 'public');
            $validated['image'] = $path;
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner actualizado exitosamente.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner eliminado exitosamente.');
    }
}
