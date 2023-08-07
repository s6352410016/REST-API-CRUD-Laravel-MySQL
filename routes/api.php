<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/getAllProduct' , [ProductController::class , 'index']);
Route::get('/getProductById/{id}' , [ProductController::class , 'show']);
Route::post('/createProduct' , [ProductController::class , 'store']);
Route::put('/updateProductById/{id}' , [ProductController::class , 'update']);
Route::delete('/deleteProductById/{id}' , [ProductController::class , 'delete']);