<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'contact_email' => SiteSetting::get('contact_email', null, 'export@limabijiagritech.com'),
            'contact_phone' => SiteSetting::get('contact_phone', null, '+62 812 3456 7890'),
            'contact_hours' => SiteSetting::get('contact_hours', null, 'Mon – Fri, 8:00 – 16:00 WIB'),
            'contact_address_en' => SiteSetting::get('contact_address', 'en', 'Bogor, West Java, Indonesia'),
            'contact_address_id' => SiteSetting::get('contact_address', 'id', 'Bogor, Jawa Barat, Indonesia'),
            'site_name_en' => SiteSetting::get('site_name', 'en', 'Lima Biji Agritech'),
            'site_name_id' => SiteSetting::get('site_name', 'id', 'Lima Biji Agritech'),
            'map_lat' => SiteSetting::get('map_lat', null, -6.5971),
            'map_lng' => SiteSetting::get('map_lng', null, 106.8060),
            'map_zoom' => SiteSetting::get('map_zoom', null, 14),
            'map_label' => SiteSetting::get('map_label', null, 'Lima Biji Agritech'),
            'map_embed_url' => SiteSetting::get('map_embed_url', null, ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_hours' => ['nullable', 'string', 'max:255'],
            'contact_address_en' => ['nullable', 'string', 'max:500'],
            'contact_address_id' => ['nullable', 'string', 'max:500'],
            'site_name_en' => ['nullable', 'string', 'max:255'],
            'site_name_id' => ['nullable', 'string', 'max:255'],
            'map_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'map_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'map_zoom' => ['nullable', 'integer', 'between:1,20'],
            'map_label' => ['nullable', 'string', 'max:255'],
            'map_embed_url' => ['nullable', 'url', 'max:1000'],
        ]);

        SiteSetting::set('contact_email', $validated['contact_email'], null, 'contact');
        SiteSetting::set('contact_phone', $validated['contact_phone'], null, 'contact');
        SiteSetting::set('contact_hours', $validated['contact_hours'] ?? '', null, 'contact');
        SiteSetting::set('contact_address', $validated['contact_address_en'] ?? '', 'en', 'contact');
        SiteSetting::set('contact_address', $validated['contact_address_id'] ?? '', 'id', 'contact');

        if (isset($validated['site_name_en'])) {
            SiteSetting::set('site_name', $validated['site_name_en'], 'en', 'general');
        }
        if (isset($validated['site_name_id'])) {
            SiteSetting::set('site_name', $validated['site_name_id'], 'id', 'general');
        }

        if (isset($validated['map_lat'])) {
            SiteSetting::set('map_lat', (float) $validated['map_lat'], null, 'map');
        }
        if (isset($validated['map_lng'])) {
            SiteSetting::set('map_lng', (float) $validated['map_lng'], null, 'map');
        }
        if (isset($validated['map_zoom'])) {
            SiteSetting::set('map_zoom', (int) $validated['map_zoom'], null, 'map');
        }
        if (isset($validated['map_label'])) {
            SiteSetting::set('map_label', $validated['map_label'], null, 'map');
        }
        if (isset($validated['map_embed_url'])) {
            SiteSetting::set('map_embed_url', $validated['map_embed_url'], null, 'map');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Site settings updated successfully.');
    }
}
