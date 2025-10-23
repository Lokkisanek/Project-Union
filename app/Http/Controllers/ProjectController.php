<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // Potřebujeme pro uložení souborů

// Třída Intervention Image je zcela odstraněna

class ProjectController extends Controller
{

    // Uvnitř app/Http/Controllers/ProjectController.php

public function like(Project $project)
{
    $sessionKey = 'liked_project_' . $project->id;
    
    // 1. Kontrola, zda uživatel v této session lajkoval
    if (session($sessionKey)) {
        return Redirect::back()->with('error', 'Tento projekt jsi již ohodnotil.');
    }

    // 2. Zvýšení počtu lajků
    $project->increment('likes');
    
    // 3. Nastavení session pro zamezení opakování
    session()->put($sessionKey, true);

    // 4. Přesměrování zpět
    return Redirect::back()->with('success', 'Projekt byl úspěšně ohodnocen!');
}
    // NOVÁ METODA: Zobrazení Intro stránky s daty pro carousel
public function intro()
{
    
    // 1. Získá 4 nejoblíbenější projekty NEBO projekty označené jako Featured
    $featuredProjects = Project::where('is_approved', true)
        ->where('is_featured', true)
        ->orderBy('likes', 'desc') // Seřadit podle lajků
        ->take(4) // Vezmi jen 4
        ->get();
        
    // Pokud nenajdeme 4 featured, můžeme přidat další podle likes
    if ($featuredProjects->isEmpty() && Project::count() > 0) {
        $featuredProjects = Project::where('is_approved', true)->orderBy('likes', 'desc')->take(4)->get();
    }

    return view('intro', ['featured' => $featuredProjects]);
}

// NOVÁ METODA: Zobrazení Detailu projektu (pro Steam-like)
public function show(Project $project)
{
    // Kontrola, jestli je projekt schválený, než ho ukážeme veřejnosti
    if (!$project->is_approved && (!auth()->check() || !auth()->user()->is_admin)) {
        abort(404);
    }

    return view('projects.show', compact('project'));
}

// Původní metoda index() (seznam projektů) zůstane stejná
// Původní metoda create(), store() zůstanou stejné
    /**
     * Zobrazí hlavní stránku s výpisem schválených projektů.
     */
    public function index()
    {
        $approvedProjects = Project::where('is_approved', true)
            ->latest()
            ->get();

        return view('welcome', ['projects' => $approvedProjects]);
    }

    /**
     * Zobrazí formulář pro vytvoření nového projektu.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Uloží nový projekt do databáze (Standardní uložení, bez ořezu).
     */
    public function store(Request $request)
    {
        // 1. Validace dat z formuláře
        $validatedData = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|ends_with:@spst.eu',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            
            // Volitelné soubory/odkazy
            'web_link' => 'nullable|url|max:255',
            'file' => 'nullable|file|mimes:pdf,zip,doc,docx|max:20480',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // 2. Kontrola, zda bylo nahráno ALPOŇ něco
        if (empty($validatedData['file']) && empty($validatedData['web_link'])) {
            return Redirect::back()->withErrors(['submission' => 'Musíte nahrát buď projektový soubor, nebo zadat odkaz na webovou aplikaci.'])->withInput();
        }

        $filePath = null;
        $imagePath = null;

        // 3. Uložení projektového souboru
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('projects', 'public');
        }

        // 4. Uložení obrázku (standardní uložení, bez jakékoliv úpravy)
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('project_images', 'public');
        }

        // 5. Uložení záznamu do databáze
        Project::create([
            'author_name' => $validatedData['author_name'],
            'author_email' => $validatedData['author_email'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'web_link' => $validatedData['web_link'] ?? null,
            'file_path' => $filePath,
            'image_path' => $imagePath,
            'is_approved' => false,
        ]);

        return Redirect::route('projects.create')->with('success', 'Projekt byl úspěšně nahrán a čeká na schválení. Děkujeme!');
    }
}