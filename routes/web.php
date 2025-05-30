<?php
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\DrugSearch;
use App\Http\Livewire\Profile;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ExportController;

Route::get('/export-drugs', [ExportController::class, 'exportUserDrugs'])->name('export.drugs')->middleware('auth');

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('guest');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    // Define the route for profile using Livewire
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/drug-search', DrugSearch::class)->name('drug-search');
});

require __DIR__.'/auth.php';