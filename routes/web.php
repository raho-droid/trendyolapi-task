<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/save-products', [ProductController::class, 'saveProducts']);
Route::get('/get-products', [ProductController::class, 'getProducts']);