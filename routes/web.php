<?php

use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\DemoSandboxController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PublicWebsiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Enterprise Marketing Website
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicWebsiteController::class, 'home'])->name('home');
Route::get('/features', [PublicWebsiteController::class, 'features'])->name('features');
Route::get('/workflow', [PublicWebsiteController::class, 'workflow'])->name('workflow');
Route::get('/industries', [PublicWebsiteController::class, 'industries'])->name('industries');
Route::get('/pricing', [PublicWebsiteController::class, 'pricing'])->name('pricing');
Route::get('/docs', [PublicWebsiteController::class, 'docs'])->name('docs');
Route::get('/contact', [PublicWebsiteController::class, 'contact'])->name('contact');
Route::post('/contact', function () {
    return back()->with('success', 'Thank you! Your enterprise demo request has been received.');
});
Route::get('/register', [PublicWebsiteController::class, 'register'])->name('register');
Route::post('/register', [PublicWebsiteController::class, 'storeRegistration'])->name('register.store');

/*
|--------------------------------------------------------------------------
| Interactive Sandbox Demo Experience
|--------------------------------------------------------------------------
*/
Route::get('/demo', [DemoSandboxController::class, 'index'])->name('demo');
Route::post('/demo/quick-login', [DemoSandboxController::class, 'quickLogin'])->name('demo.quick_login');
Route::post('/demo/step-advance', [DemoSandboxController::class, 'stepAdvance'])->name('demo.step_advance');

/*
|--------------------------------------------------------------------------
| PDF Document Generation
|--------------------------------------------------------------------------
*/
Route::get('/documents/rma/{rma}/label', [DocumentController::class, 'shippingLabel'])->name('documents.rma.label');
Route::get('/documents/rma/{rma}/voucher', [DocumentController::class, 'rmaVoucher'])->name('documents.rma.voucher');
Route::get('/documents/credit-notes/{creditNote}/{locale?}', [DocumentController::class, 'creditNote'])->name('documents.credit_note');

/*
|--------------------------------------------------------------------------
| Customer Self-Service Portal
|--------------------------------------------------------------------------
*/
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login', [CustomerPortalController::class, 'login'])->name('login');
    Route::post('/login', [CustomerPortalController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [CustomerPortalController::class, 'logout'])->name('logout');
    Route::get('/guest-lookup', [CustomerPortalController::class, 'guestLookup'])->name('guest');

    Route::middleware('auth')->group(function () {
        Route::get('/', [CustomerPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [CustomerPortalController::class, 'orders'])->name('orders');
        Route::get('/returns/create/{order}', [CustomerPortalController::class, 'createReturn'])->name('returns.create');
        Route::post('/returns/store/{order}', [CustomerPortalController::class, 'storeReturn'])->name('returns.store');
        Route::get('/returns/{rma_number}', [CustomerPortalController::class, 'showReturn'])->name('return.show');
        Route::post('/returns/{rma}/comment', [CustomerPortalController::class, 'postComment'])->name('return.comment');
        Route::post('/returns/{rma}/dispute', [CustomerPortalController::class, 'storeDispute'])->name('return.dispute');
    });
});
