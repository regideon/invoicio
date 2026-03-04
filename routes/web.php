<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Livewire\Clients\ClientList;
use App\Livewire\Dashboard;
use App\Livewire\Products\ProductList;

Route::get('/', fn() => redirect('/login'));

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->prefix('zpanel')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/clients', ClientList::class)->name('clients.index');
    Route::get('/products', ProductList::class)->name('products.index'); 
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');



