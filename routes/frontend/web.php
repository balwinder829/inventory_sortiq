<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\FrontendController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| Here is where all of the frontend routes for the StockFlow Systems
| inventory portal are registered.
|
*/

Route::name('frontend.')->group(function () {
    // Landing & Information Pages
    Route::get('/', [FrontendController::class, 'index'])->name('home');
    Route::get('/home', [FrontendController::class, 'index'])->name('index');
    Route::get('/about', [FrontendController::class, 'about'])->name('about');
    Route::get('/features', [FrontendController::class, 'features'])->name('features');
    Route::get('/modules', [FrontendController::class, 'modules'])->name('modules');
    
    // Live Stock Catalog
    Route::get('/catalog', [FrontendController::class, 'products'])->name('catalog');
    Route::get('/products', [FrontendController::class, 'products'])->name('catalog.products');
    
    // Operations Pricing & Sourcing Tiers
    Route::get('/pricing', [FrontendController::class, 'pricing'])->name('pricing');
    
    // Helpdesk & Inquiries
    Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
    
    // Logistics Insights & Blogs
    Route::get('/blogs', [FrontendController::class, 'blogs'])->name('blogs');
    
    // Sourcing Buyer Portal & Terminal
    Route::get('/dashboard', [FrontendController::class, 'dashboard'])->name('dashboard');
    Route::get('/portal/dashboard', [FrontendController::class, 'dashboard'])->name('portal.dashboard');
    Route::get('/portal/login', [FrontendController::class, 'login'])->name('portal.login');
    Route::get('/portal/signup', [FrontendController::class, 'signup'])->name('portal.signup');
    Route::get('/signup', [FrontendController::class, 'signup'])->name('signup');
});
