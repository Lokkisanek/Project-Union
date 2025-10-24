<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->title }} | Project Union Detail</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 min-h-screen text-white antialiased">
    
    @include('layouts.navigation-public') 

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pt-20">
        
        <a href="{{ route('home') }}" class="text-indigo-400 hover:underline mb-6 inline-block">&larr; Zpět na seznam projektů</a>

        <h1 class="text-4xl font-bold mb-6">{{ $project->title }}</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- LEVÝ BLOK (Obrázek, Galerie, Popis, Detaily) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Hlavní obrázek projektu --}}
                @if ($project->main_image)
                     <img src="{{ asset('storage/' . $project->main_image) }}" alt="{{ $project->title }}" class="w-full rounded-lg shadow-xl">
                @else
                     <div class="w-full h-96 bg-gray-700 flex items-center justify-center text-xl rounded-lg">Hlavní náhled chybí</div>
                @endif
                
                {{-- GALERIE OBRÁZKŮ (pokud existuje) --}}
                @if ($project->gallery->isNotEmpty())
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-4">
                        @foreach ($project->gallery as $galleryImage)
                            <a href="{{ asset('storage/' . $galleryImage->path) }}" target="_blank" class="block">
                                <img src="{{ asset('storage/' . $galleryImage->path) }}" alt="Obrázek z galerie" class="w-full h-32 object-cover rounded hover:opacity-75 transition-opacity">
                            </a>
                        @endforeach
                    </div>
                @endif
                
                <h2 class="text-2xl font-bold pt-4 border-t border-gray-800">Popis projektu</h2>
                <p class="text-gray-300">{{ $project->description }}</p>

                <div class="pt-4 border-t border-gray-700">
                    <h3 class="text-xl font-bold mb-2">Technologie / Autor</h3>
                    <p class="text-gray-400">Autor: {{ $project->author_name }}</p>
                    <p class="text-gray-400">Email: {{ $project->author_email }}</p>
                    
                    {{-- Zobrazení kategorie (Žánru) --}}
                    @if ($project->category)
                        <p class="text-gray-400">Žánr: 
                            <span class="font-semibold text-indigo-400">{{ $project->category->name }}</span>
                        </p>
                    @endif
                </div>
            </div>
            
            {{-- PRAVÝ BLOK (Akce / Odkazy / Hodnocení) --}}
            <div class="lg:col-span-1 bg-gray-800 p-6 rounded-lg shadow-xl h-fit">
                
                @if ($project->web_link || $project->file_path)
                    <p class="text-lg font-bold mb-4">Soubory a Odkazy</p>
                @endif
                
                {{-- Tlačítka pro akce --}}
                <div class="space-y-3">
                    @if ($project->web_link)
                        <a href="{{ $project->web_link }}" target="_blank" class="block w-full bg-blue-600 text-white py-3 px-6 rounded-md font-semibold text-center hover:bg-blue-700 transition-colors">
                            Navštívit Web &rarr;
                        </a>
                    @endif
                    
                    @if ($project->file_path)
                        <a href="{{ asset('storage/' . $project->file_path) }}" target="_blank" class="block w-full bg-indigo-600 text-white py-3 px-6 rounded-md font-semibold text-center hover:bg-indigo-700 transition-colors">
                            Stáhnout projektový soubor
                        </a>
                    @endif
                </div>

                {{-- HODNOCENÍ / LAJKY --}}
                <div class="border-t border-gray-700 pt-4 mt-6 text-center">
                    
                    @if (session('success') === 'Projekt byl úspěšně ohodnocen!')
                         <p class="text-green-500 mb-2">Díky za hodnocení!</p>
                    @elseif (session('error'))
                         <p class="text-yellow-500 mb-2">{{ session('error') }}</p>
                    @endif
                    
                    <p class="text-lg font-bold">Hodnocení</p>
                    <p class="text-4xl text-red-500 font-extrabold mt-1">{{ $project->likes }} ❤️</p>
                    
                    @if (!session('liked_project_' . $project->id))
                        <form action="{{ route('projects.like', $project) }}" method="POST">
                            @csrf
                            <button type="submit" class="mt-4 bg-red-600 text-white py-2 px-6 rounded-md hover:bg-red-700 font-semibold transition-colors">
                                Dát hlas
                            </button>
                        </form>
                    @else
                        <button disabled class="mt-4 bg-gray-600 text-gray-400 py-2 px-6 rounded-md font-semibold cursor-not-allowed">
                            Již jsi hlasoval
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>