<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPendingController;
use App\Http\Controllers\OrderCompleteController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\WooCommerceController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoiceAnalyticsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProfitAnalysisController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\LowStockController;
use Spatie\Permission\PermissionRegistrar;

Route::middleware(['auth', 'active'])->prefix('admin')->group(function () {
     Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/permissions', [PermissionController::class, 'store']);
    Route::get('/low-stock', [LowStockController::class, 'index'])->name('admin.low_stock.index');
    Route::post('/low-stock/dismiss', [LowStockController::class, 'dismiss'])->name('admin.low_stock.dismiss');
    Route::get('/profit-analysis', [ProfitAnalysisController::class, 'index'])
    ->name('profit.analysis');

});


Route::get('/fix-permissions', function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    return "Permission cache cleared!";
});
// Route::get('/login', [AdminController::class, 'login']);
// Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
// Route::get('/', [AdminController::class, 'dashboard']);

// Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// AUth Controllers start
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Public SEO Routes
Route::get('/sitemap.xml', [App\Http\Controllers\Admin\SeoController::class, 'renderSitemap'])->name('seo.sitemap.xml');
Route::get('/robots.txt', [App\Http\Controllers\Admin\SeoController::class, 'renderRobots'])->name('seo.robots.txt');

// Password Reset Routes
Route::get('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\PasswordResetController::class, 'reset'])->name('password.update');
// AUth Controllers ends

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/admin/user/create', [AdminUserController::class, 'create'])->name('user.create');
    Route::get('/admin/user/list', [AdminUserController::class, 'list'])->name('user.list');
    Route::get('/admin/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/admin/users/{id}', [AdminUserController::class, 'update'])->name('admin.user.update');
});

