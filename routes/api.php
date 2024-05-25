<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authorized\Facebook\AdAccountsController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('/authorized/accounts', AdAccountsController::class);
});


require __DIR__.'/auth.php';