<?php

use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\InquiryController;
use App\Http\Controllers\Api\V1\SiteSettingsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('locale')->group(function () {
    Route::get('/settings', SiteSettingsController::class);
    Route::get('/products', [CatalogController::class, 'products']);
    Route::get('/products/{slug}', [CatalogController::class, 'product']);
    Route::get('/catalog/filters', [CatalogController::class, 'filters']);
    Route::post('/inquiries', [InquiryController::class, 'store'])->middleware('throttle:10,1');
});
