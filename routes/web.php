<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransactionController;

Route::redirect('/', '/transactions');

Route::resource('products', ProductController::class);
Route::resource('customers', CustomerController::class);
Route::resource('transactions', TransactionController::class);
