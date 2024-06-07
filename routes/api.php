<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authorized\Facebook\AdAccountsController;
use App\Http\Controllers\Authorized\Facebook\AdInsightsController;
use App\Http\Controllers\Sync\GoogleController;

Route::middleware(['auth:sanctum'])->group(function () {
    
});

Route::resource('/facebook/accounts', AdAccountsController::class);
Route::post('/facebook/insights', [AdInsightsController::class, 'index']);
Route::post('/sync', [GoogleController::class, 'index']);


require __DIR__.'/auth.php';