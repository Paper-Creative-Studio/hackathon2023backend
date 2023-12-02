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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/koordynaty', [CordsController::class, 'cords_display']);
    Route::get('/koordynat/{id}', [CordsController::class, 'cords_display_with_photos']);
    Route::get('/photos/{id}', [CordsController::class, 'photos_display']);
    Route::get('/userinfo', [CordsController::class, 'user_info']);
    Route::get('/feed', [CordsController::class, 'feed']);
    Route::post('/post_image', [CordsController::class, 'post_image']);
    Route::post('/like', [CordsController::class, 'like']);
    Route::get('/pinfeed/{id}', [CordsController::class, 'pinfeed']);
});

require __DIR__.'/auth.php';