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


// Route pour l'affichage de la page de connexion
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Routes d'authentification (Login, Register, etc.)
Auth::routes();

//Route utilisateurs 
Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/utilisateurs', [HomeController::class, 'viewusers'])->name('viewusers')->middleware('auth');
Route::post('/create-user', [HomeController::class, 'createUser'])->name('create.user')->middleware('auth');
Route::delete('/delete-user/{id}', [HomeController::class, 'deleteUser'])->name('delete.user')->middleware('auth');
Route::post('/updateuser', [HomeController::class, 'updateUser'])->name('updateuser')->middleware('auth');
Route::delete('/users/{id}', [HomeController::class, 'destroy'])->name('users.destroy')->middleware('auth');
Route::get('/mobiles', [UserMobileController::class, 'viewmobiles'])->name('mobiles')->middleware('auth');
Route::post('/users/{id}/activate', [UserMobileController::class, 'activate'])->middleware('auth');
Route::post('/users/{id}/deactivate', [UserMobileController::class, 'deactivate'])->middleware('auth');

// Route des Natures de cas
Route::post('/create-natures', [NatureController::class, 'createNatures'])->name('create.natures')->middleware('auth');
Route::get('/natures', [NatureController::class, 'viewnatures'])->name('view.natures')->middleware('auth');
Route::post('/updatenature', [NatureController::class, 'updateNature'])->name('update.nature')->middleware('auth');
Route::delete('/delete-natures/{id}', [NatureController::class, 'deleteNature'])->name('delete.natures')->middleware('auth')    ;

// Route des Collecte

Route::post('/collectes', [CollecteController::class, 'store'])->name('collectes.store')->middleware('auth');
Route::get('/collectes', [CollecteController::class, 'viewcollectes'])->name('view.collectes')->middleware('auth');
Route::post('/update-collecte', [CollecteController::class, 'update'])->name('update.collecte')->middleware('auth');
Route::delete('/delete-collecte/{id}', [CollecteController::class, 'deleteCollecte'])->name('delete.collecte')->middleware('auth');

// Groupe de routes pour les Violences
Route::get('/violences', [ViolencesController::class, 'viewviolences'])->name('view.violences')->middleware('auth');
Route::get('/addviolences', [ViolencesController::class, 'addViolences'])->name('add.violences')->middleware('auth');
Route::post('/store', [ViolencesController::class, 'store'])->name('create.violences')->middleware('auth');
Route::get('/edit/{id}', [ViolencesController::class, 'edit'])->name('edit.violences')->middleware('auth');
Route::put('/update/{id}', [ViolencesController::class, 'update'])->name('update.violences')->middleware('auth');
Route::delete('/delete/{id}', [ViolencesController::class, 'destroy'])->name('delete.violences')->middleware('auth');
Route::get('/export-pdf', [ViolencesController::class, 'exportPDF'])->name('export.violences.pdf')->middleware('auth');
Route::get('/export-excel', [ViolencesController::class, 'exportExcel'])->name('export.violences.excel')->middleware('auth');
Route::get('/export-csv', [ViolencesController::class, 'exportCSV'])->name('export.violences.csv')->middleware('auth');
Route::post('/violences/{id}/toggle-permis', [ViolencesController::class, 'togglePermis'])
    ->name('violences.togglePermis')->middleware('auth');

// Route pour l'historique
Route::get('/historiques', [HistoriqueController::class, 'viewhistorique'])->name('historique')->middleware('auth');

// Route pour les activités
Route::get('/activites', [ActivitesController::class, 'viewactivites'])->name('viewactivites')->middleware('auth');
Route::delete('/activite/{id}', [ActivitesController::class, 'destroy'])->name('delete.activite')->middleware('auth');
Route::delete('/activites/clear', [ActivitesController::class, 'clearMyHistory'])->name('clear.activites')->middleware('auth');


//Route de traduction de la langue
Route::get('/lang/{locale}', function ($locale) {
    if (!in_array($locale, ['fr', 'en'])) {
        abort(400);
    }

    session(['locale' => $locale]); 
    app()->setLocale($locale);     
    return redirect()->back();     
})->name('lang.switch')->middleware('auth');