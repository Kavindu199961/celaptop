<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaptopRepairController;
use App\Http\Controllers\CompleteRepairController;


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

  
    Route::resource('complete-repair', CompleteRepairController::class)->except(['show']);  

});

   

