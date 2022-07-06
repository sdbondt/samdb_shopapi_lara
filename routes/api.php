<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [AuthController::class, 'index']);
    Route::get('/yeet', function(){
        return 'yeet';
    });
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::delete('/cart', [CartController::class, 'removeAllFromCart']);

    Route::post('/products', [ProductController::class, 'store']);
    Route::post('/products/{product:slug}/add', [CartController::class, 'addToCart']);
    Route::post('/products/{product:slug}/remove', [CartController::class, 'removeFromCart']);
    Route::patch('/products/{product:slug}/update', [CartController::class, 'updateCartItem']);
    
    Route::patch('/products/{product:slug}', [ProductController::class, 'update']);
    Route::delete('/products/{product:slug}', [ProductController::class, 'destroy']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product:slug}', [ProductController::class, 'show']);
});

Route::group(['middleware' => 'guest'], function() {
    Route::post('/signup', [AuthController::class, 'signup'])->middleware('guest');
    Route::post('login', [AuthController::class, 'login'])->middleware('guest');
});



