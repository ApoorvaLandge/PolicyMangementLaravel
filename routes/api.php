<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PolicyController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:api')->group(function(){
    Route::apiResource('policies',PolicyController::class);
    Route::apiResource('categories',CategoryController::class);
    Route::apiResource('groups',GroupController::class);
    Route::post('setcategories/{id}',[PolicyController::class,'setCategories']);
    Route::post('addMember/{id}',[GroupController::class,'addMember']);
    Route::post('setGroups/{id}',[PolicyController::class,'assignGroups']);
    Route::get('users',[AuthController::class,'showAllUsers']);
   // Route::post('logout',[AuthController::class,'logout']);

});


Route::post('login',[AuthController::class,'login']);

//Route::post('register',[AuthController::class,'register']);

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', [AuthController::class,'register']);
    // Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

});