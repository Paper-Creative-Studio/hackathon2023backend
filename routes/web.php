<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CordsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/hello', [CordsController::class, 'hello']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/koordynaty', [CordsController::class, 'cords_display']);
    Route::post('/koordynat/{id}', [CordsController::class, 'cords_display_with_photos']);
    Route::post('/photos/{id}', [CordsController::class, 'photos_display']);
    Route::post('/userinfo', [CordsController::class, 'user_info']);
    Route::post('/feed', [CordsController::class, 'feed']);
    Route::post('/post_image', [CordsController::class, 'post_image']);
    Route::post('/like', [CordsController::class, 'like']);
    Route::post('/pinfeed/{id}', [CordsController::class, 'pinfeed']);
    Route::post('/friends', [CordsController::class, 'friends']);
    Route::post('/friends/{id}', [CordsController::class, 'friendshow']);
    Route::post('/friendfind', [CordsController::class, 'friendfind']);
});

require __DIR__.'/auth.php';