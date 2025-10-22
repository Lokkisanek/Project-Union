<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // Potřebujeme pro uložení souborů

class ProjectController extends Controller
{
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
     * Uloží nový projekt do databáze.
     */
    public function store(Request $request)
    {
        // 1. Validace dat z formuláře
        $validatedData = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|ends_with:@spst.eu',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            
            'web_link' => 'nullable|url|max:255',
            'file' => 'nullable|file|mimes:pdf,zip,doc,docx|max:20480',
            
            // Image je nyní jen odesláno do storage, bez úprav!
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

        // 4. Uložení obrázku (bez ořezu)
        if ($request->hasFile('image')) {
            // Použijeme standardní uložení Laravelu
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