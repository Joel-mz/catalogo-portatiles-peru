<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'store_logo']);

        if ($request->hasFile('store_logo')) {
            $logoPath = $request->file('store_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'store_logo'], ['value' => $logoPath, 'type' => 'string']);
        }

        if (!empty($data['google_site_verification'])) {
            if (preg_match('/content=["\']([^"\']+)["\']/i', $data['google_site_verification'], $matches)) {
                $data['google_site_verification'] = $matches[1];
            }
            $data['google_site_verification'] = trim(strip_tags($data['google_site_verification']));
        }

        if (!empty($data['bing_site_verification'])) {
            if (preg_match('/content=["\']([^"\']+)["\']/i', $data['bing_site_verification'], $matches)) {
                $data['bing_site_verification'] = $matches[1];
            }
            $data['bing_site_verification'] = trim(strip_tags($data['bing_site_verification']));
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => 'string']
            );
        }

        return redirect()->back()->with('success', 'Configuraciones actualizadas exitosamente.');
    }
}
