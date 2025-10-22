<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController; // NOVÉ

/*
|--------------------------------------------------------------------------
| Veřejné a studentské routy (FRONTEND)
|--------------------------------------------------------------------------
*/

// Hlavní stránka s výpisem schválených projektů
Route::get('/', [ProjectController::class, 'index'])->name('home');

// Routy pro formulář pro nahrávání projektu (veřejné)
Route::get('/submit', [ProjectController::class, 'create'])->name('projects.create');
Route::post('/submit', [ProjectController::class, 'store'])->name('projects.store');


/*
|--------------------------------------------------------------------------
| Routy pro autentizaci a uživatele (BACKEND)
|--------------------------------------------------------------------------
*/

// Routa pro dashboard (kam se uživatel dostane po přihlášení)
Route::get('/dashboard', function () {
    // Přesměrování na admin dashboard, pokud je uživatel admin.
    if (auth()->check() && auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    // Jinak zobrazí klasický dashboard (pro případ, že by se přihlásil běžný student)
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Routy pro správu profilu (dostupné jen pro přihlášené)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Routy pro Admin sekci (VYŽADUJE PŘIHLÁŠENÍ)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Admin dashboard - seznam čekajících projektů
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Akce: Schválení projektu
    Route::post('/admin/projects/{project}/approve', [AdminController::class, 'approve'])->name('admin.projects.approve');
    
    // Akce: Mazání projektu (DELETE požadavek)
    Route::delete('/admin/projects/{project}', [AdminController::class, 'destroy'])->name('admin.projects.destroy');

     Route::get('/admin/projects/{project}/edit', [AdminController::class, 'edit'])->name('admin.projects.edit');
    
    // Routa pro uložení změn (PATCH požadavek)
    Route::patch('/admin/projects/{project}', [AdminController::class, 'update'])->name('admin.projects.update');

    Route::get('/admin/projects', [AdminController::class, 'index'])->name('admin.projects.index');
});


// Routy pro login a register (vyžadované Laravelem Breeze)
require __DIR__.'/auth.php';