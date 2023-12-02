<?php

use Illuminate\Http\Request;
use App\Http\Controllers\CordsController;
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

Route::middleware('auth')->group(function () {

});

Route::Delete('/friendfind/{friendname}', [CordsController::class, 'frienddelete']);
Route::get('/hello', [CordsController::class, 'hello']);
Route::post('/koordynaty', [CordsController::class, 'cords_display']);
Route::post('/koordynat/{id}', [CordsController::class, 'cords_display_with_photos']);
Route::post('/photos/{id}', [CordsController::class, 'photos_display']);
Route::get('/userinfo', [CordsController::class, 'user_info_get']);
Route::post('/userinfo', [CordsController::class, 'user_info']);
Route::post('/feed', [CordsController::class, 'feed']);
Route::post('/post_image', [CordsController::class, 'post_image']);
Route::post('/like', [CordsController::class, 'like']);
Route::post('/pinfeed/{id}', [CordsController::class, 'pinfeed']);
Route::post('/friends', [CordsController::class, 'friends']);
Route::post('/friends/{id}', [CordsController::class, 'friendshow']);
Route::post('/friendfind', [CordsController::class, 'friendfind']);