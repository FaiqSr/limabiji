<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportDestinationController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\InnovationStepController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\OriginController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// --- Admin Auth Routes (guest) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])
        ->name('admin.login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store']);
});

// --- Admin Routes (auth + editor/admin) ---
Route::middleware(['auth', 'editor.or.admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard & Integrated Settings
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::put('/dashboard/settings', [DashboardController::class, 'updateSettings'])->name('dashboard.settings');

    // Export Destinations Map
    Route::resource('export-destinations', ExportDestinationController::class)->except(['show']);

    // Origins
    Route::get('/origins', [OriginController::class, 'index'])->name('origins.index');
    Route::get('/origins/create', [OriginController::class, 'create'])->name('origins.create');
    Route::post('/origins', [OriginController::class, 'store'])->name('origins.store');
    Route::get('/origins/{origin}/edit', [OriginController::class, 'edit'])->name('origins.edit');
    Route::put('/origins/{origin}', [OriginController::class, 'update'])->name('origins.update');
    Route::delete('/origins/{origin}', [OriginController::class, 'destroy'])->name('origins.destroy');

    // News / Articles
    Route::get('/news', [ArticleController::class, 'index'])->name('news.index');
    Route::get('/news/create', [ArticleController::class, 'create'])->name('news.create');
    Route::post('/news', [ArticleController::class, 'store'])->name('news.store');
    Route::get('/news/{article}/edit', [ArticleController::class, 'edit'])->name('news.edit');
    Route::put('/news/{article}', [ArticleController::class, 'update'])->name('news.update');
    Route::delete('/news/{article}', [ArticleController::class, 'destroy'])->name('news.destroy');

    // News Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Testimonials
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // Innovation / Process Steps
    Route::resource('innovation-steps', InnovationStepController::class)->except(['show']);
    Route::post('/innovation-steps/{innovation_step}/toggle-active', [InnovationStepController::class, 'toggleActive'])->name('innovation-steps.toggle-active');

    // FAQs (Frequently Asked Questions)
    Route::resource('faqs', FaqController::class)->except(['show']);
    Route::post('/faqs/{faq}/toggle-active', [FaqController::class, 'toggleActive'])->name('faqs.toggle-active');

    // Contact Messages / Inquiries
    Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{message}/toggle-read', [ContactMessageController::class, 'toggleRead'])->name('messages.toggle-read');
    Route::delete('/messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');

    // Users (admin only)
    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Media
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
    Route::delete('/media', [MediaController::class, 'destroy'])->name('media.destroy');

    // Site Settings (Contact & Brand Info)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
