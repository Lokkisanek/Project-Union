<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Zobrazí Admin dashboard se seznamem VŠECH projektů (pro rychlé schvalování a editaci).
     */
    public function dashboard()
    {
        // 1. Kontrola oprávnění admina
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Přístup odepřen. Nejste administrátor.');
        }

        // 2. Načtení VŠECH projektů (neschválené jako první pro lepší přehled)
        $allProjects = Project::orderBy('is_approved', 'asc')
                              ->latest()
                              ->get();

        return view('admin.dashboard', [
            'projects' => $allProjects // Posíláme VŠECHNY projekty
        ]);
    }

    /**
     * Zobrazí formulář pro editaci daného projektu.
     */
    public function edit(Project $project)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Přístup odepřen.');
        }

        return view('admin.edit', compact('project'));
    }

    /**
     * Uloží změny v projektu.
     */
    public function update(Request $request, Project $project)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Přístup odepřen.');
        }

        // 1. Validace dat
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|ends_with:@spst.eu',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'web_link' => 'nullable|url|max:255',
            
            // Nová pole pro správu: likes a featured
            'is_featured' => ['boolean'], 
            'likes' => ['nullable', 'integer'], // Možnost ručně nastavit lajky
            
            // Soubory a obrázky pro zjednodušení v editaci neupravujeme/nahráváme
            // Předpokládáme, že pokud admin chce změnit soubor, smaže projekt a nahraje ho znovu
        ]);

        // 2. Zpracování checkboxu (pokud není zaškrtnut, Laravel ho nepošle, musíme to ošetřit)
        $validated['is_featured'] = $request->has('is_featured');
        
        // 3. Aktualizace dat
        $project->update($validated);

        return Redirect::route('admin.dashboard')->with('success', 'Projekt byl úspěšně aktualizován.');
    }

    /**
     * Schválí daný projekt.
     */
    public function approve(Project $project)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Přístup odepřen.');
        }

        // Aktualizuje status na schváleno (true)
        $project->update(['is_approved' => true]);

        return Redirect::route('admin.dashboard')->with('success', 'Projekt byl úspěšně schválen a je viditelný na hlavní stránce!');
    }

    /**
     * Smaže projekt.
     */
    public function destroy(Project $project)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Přístup odepřen.');
        }

        // 1. Smazání fyzických souborů z disku (dobrý zvyk!)
        if ($project->file_path) {
            Storage::disk('public')->delete($project->file_path);
        }
        if ($project->image_path) {
            Storage::disk('public')->delete($project->image_path);
        }

        // 2. Smazání záznamu z databáze
        $project->delete();

        return Redirect::route('admin.dashboard')->with('success', 'Projekt byl trvale smazán!');
    }
}