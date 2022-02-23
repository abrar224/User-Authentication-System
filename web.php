<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\PostController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
    return redirect('login');
});

Route::get('/login', [CustomAuthController::class, 'login'])->middleware('loggedin');
Route::get('/registration', [CustomAuthController::class, 'registration'])->middleware('loggedin');
Route::post('/register-user',[CustomAuthController::class, 'registerUser'])->name('register-user');
Route::post('/login-user',[CustomAuthController::class, 'loginUser'])->name('login-user');
Route::get('/homepage',[CustomAuthController::class, 'homepage'])->middleware('islogin');
Route::get('/logout',[CustomAuthController::class, 'logout']);

Route::resource('posts', PostController::class)->middleware('islogin');