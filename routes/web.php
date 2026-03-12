<?php

use App\Http\Controllers\ActivitesController;
use App\Http\Controllers\CollecteController;
use App\Http\Controllers\HistoriqueController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NatureController;
use App\Http\Controllers\UserMobileController;
use App\Http\Controllers\ViolencesController;

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



// Route pour l'affichage de la page de connexion
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Routes d'authentification (Login, Register, etc.)
Auth::routes();

Route::get('/utilisateurs', [HomeController::class, 'viewusers'])->name('viewusers');
Route::post('/create-user', [HomeController::class, 'createUser'])->name('create.user');
Route::delete('/delete-user/{id}', [HomeController::class, 'deleteUser'])->name('delete.user');
Route::post('/updateuser', [HomeController::class, 'updateUser'])->name('updateuser');

// Route après connexion
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Route des Natures de cas
Route::post('/create-natures', [NatureController::class, 'createNatures'])->name('create.natures');
Route::get('/natures', [NatureController::class, 'viewnatures'])->name('view.natures');
Route::post('/updatenature', [NatureController::class, 'updateNature'])->name('update.nature');
Route::delete('/delete-natures/{id}', [NatureController::class, 'deleteNature'])->name('delete.natures');

// Route des Collecte

Route::post('/collectes', [CollecteController::class, 'store'])->name('collectes.store');
Route::get('/collectes', [CollecteController::class, 'viewcollectes'])->name('collectes');
Route::post('/update-collecte', [CollecteController::class, 'update'])->name('update.collecte');
Route::delete('/delete-collecte/{id}', [CollecteController::class, 'deleteCollecte'])->name('delete.collecte');

// Route des violences
Route::get('/addviolences', [ViolencesController::class, 'addViolences'])->name('add.violences');
Route::post('/create-violences', [ViolencesController::class, 'store'])->name('create.violences');
Route::get('/violences', [ViolencesController::class, 'viewviolences'])->name('view.violences');
Route::post('/update-violences', [ViolencesController::class, 'update'])->name('update.violences');

Route::get('/mobiles', [UserMobileController::class, 'viewmobiles'])->name('mobiles');