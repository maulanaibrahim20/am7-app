<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\{
    AdminController,
    BookingController,
    CategoryController,
    CustomerController,
    DashboardController,
    InventoryController,
    MechanicController,
    ProductController,
    PurchaseOrderController,
    SaleController,
    ServiceController,
    SettingController,
    StaffController,
    SupplierController,
    TransactionController,
    UserCashierController,
    UserController
};
use App\Http\Controllers\Backend\Auth\{
    LoginController,
    LogoutController,
    RegisterController
};
use App\Http\Controllers\CashierController;
use App\Http\Controllers\Frontend\LandingPageController;

Route::middleware('guest')->group(function () {
    Route::prefix('/')->name('landing.')->controller(LandingPageController::class)->group(function () {
        Route::get('/', 'home')->name('home');
        Route::get('/booking', 'booking')->name('booking.create');
        Route::post('/booking/store', 'bookingStore')->name('booking.store');
        Route::get('/about', 'about')->name('about');
        Route::get('/services', 'services')->name('services');
        Route::get('/contact', 'contact')->name('contact');
    });

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});


Route::group(['prefix' => '~admin', 'middleware' => 'auth'], function () {
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier');
    Route::get('/cashier/detail/{id}/{type}', [CashierController::class, 'show'])->name('cashier.show');
    Route::get('/cashier/getSelect-customer', [CashierController::class, 'getSelectCustomer'])->name('cashier.getSelect-customer');
    Route::post('/cashier/select-customer', [CashierController::class, 'selectCustomer'])->name('cashier.select-customer');

    Route::post('/cashier/add-to-cart', [CashierController::class, 'addToCart'])->name('cashier.addToCart');
    Route::get('/cashier/get-cart', [CashierController::class, 'viewCart'])->name('cashier.getCart');
    Route::post('/cashier/update-cart/{id}', [CashierController::class, 'updateCartItem'])->name('cashier.updateCart');
    Route::post('/cashier/delete-item/{id}', [CashierController::class, 'removeCartItem'])->name('cashier.deleteCart');

    Route::get('/cashier/hold', [CashierController::class, 'holdModal'])->name('cashier.holdModal');
    Route::post('/cashier/hold-cart', [CashierController::class, 'holdCart'])->name('cashier.hold-cart');

    Route::get('/cashier/held-cart', [CashierController::class, 'heldCarts'])->name('cashier.held-carts');
    Route::post('/cashier/resume-cart/{session_code}', [CashierController::class, 'resumeCart'])->name('cashier.resume-cart');
    Route::post('/cashier/cancel-cart/{session_code}', [CashierController::class, 'cancelCart'])->name('cashier.cancel-cart');

    Route::get('/cashier/checkout', [CashierController::class, 'checkout'])->name('cashier.checkout');
    Route::post('/cashier/checkout', [CashierController::class, 'processPayment'])->name('cashier.processPayment');
    Route::get('/cashier/checkout/invoice/{id}', [CashierController::class, 'invoice'])->name('cashier.invoice');
    Route::get('/cashier/pring/invoice/{id}', [CashierController::class, 'printInvoice'])->name('cashier.printInvoice');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('user')->name('user.')->group(function () {
        Route::prefix('admin')->name('admin.')->controller(AdminController::class)->group(function () {
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
        Route::prefix('staff')->name('staff.')->controller(StaffController::class)->group(function () {
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
        Route::prefix('cashier')->name('cashier.')->controller(UserCashierController::class)->group(function () {
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
        Route::prefix('mechanic')->name('mechanic.')->controller(MechanicController::class)->group(function () {
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
    });

    Route::prefix('product')->controller(ProductController::class)->name('product.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/contoh', 'contoh')->name('contoh');
        Route::get('/getData', 'getData')->name('getData');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::post('/addSupplier/{id}', 'addSupplier')->name('addSupplier');
    });

    Route::prefix('service')->controller(ServiceController::class)->name('service.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/getData', 'getData')->name('getData');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('customer')->controller(CustomerController::class)->name('customer.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/getData', 'getData')->name('getData');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('booking')->controller(BookingController::class)->name('booking.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/getData', 'getData')->name('getData');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/updateStatus/{id}', 'updateStatus')->name('updateStatus');
        Route::put('/updateNote/{id}', 'notes')->name('updateNote');
        Route::post('/loadPaymentFromBooking/{id}', 'loadFromBooking')->name('loadFromBooking');
        Route::get('/filter', 'filter')->name('filter');
    });

    Route::prefix('sale')->controller(SaleController::class)->name('sale.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/getData', 'getData')->name('getData');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/filter', 'filter')->name('filter');
    });

    Route::prefix('purchase-order')->controller(PurchaseOrderController::class)->name('purchase-order.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/getData', 'getData')->name('getData');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/receive/{id}', 'receive')->name('receive');
        Route::post('/receive/{id}', 'receiveSubmit')->name('receiveSubmit');
        Route::put('/updateStatus/{id}', 'updateStatus')->name('updateStatus');
        Route::get('/filter', 'filter')->name('filter');
    });

    Route::prefix('inventory')->controller(InventoryController::class)->name('inventory.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/getData', 'getData')->name('getData');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/resolve/{id}', 'resolve')->name('resolve');
    });

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::prefix('endpoint-cront-task')->controller(SettingController::class)->name('endpoint-cront-task.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/getData', 'getData')->name('getData');
        });
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

    Route::get('/logout', LogoutController::class)->name('logout');
});


require_once('cront_taks.php');
