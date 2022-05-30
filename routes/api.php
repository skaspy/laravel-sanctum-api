<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
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

// Route::apiResource('products', ProductController::class);  // uses a ressource instead of declaring all routes + php artisan route:list


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']);  // calls the ProductController and its index-method to return all products
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/search/{name}', [ProductController::class, 'search']);  // calls the ProductController and its search-method to return the product

// Protected routes - for logged in users only (just paste the route within the group to protect it)
Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::post('/products', [ProductController::class, 'store']); // calls the ProductController and its store-method to create new products
    Route::put('/products/{id}', [ProductController::class, 'update']); //updates a specific product
    Route::delete('/products/{id}', [ProductController::class, 'destroy']); // deletes a specific product
    Route::post('/logout', [AuthController::class, 'logout']);
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
