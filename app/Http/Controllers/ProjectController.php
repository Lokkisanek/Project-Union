<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Category;
use App\Models\ProjectGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

// NEPOUŽÍVÁME ŽÁDNOU EXTERNÍ KNIHOVNU PRO OBRÁZKY

class ProjectController extends Controller
{
    /**
     * Zobrazí hlavní stránku (home) s carouselem a seznamem projektů.
     */
   public function home(Request $request)
{
    $search = $request->input('search');
    
    // 1. Získáme 4 nejoblíbenější SCHVÁLENÉ projekty pro CAROUSEL
    $featuredProjects = Project::where('is_approved', true)
        ->when($search, function ($query) use ($search) {
            $query->where('title', 'LIKE', "%{$search}%");
        })
        ->orderBy('likes', 'desc') // KLÍČOVÉ: řadíme podle lajků sestupně
        ->take(4) 
        ->get();
        
    // 2. Získáme pole ID featured projektů
    $featuredIds = $featuredProjects->pluck('id');

    // 3. Získáme VŠECHNY OSTATNÍ schválené projekty
    // Použijeme WHERE NOT IN, což je nejbezpečnější
    $projects = Project::where('is_approved', true)
        ->whereNotIn('id', $featuredIds) // Vyloučíme podle ID
        ->when($search, function ($query) use ($search) {
            $query->where('title', 'LIKE', "%{$search}%");
        })
        ->orderBy('likes', 'desc') // Zde také řadíme podle lajků
        ->get();

        $categories = Category::all();
        
    return view('home', [
        'featured' => $featuredProjects, 
        'projects' => $projects,         
        'search' => $search,    
        'categories' => $categories // <-- Přidáme kategorie do view         
    ]);
}

    /**
     * Zobrazí detail konkrétního projektu.
     */
    public function show(Project $project)
    {
        if (!$project->is_approved && (!auth()->check() || !auth()->user()->is_admin)) {
            abort(404);
        }
        $project->load('category', 'gallery'); 
        return view('projects.show', compact('project'));
    }

    /**
     * Zpracuje hlasování (Lajk).
     */
    public function like(Project $project)
    {
        $sessionKey = 'liked_project_' . $project->id;
        if (session($sessionKey)) {
            return Redirect::back()->with('error', 'Tento projekt jsi již ohodnotil.');
        }
        $project->increment('likes');
        session()->put($sessionKey, true);
        return Redirect::back()->with('success', 'Projekt byl úspěšně ohodnocen!');
    }

    /**
     * Zobrazí formulář pro vytvoření projektu.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('projects.create', compact('categories'));
    }

    /**
     * Uloží nový projekt do databáze (s nativním PHP ořezem).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|ends_with:@spst.eu',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'web_link' => 'nullable|url|max:255',
            'main_file' => 'nullable|file|mimes:pdf,zip,doc,docx|max:20480', 
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'gallery_images' => ['required', 'array', 'min:4', 'max:4'], 
            'gallery_images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);

        if (empty($validatedData['main_file']) && empty($validatedData['web_link'])) {
            return Redirect::back()->withErrors(['submission' => 'Musíte nahrát buď hlavní projektový soubor, nebo zadat odkaz na web.'])->withInput();
        }

        // --- AUTOMATICKÝ OŘEZ POMOCÍ NATIVNÍ PHP GD KNIHOVNY ---
        $mainImagePath = null;
        if ($request->hasFile('main_image')) {
            $image = $request->file('main_image');
            $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $targetPath = storage_path('app/public/project_images/' . $filename);

            // Cílové rozměry 16:9
            $targetWidth = 1280;
            $targetHeight = 720;
            $targetRatio = $targetWidth / $targetHeight;

            list($originalWidth, $originalHeight, $imageType) = getimagesize($image->getRealPath());
            $originalRatio = $originalWidth / $originalHeight;

            switch ($imageType) {
                case IMAGETYPE_JPEG: $sourceImage = imagecreatefromjpeg($image->getRealPath()); break;
                case IMAGETYPE_PNG: $sourceImage = imagecreatefrompng($image->getRealPath()); break;
                case IMAGETYPE_GIF: $sourceImage = imagecreatefromgif($image->getRealPath()); break;
                default:
                    $mainImagePath = $request->file('main_image')->store('project_images', 'public');
                    goto end_image_processing;
            }
            
            $srcX = 0; $srcY = 0;
            $srcWidth = $originalWidth; $srcHeight = $originalHeight;

            if ($originalRatio > $targetRatio) {
                $srcWidth = $originalHeight * $targetRatio;
                $srcX = ($originalWidth - $srcWidth) / 2;
            } else {
                $srcHeight = $originalWidth / $targetRatio;
                $srcY = ($originalHeight - $srcHeight) / 2;
            }

            $destinationImage = imagecreatetruecolor($targetWidth, $targetHeight);
            imagecopyresampled($destinationImage, $sourceImage, 0, 0, $srcX, $srcY, $targetWidth, $targetHeight, $srcWidth, $srcHeight);

            switch ($imageType) {
                case IMAGETYPE_JPEG: imagejpeg($destinationImage, $targetPath, 90); break;
                case IMAGETYPE_PNG: imagepng($destinationImage, $targetPath, 9); break;
                case IMAGETYPE_GIF: imagegif($destinationImage, $targetPath); break;
            }

            imagedestroy($sourceImage);
            imagedestroy($destinationImage);

            $mainImagePath = 'project_images/' . $filename;

            end_image_processing:
        }
        // --- KONEC OŘEZU ---

        $filePath = $request->hasFile('main_file') ? $request->file('main_file')->store('projects', 'public') : null;

        $project = Project::create([
            'author_name' => $validatedData['author_name'],
            'author_email' => $validatedData['author_email'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
            'main_image' => $mainImagePath,
            'web_link' => $validatedData['web_link'] ?? null,
            'file_path' => $filePath,
            'is_approved' => false,
        ]);
        
        foreach ($request->file('gallery_images') as $index => $galleryFile) {
            $galleryPath = $galleryFile->store('project_gallery', 'public');
            ProjectGallery::create([
                'project_id' => $project->id,
                'path' => $galleryPath,
                'sort_order' => $index,
            ]);
        }

        return Redirect::route('projects.create')->with('success', 'Projekt byl úspěšně nahrán a čeká na schválení. Děkujeme!');
    }
}