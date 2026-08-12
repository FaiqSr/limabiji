<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string', 'max:255'],
            'settings.*.value' => ['required'],
            'settings.*.locale' => ['nullable', 'string', 'in:en,id'],
            'settings.*.group' => ['required', 'string', 'max:100'],
        ]);

        foreach ($validated['settings'] as $setting) {
            SiteSetting::set(
                $setting['key'],
                $setting['value'],
                $setting['locale'] ?? null,
                $setting['group']
            );
        }

        return back()->with('success', 'Settings saved successfully.');
    }
}
