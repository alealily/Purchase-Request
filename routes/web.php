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
    Route::get('/pr-detail/export', [PRDetailController::class, 'export'])->name('pr_detail.export');
    Route::get('/pr-detail/{id}', [PRDetailController::class, 'show'])->name('pr_detail.show');
    Route::get('/pr-detail/{id}/pdf', [PRDetailController::class, 'generatePdf'])->name('pr_detail.pdf');

    
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
        // User Management (full CRUD)
        Route::prefix('user-management')->name('user_management.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::get('/export', [UserManagementController::class, 'export'])->name('export');
            Route::get('/create', [UserManagementController::class, 'create'])->name('create');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            Route::get('/{id}', [UserManagementController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserManagementController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserManagementController::class, 'destroy'])->name('destroy');
        });
        
        // Supplier Management (full CRUD)
        Route::prefix('supplier-management')->name('supplier_management.')->group(function () {
            Route::get('/', [SupplierManagementController::class, 'index'])->name('index');
            Route::get('/export', [SupplierManagementController::class, 'export'])->name('export');
            Route::get('/create', [SupplierManagementController::class, 'create'])->name('create');
            Route::post('/', [SupplierManagementController::class, 'store'])->name('store');
            Route::get('/{id}', [SupplierManagementController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SupplierManagementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SupplierManagementController::class, 'update'])->name('update');
            Route::delete('/{id}', [SupplierManagementController::class, 'destroy'])->name('destroy');
        });
    });
    
});