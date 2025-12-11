<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SupplierManagementController;
use App\Http\Controllers\PRDetailController;

// Authentication Routes
Route::get('/', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Purchase Request Routes
    Route::prefix('purchase-request')->name('purchase_request.')->group(function () {
        // List & View (All roles)
        Route::get('/', [PurchaseRequestController::class, 'index'])->name('index');
        Route::get('/{id}', [PurchaseRequestController::class, 'show'])->name('show');
        
        // Create & Edit (Employee & IT only)
        Route::get('/create', [PurchaseRequestController::class, 'create'])->name('create');
        Route::post('/', [PurchaseRequestController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PurchaseRequestController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PurchaseRequestController::class, 'update'])->name('update');
        Route::delete('/{id}', [PurchaseRequestController::class, 'destroy'])->name('destroy');
        
        // Approval Actions (Superior only)
        Route::post('/{id}/approve', [PurchaseRequestController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [PurchaseRequestController::class, 'reject'])->name('reject');
        Route::post('/{id}/revision', [PurchaseRequestController::class, 'revision'])->name('revision');
        
        // Export (All roles)
        Route::get('/export', [PurchaseRequestController::class, 'export'])->name('export');
    });
    
    // PR Detail
    Route::get('/pr-detail', [PRDetailController::class, 'index'])->name('pr_detail.index');
    
    // User Management (IT only)
    Route::get('/user-management', [UserManagementController::class, 'index'])->name('user_management.index');
    
    // Supplier Management (IT only)
    Route::get('/supplier-management', [SupplierManagementController::class, 'index'])->name('supplier_management.index');
    
});