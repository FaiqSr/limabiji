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

        return redirect()->route('admin.settings.index')
            ->with('success', 'Site settings updated successfully.');
    }
}
