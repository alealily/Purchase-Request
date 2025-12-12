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
    
    // ===== ALL ROLES CAN ACCESS =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Purchase Request - View & Export (All roles)
    Route::prefix('purchase-request')->name('purchase_request.')->group(function () {
        Route::get('/', [PurchaseRequestController::class, 'index'])->name('index');
        Route::get('/export', [PurchaseRequestController::class, 'export'])->name('export');
        // IMPORTANT: /create must come BEFORE /{id} to prevent conflict
        Route::get('/create', [PurchaseRequestController::class, 'create'])->name('create')->middleware('role:employee,it');
        Route::post('/', [PurchaseRequestController::class, 'store'])->name('store')->middleware('role:employee,it');
        Route::get('/{id}/edit', [PurchaseRequestController::class, 'edit'])->name('edit')->middleware('role:employee,it');
        Route::put('/{id}', [PurchaseRequestController::class, 'update'])->name('update')->middleware('role:employee,it');
        Route::delete('/{id}', [PurchaseRequestController::class, 'destroy'])->name('destroy')->middleware('role:employee,it');
        Route::get('/{id}', [PurchaseRequestController::class, 'show'])->name('show');
    });
    
    // PR Detail (All roles)
    Route::get('/pr-detail', [PRDetailController::class, 'index'])->name('pr_detail.index');
    
    // ===== SUPERIOR ONLY =====
    Route::middleware(['role:superior'])->group(function () {
        Route::prefix('purchase-request')->name('purchase_request.')->group(function () {
            Route::post('/{id}/approve', [PurchaseRequestController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [PurchaseRequestController::class, 'reject'])->name('reject');
            Route::post('/{id}/revision', [PurchaseRequestController::class, 'revision'])->name('revision');
        });
    });
    
    // ===== IT ONLY =====
    Route::middleware(['role:it'])->group(function () {
        Route::get('/user-management', [UserManagementController::class, 'index'])->name('user_management.index');
        Route::get('/supplier-management', [SupplierManagementController::class, 'index'])->name('supplier_management.index');
    });
    
});