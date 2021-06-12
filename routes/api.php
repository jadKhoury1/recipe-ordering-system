<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BoxController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\IngredientController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/ingredient', [IngredientController::class, 'add']);
Route::get('/ingredients', [IngredientController::class, 'get']);
Route::get('/requiredIngredients', [IngredientController::class, 'getRequired']);

Route::post('/recipe', [RecipeController::class, 'add']);
Route::get('/recipes', [RecipeController::class, 'get']);

Route::post('/box', [BoxController::class, 'add']);


