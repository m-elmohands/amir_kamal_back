<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SiteSettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');
Route::middleware(['locale', 'guest'])->group(function () {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'store'])->name('admin.login.store');
});
Route::prefix('admin')->name('admin.')->middleware(['locale', 'auth', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::post('/locale', function (Request $request) {
        $locale = $request->validate(['locale' => ['required', 'in:en,ar']])['locale'];
        $request->session()->put('locale', $locale);

        return back();
    })->name('locale');
    Route::resource('products', ProductController::class)->except('show');
    Route::get('/products-data', [ProductController::class, 'data'])->name('products.data');
    Route::get('/settings', [SiteSettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SiteSettingsController::class, 'update'])->name('settings.update');
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('brands', BrandController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries-data', [InquiryController::class, 'data'])->name('inquiries.data');
    Route::patch('/inquiries/{inquiry}', [InquiryController::class, 'update'])->name('inquiries.update');
    Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');
});
