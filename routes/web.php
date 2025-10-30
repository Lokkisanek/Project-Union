<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController; 

/*
|--------------------------------------------------------------------------
| Veřejné a studentské routy (FRONTEND)
|--------------------------------------------------------------------------
*/

// 1. ÚVODNÍ/VSTUPNÍ STRÁNKA (Hlavní stránka s carouselom a vyhledáváním)
Route::get('/', [ProjectController::class, 'home'])->name('home'); 

// 2. DETAIL PROJEKTU (Steam-like detail)
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

// 2a. STRÁNKA KATEGORIE - seznam projektů pro danou kategorii
Route::get('/categories/{category}', [ProjectController::class, 'category'])->name('categories.show');

// 3. Kontaktní stránka
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');

// 4. Přidání projektu (formulář)
Route::get('/submit', [ProjectController::class, 'create'])->name('projects.create');
Route::post('/submit', [ProjectController::class, 'store'])->name('projects.store');

// 5. Akce pro lajkování (Lajk projektu)
Route::post('/projects/{project}/like', [ProjectController::class, 'like'])->name('projects.like');


/*
|--------------------------------------------------------------------------
| Routy pro autentizaci a admin sekci
|--------------------------------------------------------------------------
*/

// Routa pro dashboard (přesměrování na admin sekci, pokud je uživatel admin)
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Routy pro správu profilu (dostupné jen pro přihlášené)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Routy pro Admin sekci (VYŽADUJE PŘIHLÁŠENÍ)
Route::middleware(['auth'])->group(function () {
    // Admin dashboard - seznam VŠECH projektů (pro správu)
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Akce: Schválení projektu
    Route::post('/admin/projects/{project}/approve', [AdminController::class, 'approve'])->name('admin.projects.approve');
    
    // Akce: Mazání projektu (DELETE požadavek)
    Route::delete('/admin/projects/{project}', [AdminController::class, 'destroy'])->name('admin.projects.destroy');
    
    // Akce: Editace (zobrazení formuláře)
    Route::get('/admin/projects/{project}/edit', [AdminController::class, 'edit'])->name('admin.projects.edit');
    
    // Akce: Uložení změn (PATCH požadavek)
    Route::patch('/admin/projects/{project}', [AdminController::class, 'update'])->name('admin.projects.update');
    
    // Správa hero banneru (admin)
    Route::get('/admin/hero-banner', [App\Http\Controllers\AdminHeroController::class, 'edit'])->name('admin.hero.edit');
    Route::patch('/admin/hero-banner', [App\Http\Controllers\AdminHeroController::class, 'update'])->name('admin.hero.update');
    Route::post('/admin/projects/{project}/set-hero', [App\Http\Controllers\AdminHeroController::class, 'setHero'])->name('admin.projects.set-hero');
});


// Routy pro login a register (vyžadované Laravelem Breeze)
require __DIR__.'/auth.php';