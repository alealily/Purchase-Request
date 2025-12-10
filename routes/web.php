<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SupplierManagementController;
use App\Http\Controllers\PRDetailController;

Route::get('/', [LoginController::class, 'create'])->name('login');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/purchase-request', [PurchaseRequestController::class, 'index'])->name('purchase_request.index');
Route::get('/purchase-request/export', [PurchaseRequestController::class, 'export'])->name('purchase_request.export');
Route::get('/pr-detail', [PRDetailController::class, 'index'])->name('pr_detail.index');
Route::get('/user-management', [UserManagementController::class, 'index'])->name('user_management.index');
Route::get('/supplier-management', [SupplierManagementController::class, 'index'])->name('supplier_management.index');