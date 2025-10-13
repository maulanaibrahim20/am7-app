<?php

use App\Http\Controllers\Cron\InventoryController;
use App\Http\Controllers\Crontask\WalletCronController;
use Illuminate\Support\Facades\Route;

Route::prefix('cron')->group(function () {
    Route::get('/check-inventory-alerts', [InventoryController::class, 'checkInventoryAlerts']);
});
