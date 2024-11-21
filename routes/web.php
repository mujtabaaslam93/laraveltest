<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index']);
Route::post('/store', [ProductController::class, 'store']);
Route::post('/edit', [ProductController::class, 'edit']);
Route::post('/update', [ProductController::class, 'update']);
