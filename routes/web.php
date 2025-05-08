<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AuthAdmin;



Auth::routes();
Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index'); 
});

Route::middleware(['auth',AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index'); 
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brand/create', [AdminController::class, 'create_Brand'])->name('admin.brand.create');
    Route::post('/admin/brand/store', [AdminController::class, 'store_Brand'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}', [AdminController::class, 'edit_Brand'])->name('admin.brand.edit');	
    Route::post('/admin/brand/update', [AdminController::class, 'update_Brand'])->name('admin.brand.update');
    Route::delete('/admin/brand/{id}/delete', [AdminController::class, 'delete_Brand'])->name('admin.brand.delete');
});