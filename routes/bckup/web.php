<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ItemController;

// Route::get('/login', [AdminController::class, 'login']);
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/', [AdminController::class, 'dashboard']);


// AUth Controllers start
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// AUth Controllers ends

Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::get('/admin/user/create', [AdminUserController::class, 'create'])->name('user.create');
    Route::get('/admin/user/list', [AdminUserController::class, 'list'])->name('user.list');
    
});

Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::get('admin/stores', [StoreController::class, 'index'])->name('stores.index');
    Route::get('admin/stores/create', [StoreController::class, 'create'])->name('stores.create');
    Route::post('admin/stores', [StoreController::class, 'store'])->name('stores.store');
    Route::get('admin/stores/list', [StoreController::class, 'list'])->name('stores.list');
    Route::get('admin/stores/{store}', [StoreController::class, 'show'])->name('stores.show');
    Route::delete('/admin/stores/{store}', [StoreController::class, 'destroy'])->name('stores.destroy');

});

Route::middleware(['auth'])->group(function () {
   Route::get('/stores', [StoreController::class, 'userStores'])->name('user.store');
    Route::get('admin/stores/{store}', [StoreController::class, 'show'])->name('stores.show');
   Route::resource('admin/items', ItemController::class);
   // Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
// Route::post('/items', [ItemController::class, 'store'])->name('items.store');


});



Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users');
// URL to create super admin
 Route::get('/super_admin_signup_form_url', [AdminUserController::class, 'superadmin_create']);


// Route::get('{any}.html', function () {
//     return response()->view('coming-soon', [], 404);
// })->where('any', '.*');

Route::get('{any}.html', function ($any) {
    return redirect("/$any", 301);
})->where('any', '.*');