<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaptopRepairController;
use App\Http\Controllers\CompleteRepairController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RepairTrackingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\MyShopController;
use App\Http\Controllers\InvoiceWithStockController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\TotalAmountController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShopNameController;
use App\Http\Controllers\CompleteShopRepairController;
use App\Http\Controllers\CreditInvoiceController;
use App\Http\Controllers\EmailSettingController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\CreditShopController;

Route::get('/', [RepairTrackingController::class, 'index'])->name('web.repair-tracking.index');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('super-admin')->middleware('auth')->group(function () {
    Route::get('/users', [AdminController::class, 'dashboard'])->name('super-admin.users.index');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('super-admin.users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser']); // Add this line
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('super-admin.toggle-status');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('super-admin.users.update');
    Route::delete('/users/{user}/delete', [AdminController::class, 'deleteUser'])->name('delete-user');


    Route::get('/payments', [PaymentController::class, 'index'])->name('super-admin.payments.index');
    Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('super-admin.payments.approve');
    Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('super-admin.payments.reject');



});
// Auth routes (including password reset)

// Admin routes
Route::middleware(['user'])->prefix('user')->name('user.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Laptop Repair Routes


    Route::prefix('laptop-repair')->middleware('auth')->name('laptop-repair.')->group(function () {
  
    Route::resource('', LaptopRepairController::class)->except(['show']);

    // Additional Laptop Repair Routes
    Route::patch('{id}/status', [LaptopRepairController::class, 'updateStatus'])->name('update-status');   
    Route::patch('{id}/price', [LaptopRepairController::class, 'updatePrice'])->name('update-price');  
    Route::get('stats', [LaptopRepairController::class, 'getStats'])->name('stats');
    Route::patch('{id}/complete', [LaptopRepairController::class, 'completeRepair'])->name('complete');
    Route::get('get-note-number', [LaptopRepairController::class, 'getNextNoteNumber'])->name('get-note-number');
    Route::get('repairs/{repair}', [LaptopRepairController::class, 'show'])->name('show');
    Route::delete('repairs/{repair}', [LaptopRepairController::class, 'destroy'])->name('destroy');
    Route::get('{id}/edit', [LaptopRepairController::class, 'edit'])->name('edit');
    Route::put('{id}', [LaptopRepairController::class, 'update'])->name('update');
});



  
   Route::resource('complete-repair', CompleteRepairController::class)->except(['show']);
    
   Route::prefix('stock')->name('stock.')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('index');
    Route::post('/', [StockController::class, 'store'])->name('store');
    Route::get('/create', [StockController::class, 'create'])->name('create');
    Route::get('/{stock}', [StockController::class, 'show'])->name('show');
    Route::get('/{stock}/edit', [StockController::class, 'edit'])->name('edit');
    Route::put('/{stock}', [StockController::class, 'update'])->name('update');
    Route::delete('/{stock}', [StockController::class, 'destroy'])->name('destroy');
    
    // Vendor routes
    Route::get('/vendor/{vendor}', [StockController::class, 'vendorShow'])->name('vendor.show');
});

Route::prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index');
    Route::post('/', [InvoiceController::class, 'store'])->name('store');
    Route::get('/create', [InvoiceController::class, 'create'])->name('create');
    Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
    Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
    Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
    Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
    
    // Additional invoice-specific routes
    Route::get('/{invoice}/print', [InvoiceController::class, 'print'])->name('print');
    Route::get('/{invoice}/download', [InvoiceController::class, 'download'])->name('download');
    
    // Customer routes (similar to your vendor routes)
    Route::get('/customer/{customer}', [InvoiceController::class, 'customerShow'])->name('customer.show');
});

Route::prefix('estimates')->name('estimates.')->group(function () {
    Route::get('/', [EstimateController::class, 'index'])->name('index');
    Route::post('/', [EstimateController::class, 'store'])->name('store');
    Route::get('/create', [EstimateController::class, 'create'])->name('create');
    Route::get('/{estimate}', [EstimateController::class, 'show'])->name('show');
    Route::get('/{estimate}/edit', [EstimateController::class, 'edit'])->name('edit');
    Route::put('/{estimate}', [EstimateController::class, 'update'])->name('update');
    Route::delete('/{estimate}', [EstimateController::class, 'destroy'])->name('destroy');
    
    // Additional invoice-specific routes
    Route::get('/{estimate}/print', [EstimateController::class, 'print'])->name('print');
    Route::get('/{estimate}/download', [EstimateController::class, 'download'])->name('download');
    
    // Customer routes (similar to your vendor routes)
    Route::get('/customer/{customer}', [EstimateController::class, 'customerShow'])->name('customer.show');
});

Route::prefix('invoices-with-stock')->name('invoices_with_stock.')->group(function () {
    Route::get('/', [InvoiceWithStockController::class, 'index'])->name('index');
    Route::post('/', [InvoiceWithStockController::class, 'store'])->name('store');
    Route::get('/{invoice_with_stock}', [InvoiceWithStockController::class, 'show'])->name('show');
    Route::delete('/{invoice_with_stock}', [InvoiceWithStockController::class, 'destroy'])->name('destroy');
    
    // Print/download routes
    Route::get('/{invoice_with_stock}/print', [InvoiceWithStockController::class, 'print'])->name('print');
    Route::get('/{invoice_with_stock}/download', [InvoiceWithStockController::class, 'download'])->name('download');

    // Search routes
    Route::get('/search/products', [InvoiceWithStockController::class, 'searchStock'])->name('products.search');
    Route::get('/get/product/{id}', [InvoiceWithStockController::class, 'getProduct'])->name('product.get');
});


Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::post('/', [ShopController::class, 'store'])->name('store');
    Route::get('/{shop}/edit', [ShopController::class, 'edit'])->name('edit');
    Route::put('/{shop}', [ShopController::class, 'update'])->name('update');
    Route::delete('/{shop}', [ShopController::class, 'destroy'])->name('destroy');
     Route::get('/{shop}', [ShopController::class, 'show'])->name('show');
});

