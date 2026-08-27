<?php

use App\Http\Controllers\LandingPages;
use App\Http\Controllers\SitemapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

// --- Sitemap for Search Engines / Google Search Console ---
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap', [SitemapController::class, 'index']);

// --- Public Landing Pages ---
Route::get('/', [LandingPages::class, 'index'])->name('landingpages.home');
Route::get('/home', [LandingPages::class, 'index'])->name('landingpages.index');
Route::get('/about', [LandingPages::class, 'about'])->name('landingpages.about');
Route::get('/innovation', [LandingPages::class, 'innovation'])->name('landingpages.innovation');
Route::get('/news', [LandingPages::class, 'news'])->name('landingpages.news');
Route::get('/news/{article:slug}', [LandingPages::class, 'newsDetail'])->name('landingpages.news.detail');
Route::get('/origin/{name}', [LandingPages::class, 'origins'])->name('landingpages.origins');
Route::get('/testimonials', [LandingPages::class, 'testimonials'])->name('landingpages.testimonials');
Route::get('/contact', [LandingPages::class, 'contact'])->name('landingpages.contact');
Route::post('/contact', [LandingPages::class, 'submitContact'])->name('landingpages.contact.submit');

// --- Specialty Coffee Store & Marketplace ---
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StoreController;

Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::get('/store/{product:slug}', [StoreController::class, 'show'])->name('store.show');
Route::post('/store/quiz/recommend', [StoreController::class, 'quiz'])->name('store.quiz.recommend');

// Cart Endpoints
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// RajaOngkir shipping helpers (server-side; never call RajaOngkir from the frontend)
use App\Http\Controllers\RajaOngkirController;

Route::get('/shipping/provinces', [RajaOngkirController::class, 'provinces'])->name('shipping.provinces');
Route::get('/shipping/cities/{provinceId}', [RajaOngkirController::class, 'cities'])->name('shipping.cities');
Route::get('/shipping/districts/{cityId}', [RajaOngkirController::class, 'districts'])->name('shipping.districts');
Route::post('/shipping/cost', [RajaOngkirController::class, 'shippingCost'])->name('shipping.cost');

// Checkout & Payment
Route::get('/checkout', [CheckoutController::class, 'index'])->name('store.checkout');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('store.checkout.process');
Route::get('/order/status/{orderNumber}', [CheckoutController::class, 'status'])->name('store.order.status');
Route::get('/payment/status/{orderNumber}', [CheckoutController::class, 'paymentStatus'])->name('store.order.payment-status');
Route::post('/order/simulate/{orderNumber}', [CheckoutController::class, 'simulatePayment'])->name('store.order.simulate');
Route::post('/payment/midtrans/notification', [CheckoutController::class, 'notification'])->name('payment.midtrans.notification');

// --- Utility Routes ---
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
