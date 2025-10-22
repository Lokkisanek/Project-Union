<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // Přidáme pro mazání souborů

class AdminController extends Controller
{
    /**
     * Zobrazí Admin dashboard se seznamem čekajících projektů.
     */
  public function dashboard()
{
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403, 'Přístup odepřen.');
    }

    // Načteme VŠECHNY projekty, seřazené od nejnovějšího po nejstarší.
    // Pro lepší přehlednost na začátek řadíme ty neschválené.
    $allProjects = Project::orderBy('is_approved', 'asc') // False (0) bude první
                          ->latest()
                          ->get(); 

    return view('admin.dashboard', [
        'projects' => $allProjects // Posíláme VŠECHNY projekty
    ]);
}
    /**
     * Schválí daný projekt.
     */
    public function approve(Project $project)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Přístup odepřen. Nejste administrátor.');
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
            abort(403, 'Přístup odepřen. Nejste administrátor.');
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

public function edit(Project $project)
{
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403, 'Přístup odepřen.');
    }

    // Vrátí view s formulářem, kterému pošleme data projektu
    return view('admin.edit', compact('project'));
}

// Metoda pro uložení změn
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
        // Zde bychom neřešili nahrávání nových souborů, to je složitější.
        // Předpokládáme, že soubory jsou nahrány a my upravujeme jen texty.
    ]);

    // 2. Aktualizace dat v databázi
    $project->update($validated);

    // 3. Přesměrování zpět s úspěšnou zprávou
    return Redirect::route('admin.dashboard')->with('success', 'Projekt byl úspěšně aktualizován.');
}
// Zobrazí seznam VŠECH projektů (schválených i čekajících)
public function index()
{
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403, 'Přístup odepřen.');
    }

    $allProjects = Project::latest()->get(); // Načte VŠECHNY projekty

    return view('admin.index', ['projects' => $allProjects]);
}

}

