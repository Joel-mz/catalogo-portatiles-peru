<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::orderBy('id', 'desc')->get();
        return view('admin.publicidad.index', compact('advertisements'));
    }

    public function create()
    {
        return view('admin.publicidad.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,webp,gif,svg,mp4,webm,mov,ogg,m4v|max:30720',
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'location' => 'required|string',
            'status' => 'boolean'
        ]);

        $validated['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('publicidad', 'public');
            $validated['image'] = $path;

            // Ensure physical copy in public/storage if needed
            $publicDest = public_path('storage/' . $path);
            if (!file_exists(dirname($publicDest))) {
                @mkdir(dirname($publicDest), 0777, true);
            }
            @copy(storage_path('app/public/' . $path), $publicDest);
        }

        Advertisement::create($validated);

        return redirect()->route('admin.publicidad.index')->with('success', 'Publicidad creada exitosamente.');
    }

    public function edit(Advertisement $publicidad)
    {
        return view('admin.publicidad.edit', compact('publicidad'));
    }

    public function update(Request $request, Advertisement $publicidad)
    {
        $validated = $request->validate([
            'image' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,svg,mp4,webm,mov,ogg,m4v|max:30720',
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'location' => 'required|string',
            'status' => 'boolean'
        ]);

        $validated['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            if ($publicidad->image) {
                if (Storage::disk('public')->exists($publicidad->image)) {
                    Storage::disk('public')->delete($publicidad->image);
                }
                $oldPublicFile = public_path('storage/' . $publicidad->image);
                if (file_exists($oldPublicFile) && is_file($oldPublicFile)) {
                    @unlink($oldPublicFile);
                }
            }
            $path = $request->file('image')->store('publicidad', 'public');
            $validated['image'] = $path;

            // Ensure physical copy in public/storage
            $publicDest = public_path('storage/' . $path);
            if (!file_exists(dirname($publicDest))) {
                @mkdir(dirname($publicDest), 0777, true);
            }
            @copy(storage_path('app/public/' . $path), $publicDest);
        }

        $publicidad->update($validated);

        return redirect()->route('admin.publicidad.index')->with('success', 'Publicidad actualizada exitosamente.');
    }

    public function destroy(Advertisement $publicidad)
    {
        if ($publicidad->image && Storage::disk('public')->exists($publicidad->image)) {
            Storage::disk('public')->delete($publicidad->image);
        }
        
        $publicidad->delete();
        return redirect()->route('admin.publicidad.index')->with('success', 'Publicidad eliminada exitosamente.');
    }
}