Route::prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::post('/', [AccountController::class, 'store'])->name('store');
    Route::get('/{id}', [AccountController::class, 'show'])->name('show');
    Route::put('/{id}', [AccountController::class, 'update'])->name('update');
    Route::delete('/{id}', [AccountController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/edit', [AccountController::class, 'edit'])->name('edit');
    
    // Optional routes for additional features
    Route::get('/summary/data', [AccountController::class, 'summary'])->name('summary');
    Route::get('/export/pdf', [AccountController::class, 'exportPdf'])->name('export.pdf');
});

Route::prefix('user/shop_names')->name('shop_names.')->group(function () {
        Route::get('/', [ShopNameController::class, 'index'])->name('index');
        Route::post('/', [ShopNameController::class, 'store'])->name('store');
        Route::get('/{shop}/edit', [ShopNameController::class, 'edit'])->name('edit');
        Route::put('/{shop}', [ShopNameController::class, 'update'])->name('update');
        Route::delete('/{shop}', [ShopNameController::class, 'destroy'])->name('destroy');
        
        // Repair Items Routes
        Route::prefix('{shop}/repair-items')->name('repair_items.')->group(function () {
            Route::get('/', [ShopNameController::class, 'showRepairItems'])->name('index');
            Route::post('/', [ShopNameController::class, 'storeRepairItems'])->name('store');
            Route::post('/showpage', [ShopNameController::class, 'storeRepairItemsshowpage'])->name('showpage.store');
            Route::get('/{repairItem}/edit', [ShopNameController::class, 'editRepairItem'])->name('edit');
            Route::put('/{repairItem}', [ShopNameController::class, 'updateRepairItem'])->name('update');
            Route::put('/{repairItem}/status', [ShopNameController::class, 'updateStatus'])->name('update_status');
            Route::delete('/{repairItem}', [ShopNameController::class, 'destroyRepairItem'])->name('destroy');
        });
    });


Route::prefix('credit_shop')->name('credit_shop.')->group(function () {
    // Basic CRUD routes
    Route::get('/', [CreditShopController::class, 'index'])->name('index');
    Route::post('/', [CreditShopController::class, 'store'])->name('store');
    
    // Standard resource routes (keep these after specific routes)
    Route::get('/{creditShop}/edit', [CreditShopController::class, 'edit'])->name('edit');
    Route::put('/{creditShop}', [CreditShopController::class, 'update'])->name('update');
    Route::delete('/{creditShop}', [CreditShopController::class, 'destroy'])->name('destroy');
    Route::get('/{creditShop}', [CreditShopController::class, 'show'])->name('show');
});

// routes/web.php
Route::prefix('credit_invoices')->name('credit_invoices.')->group(function () {
    Route::get('/', [CreditInvoiceController::class, 'index'])->name('index');
    Route::post('/', [CreditInvoiceController::class, 'store'])->name('store');
    Route::get('/{creditInvoice}', [CreditInvoiceController::class, 'show'])->name('show');
    Route::get('/{creditInvoice}/print', [CreditInvoiceController::class, 'print'])->name('print');
    Route::get('/{creditInvoice}/download', [CreditInvoiceController::class, 'download'])->name('download');
    Route::delete('/{creditInvoice}', [CreditInvoiceController::class, 'destroy'])->name('destroy');
});

Route::prefix('shop_completed_repair')->name('shop_completed_repair.')->group(function () {
        Route::get('/', [CompleteShopRepairController::class, 'index'])->name('index');
        Route::get('/{repair}/edit', [CompleteShopRepairController::class, 'edit'])->name('edit');
        Route::put('/{repair}', [CompleteShopRepairController::class, 'update'])->name('update');
        Route::delete('/{repair}', [CompleteShopRepairController::class, 'destroy'])->name('destroy');
    });



Route::prefix('myshop')->name('myshop.')->group(function () {
    Route::get('/', [MyShopController::class, 'index'])->name('index');
    Route::post('/', [MyShopController::class, 'store'])->name('store');
    Route::get('/{id}', [MyShopController::class, 'show'])->name('show'); 
    Route::get('/create', [MyShopController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [MyShopController::class, 'edit'])->name('edit');
    Route::put('/{id}', [MyShopController::class, 'update'])->name('update');
    Route::delete('/{myshop}', [MyShopController::class, 'destroy'])->name('destroy');
});


Route::prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/', [CashierController::class, 'index'])->name('index');
    Route::post('/', [CashierController::class, 'store'])->name('store');
    Route::get('/create', [CashierController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [CashierController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CashierController::class, 'update'])->name('update');
    Route::delete('/{id}', [CashierController::class, 'destroy'])->name('destroy');

});
Route::prefix('user')->middleware('auth')->group(function () {
    // Email Settings
    Route::get('/email-settings', [EmailSettingController::class, 'index'])
        ->name('email-settings.index');
    Route::put('/email-settings', [EmailSettingController::class, 'update'])
        ->name('email-settings.update');
});

Route::get('/total-amount', [TotalAmountController::class, 'index'])->name('total_amount.index');


});

  

   

