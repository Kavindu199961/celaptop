<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaptopRepairController;
use App\Http\Controllers\CompleteRepairController;
use App\Http\Controllers\RepairTrackingController;
use App\Http\Controllers\StockController;


// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Laptop Repair Routes
    Route::resource('laptop-repair', LaptopRepairController::class)->except(['show']);
    
    // Additional Laptop Repair Routes
    Route::patch('laptop-repair/{id}/status', [LaptopRepairController::class, 'updateStatus'])
         ->name('laptop-repair.update-status');
         
    Route::patch('laptop-repair/{id}/price', [LaptopRepairController::class, 'updatePrice'])
         ->name('laptop-repair.update-price');
         
    Route::get('laptop-repair-stats', [LaptopRepairController::class, 'getStats'])
         ->name('laptop-repair.stats');

    Route::patch('laptop-repair/{id}/complete', [LaptopRepairController::class, 'completeRepair'])
         ->name('laptop-repair.complete');

    Route::get('get-note-number', [LaptopRepairController::class, 'getNextNoteNumber'])->name('laptop-repair.get-note-number');


  
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
 

});

   Route::get('/repair-tracking', [RepairTrackingController::class, 'index'])->name('web.repair-tracking.index');

   

