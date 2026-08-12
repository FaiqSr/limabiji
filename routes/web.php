<?php

use App\Http\Controllers\LandingPages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPages::class, 'index'])->name('landingpages.index');
Route::get('/origin/{name}', [LandingPages::class, 'origins'])->name('landingpages.origins');
Route::get('/innovation', [LandingPages::class, 'innovation'])->name('landingpages.innovation');
Route::get('/news', [LandingPages::class, 'news'])->name('landingpages.news');
Route::get('/news/{article:slug}', [LandingPages::class, 'newsDetail'])->name('landingpages.news.detail');
Route::get('/testimonials', [LandingPages::class, 'testimonials'])->name('landingpages.testimonials');
Route::get('/contact', [LandingPages::class, 'contact'])->name('landingpages.contact');

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
