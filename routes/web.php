<?php

use App\Http\Controllers\LandingPages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

// --- Specific Detail Routes (Parameter-based) ---
Route::get('/origin/{name}', [LandingPages::class, 'origins'])->name('landingpages.origins');
Route::get('/news/{article:slug}', [LandingPages::class, 'newsDetail'])->name('landingpages.news.detail');

// Utility Routes
Route::post('/locale', function (Request $request) {
    $locale = $request->input('locale');

    if (! in_array($locale, ['en', 'id'])) {
        return redirect()->back();
    }

    session(['locale' => $locale]);
    Cookie::queue('locale', $locale, 60 * 24 * 30);

    return redirect()->back();
})->name('locale.switch');

Route::get('/storage/{path}', function (string $path) {
    $filePath = storage_path('app/public/'.$path);

    if (! file_exists($filePath) || is_dir($filePath)) {
        abort(404);
    }

    return response()->file($filePath);
})->where('path', '.*');

// Catch-all route untuk seluruh CMS pages (termasuk home, news, testimonials, contact, dll.)
Route::get('/{slug?}', [LandingPages::class, 'show'])
    ->where('slug', '^(?!admin|login|logout|storage|api|locales|news/|origin/).*')
    ->name('landingpages.show');
