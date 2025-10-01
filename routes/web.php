<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\{AdminController, CategoryController, SupplierController, UserController};
use App\Http\Controllers\Backend\Auth\{LoginController, RegisterController};

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

Route::group(['prefix' => '~admin', 'middleware' => 'auth'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/getData', 'getData')->name('getData');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/filter', 'filter')->name('filter');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('master')->name('master.')->group(function () {

        Route::prefix('category')->controller(CategoryController::class)->name('category.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/getData', 'getData')->name('getData');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });

        Route::prefix('supplier')->controller(SupplierController::class)->name('supplier.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/getData', 'getData')->name('getData');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });
    });
});
