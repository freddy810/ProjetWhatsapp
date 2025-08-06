<?php

use App\Http\Controllers\AppelsController;
use App\Http\Controllers\ContactsUtilisateursController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UtilisateursController;
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

Route::get('/', [UtilisateursController::class, 'loginForm'])->name('login.form');
Route::post('/login', [UtilisateursController::class, 'login'])->name('login');
Route::post('/logout', [UtilisateursController::class, 'logout'])->name('logout');

Route::resource('utilisateurs', UtilisateursController::class);
Route::resource('messages', MessagesController::class);
Route::resource('appels', AppelsController::class);
Route::resource('contactsUtilisateurs', ContactsUtilisateursController::class);