Route::middleware(['auth', 'active'])->group(function () {
   

    Route::get('admin/stores', [StoreController::class, 'index'])->name('stores.index');
    Route::get('admin/stores/create', [StoreController::class, 'create'])->name('stores.create');
    Route::post('admin/stores', [StoreController::class, 'store'])->name('stores.store');
    Route::get('admin/stores/list', [StoreController::class, 'list'])->name('stores.list');
    Route::get('admin/stores/{store}', [StoreController::class, 'show'])->name('stores.show');
    Route::delete('/admin/stores/{store}', [StoreController::class, 'destroy'])->name('stores.destroy');
    Route::get('/admin/stores/{store}/edit', [StoreController::class, 'edit'])
        ->name('stores.edit');

    Route::put('/admin/stores/{store}', [StoreController::class, 'update'])
        ->name('stores.update');

    Route::get('admin/wc/products', [WooCommerceController::class, 'products'])->name('wc.products');
    Route::get('/admin/wc/products/{id}/edit', [WooCommerceController::class, 'editProduct'])->name('wc.product.edit');
    Route::put('/admin/wc/products/{id}', [WooCommerceController::class, 'updateProduct'])->name('wc.product.update');
    Route::delete('/admin/wc/products/{id}', [WooCommerceController::class, 'deleteProduct'])->name('wc.product.delete');
    Route::get('/admin/wc/products/create', [WooCommerceController::class, 'addProduct'])->name('wc.product.create');
    Route::post('/admin/wc/products/store', [WooCommerceController::class, 'store'])->name('wc.product.store');
    Route::get('/admin/wc/product/{id}', [WooCommerceController::class, 'product'])->name('wc.product.show');
    Route::get('/admin/wc/product/{id}/print-qr', [WooCommerceController::class, 'printQr'])->name('wc.product.print-qr');

    // import product from excel file
    Route::get('admin/wc/import', [WooCommerceController::class, 'importProductsPage'])->name('wc.import_product');
    Route::post('admin/wc/import', [WooCommerceController::class, 'importProducts'])->name('wc.import');
    Route::get('/import/log/{file}', function($file){
    return Storage::download($file);
})->name('import.log');

    
    Route::get('admin/wc/total_products', [WooCommerceController::class, 'getTotalProductCount']);
    Route::get('admin/wc/get_all_products', [WooCommerceController::class, 'getAllProductsDynamic']);
    Route::get('admin/wc/get_client', [WooCommerceController::class, 'getClient']);
    
    // order
    Route::get('admin/orders', [WooCommerceController::class, 'orders'])->name('wc.orders');
    Route::get('admin/orders/complete', [WooCommerceController::class, 'completedOrders'])->name('wc.orders.complete');
    Route::get('admin/orders/pending', [WooCommerceController::class, 'pendingOrders'])->name('wc.orders.pending');
    Route::get('admin/orders/processing', [WooCommerceController::class, 'processingOrders'])->name('wc.orders.processing');
    Route::get('/admin/orders/{id}', [WooCommerceController::class, 'order'])->name('wc.order.show');
    Route::put('admin/orders/{id}/status', [WooCommerceController::class, 'updateStatus'])->name('wc.order.updateStatus');

    //Stock Management
    
    



    Route::get('admin/stock', [StockController::class, 'stock'])->name('wc.stock');
    // Route::post('/admin/stock/store', [StockController::class, 'store'])->name('wc.stock.store');
    Route::post('/admin/stock/update', [StockController::class, 'updateStock'])->name('wc.stock.update');
    
    // Store Products
    // Route::get('/products/{item}', [ProductController::class, 'show'])->name('products.show');
    // Route::resource('/products', ProductController::class);

    //customers
    Route::get('admin/customers', [WooCommerceController::class, 'customers'])->name('wc.customers');;
    Route::get('/admin/customers/{id}', [WooCommerceController::class, 'customer'])->name('wc.customer.show');

    //customers
    Route::get('admin/categories', [WooCommerceController::class, 'categories'])->name('wc.categories');;
    Route::get('/admin/categories/{id}', [WooCommerceController::class, 'category'])->name('wc.categories.show');

     //Payments
    Route::get('admin/payments', [WooCommerceController::class, 'payments'])->name('wc.payments');;
    // Route::get('/admin/payments/{id}', [WooCommerceController::class, 'payment'])->name('wc.payment.show');

    Route::get('/admin/syncAll', [WooCommerceController::class, 'syncAll'])->name('syncAll');

    Route::get('/admin/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('admin/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('admin/stocks{id}', [StockController::class, 'show'])->name('stocks.show');
    Route::get('/admin/stocks/{item}/edit', [StockController::class, 'edit'])->name('stocks.edit');
    Route::put('/admin/stocks/{item}', [StockController::class, 'update'])->name('stocks.update');
    // Stock History Export
    Route::get('/admin/stocks/{item}/history/export', [StockController::class, 'exportHistory'])
    ->name('stocks.exportHistory');
    // Stock History View
    Route::get('/stocks/{item}/history', [App\Http\Controllers\StockController::class, 'history'])
    ->name('stocks.history');



   Route::get('/stores', [StoreController::class, 'userStores'])->name('user.store');
    Route::get('admin/stores/{store}', [StoreController::class, 'show'])->name('stores.show');
    Route::get('admin/import', [ItemController::class, 'import_page'])->name('item.import_page');
    Route::post('admin/import', [ItemController::class, 'import'])->name('items.import');
   Route::resource('admin/items', ItemController::class);
   // Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
   Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
   // Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
   // Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
   // Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('admin/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{item}/print-qr', [ProductController::class, 'printQr'])->name('products.print-qr');
    Route::get('/products/{item}', [ProductController::class, 'show'])->name('products.show');
    Route::resource('/products', ProductController::class);
   // Route Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/pending', OrderPendingController::class)->name('orders.pending');
    Route::get('/orders/complete', OrderCompleteController::class)->name('orders.complete');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/update/{order}', [OrderController::class, 'update'])->name('orders.update');
Route::get('/orders/details/{order_id}/download', [OrderController::class, 'downloadInvoice'])->name('order.downloadInvoice');
   // Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
// Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    
    Route::get('/invoices/{invoice}/return', [ReturnController::class, 'create'])
    ->name('invoices.return.page');

    Route::post('/invoices/{invoice}/return', [ReturnController::class, 'store'])
    ->name('invoices.return');
       // Route Invoices
    Route::get('/admin/invoices', [InvoicesController::class, 'index'])->name('invoices.index');

    Route::get('/invoices/export', [InvoicesController::class, 'export'])->name('invoices.export');
    // Route::get('/orders/pending', OrderPendingController::class)->name('orders.pending');
    // Route::get('/orders/complete', OrderCompleteController::class)->name('orders.complete');
    Route::get('/admin/invoices/create', [InvoicesController::class, 'create'])->name('invoices.create');
    Route::post('/invoices/store', [InvoicesController::class, 'store'])->name('invoices.store');
    Route::get('/admin/invoices/{invoice}', [InvoicesController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/details/{order_id}/download', [InvoicesController::class, 'downloadInvoice'])->name('invoices.downloadInvoice');
    // Route::get('/invoices/send/{invoice}', [InvoicesController::class, 'sendInvoiceToMail'])->name('invoices.send');
    Route::get('/invoices/send/{invoice}', [InvoicesController::class, 'sendPdfEmail'])->name('invoices.send');

    // Route Purchases
    Route::get('/purchases/approved', [PurchaseController::class, 'approvedPurchases'])->name('purchases.approvedPurchases');
    Route::get('/purchases/report', [PurchaseController::class, 'dailyPurchaseReport'])->name('purchases.dailyPurchaseReport');
    Route::get('/purchases/report/export', [PurchaseController::class, 'getPurchaseReport'])->name('purchases.getPurchaseReport');
    Route::post('/purchases/report/export', [PurchaseController::class, 'exportPurchaseReport'])->name('purchases.exportPurchaseReport');

    Route::get('/admin/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/admin/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

    Route::get('/admin/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('/admin/purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::put('/admin/purchases/{purchase}/edit', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('/purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

    Route::resource('/quotations', QuotationController::class);
    Route::resource('/customers', CustomerController::class);
    Route::resource('/admin/suppliers', SupplierController::class);
    Route::post('/admin/suppliers/{supplier}/payments', [App\Http\Controllers\SupplierPaymentController::class, 'store'])->name('suppliers.payments.store');
    Route::delete('/admin/suppliers/payments/{payment}', [App\Http\Controllers\SupplierPaymentController::class, 'destroy'])->name('suppliers.payments.destroy');
    Route::resource('/categories', CategoryController::class);
    Route::resource('/units', UnitController::class);
    Route::resource('/payments', PaymentController::class);
    Route::resource('/sales', SaleController::class);

    Route::get('admin/analytics', [InvoiceAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/admin/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');
    Route::resource('/admin/expenses', App\Http\Controllers\ExpenseController::class);
    
    // Returns & Refunds
    Route::get('/admin/returns/customer', [App\Http\Controllers\ReturnPortalController::class, 'customerIndex'])->name('returns.customer.index');
    Route::resource('/admin/returns/supplier', App\Http\Controllers\SupplierReturnController::class)->names([
        'index' => 'returns.supplier.index',
        'create' => 'returns.supplier.create',
        'store' => 'returns.supplier.store',
        'show' => 'returns.supplier.show',
        'destroy' => 'returns.supplier.destroy',
    ]);
     //profile section
    

    // SEO, Sitemap & Robots Management
    Route::get('/admin/seo', [App\Http\Controllers\Admin\SeoController::class, 'index'])->name('admin.seo.index');
    Route::get('/admin/seo/robots', [App\Http\Controllers\Admin\SeoController::class, 'robots'])->name('admin.seo.robots');
    Route::post('/admin/seo/robots', [App\Http\Controllers\Admin\SeoController::class, 'updateRobots'])->name('admin.seo.robots.update');
    Route::get('/admin/seo/sitemap', [App\Http\Controllers\Admin\SeoController::class, 'sitemap'])->name('admin.seo.sitemap');
    Route::post('/admin/seo/sitemap/generate', [App\Http\Controllers\Admin\SeoController::class, 'generateSitemap'])->name('admin.seo.sitemap.generate');
    Route::post('/admin/seo', [App\Http\Controllers\Admin\SeoController::class, 'store'])->name('admin.seo.store');
    Route::get('/admin/seo/{id}/edit', [App\Http\Controllers\Admin\SeoController::class, 'edit'])->name('admin.seo.edit');
    Route::put('/admin/seo/{id}', [App\Http\Controllers\Admin\SeoController::class, 'update'])->name('admin.seo.update');
    Route::get('/admin/seo/scripts', [App\Http\Controllers\Admin\SeoController::class, 'scripts'])->name('admin.seo.scripts');
    Route::post('/admin/seo/scripts', [App\Http\Controllers\Admin\SeoController::class, 'updateScripts'])->name('admin.seo.scripts.update');
    Route::delete('/admin/seo/{id}', [App\Http\Controllers\Admin\SeoController::class, 'destroy'])->name('admin.seo.destroy');

});



Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users');
// URL to create super admin
 // Route::get('/super_admin_signup_form_url', [AdminUserController::class, 'superadmin_create']);


// Route::get('{any}.html', function () {
//     return response()->view('coming-soon', [], 404);
// })->where('any', '.*');

Route::get('{any}.html', function ($any) {
    return redirect("/$any", 301);
})->where('any', '.*');

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| Includes all dedicated frontend portal routes from the routes/frontend folder.
|
*/
require __DIR__ . '/frontend/web.php';
