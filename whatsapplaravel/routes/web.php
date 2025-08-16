<?php

use App\Http\Controllers\AppelsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\utile\UtileMessageController;
use App\Http\Controllers\utile\UtileUtilisateurController;
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

//Route::resource('utilisateurs', UtilisateursController::class);
//Route::resource('messages', MessagesController::class);
//Route::resource('appels', AppelsController::class);


//------------------------Les routes spécifiques-------------------------------------------------------------------------------------------------------
Route::get('/', [UtileUtilisateurController::class, 'loginForm'])->name('login.form');
Route::post('/login', [UtileUtilisateurController::class, 'login'])->name('login');
Route::post('/register', [UtileUtilisateurController::class, 'register'])->name('utilisateur.register');
Route::post('/changer-mot-de-passe', [UtileUtilisateurController::class, 'changerMotDePasse'])->name('utilisateur.changerMotDePasse');
Route::post('/logout', [UtileUtilisateurController::class, 'logout'])->name('logout');

//utilisé dans la fonction "login" (UtileUtilisateurController), dans la vue 'test.test', et dans la fonction "getMessagesIdsByUtilisateur"
Route::get('/utilisation/{id}', [UtileMessageController::class, 'getMessagesIdsByUtilisateur'])->name('affichage.liste');

//utilisé dans la fonction "getMessagesDetailsBetweenUsers" (UtileMessageController)
Route::get('/message/{idConnecter}/{id1}/{id2}', [UtileMessageController::class, 'getMessagesDetailsBetweenUsers'])->name('message.message');

//Pour l'ajout de message
Route::post('/messages', [UtileMessageController::class, 'store'])->name('message.enregistrement');

//Pour la modification de message
Route::put('/messages/{id}', [UtileMessageController::class, 'update'])->name('message.modification');

//Pour la suppression de message
Route::delete('/messages/{id}', [UtileMessageController::class, 'destroy'])->name('message.destroy');

//Pour le nouveau message
Route::post('/nouvelle-discussion', [UtileMessageController::class, 'nouvelleDiscussionAvecUtilisateur'])->name('nouvelle.discussion');

//Utiliser Pour la modification des infos de l'utilisateur connecter
Route::put('/utilisateurs/{utilisateur}', [UtileUtilisateurController::class, 'update'])->name('utilisateur.update');

//Pour l'affichage de tous les utilisateurs (pour l'admin)
Route::get('/utile/listeUilisateurs/{id}', [UtileUtilisateurController::class, 'afficherListeUtilisateurs'])->name('affichage.listeAdmin');

//Pour l'ajout de nouveau utilisateur
Route::post('/utilisateurs', [UtileUtilisateurController::class, 'store'])->name('utilisateur.store');

//Pour la suppression du message
Route::delete('/utilisateurs/{utilisateur}', [UtileUtilisateurController::class, 'destroy'])->name('utilisateurs.destroy');
