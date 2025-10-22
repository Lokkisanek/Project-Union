<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Union - Maturitní Projekty</title>
    
    {{-- Načtení Tailwind/Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white antialiased">

    <div class="container mx-auto px-4 py-8">
        <header class="text-center mb-12">
            <h1 class="text-5xl font-bold mb-2 tracking-tight">Project Union</h1>
            <p class="text-xl text-gray-400">Přehlídka maturitních projektů SPŠT</p>
            <div class="mt-8 flex justify-center gap-4">
                {{-- Odkaz na formulář pro nahrávání --}}
                <a href="{{ route('projects.create') }}" class="bg-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                    Nahrát projekt
                </a>
                {{-- Odkaz na přihlášení pro admina --}}
                <a href="{{ route('login') }}" class="bg-gray-700 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                    Admin Login
                </a>
            </div>
        </header>

        <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            {{-- ZAČÁTEK SMYČKY: Prochází všechny schválené projekty --}}
            @forelse ($projects as $project)
                
                <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex flex-col hover:scale-105 transition-transform duration-300">
                    
                    {{-- ZOBRAZENÍ OBRÁZKU --}}
                    @if ($project->image_path)
                        <img src="{{ asset('storage/' . $project->image_path) }}" 
                             alt="Obrázek projektu {{ $project->title }}" 
                             class="w-full h-48 object-cover rounded-md mb-4 border border-gray-700">
                    @endif

                    <h2 class="text-2xl font-bold mb-2 text-indigo-400">{{ $project->title }}</h2>
                    <p class="text-gray-400 mb-4">Autor: {{ $project->author_name }}</p>
                    <p class="text-gray-300 flex-grow mb-6">{{ $project->description }}</p>
                    
                    {{-- SEKCE TLAČÍTEK --}}
                    <div class="mt-auto flex flex-col gap-3">
                        
                        {{-- Tlačítko pro WEB LINK (má prioritu, pokud existuje) --}}
                        @if ($project->web_link)
                            <a href="{{ $project->web_link }}" target="_blank" class="text-center w-full bg-blue-600 text-white py-2 px-4 rounded-md font-semibold hover:bg-blue-700 transition-colors">
                                Přejít na web projektu
                            </a>
                        @endif

                        {{-- Tlačítko pro SOUBOR (pokud existuje) --}}
                        @if ($project->file_path)
                            <a href="{{ asset('storage/' . $project->file_path) }}" target="_blank" class="text-center w-full bg-indigo-600 text-white py-2 px-4 rounded-md font-semibold hover:bg-indigo-700 transition-colors">
                                Stáhnout projekt (Soubor)
                            </a>
                        @endif

                        {{-- Pokud nemá ani soubor, ani web link --}}
                        @if (!$project->web_link && !$project->file_path)
                            <span class="text-center w-full bg-gray-600 text-gray-400 py-2 px-4 rounded-md">
                                Bez souborů / odkazů
                            </span>
                        @endif
                    </div>
                </div>
            
            @empty
                {{-- Zobrazí se, pokud seznam projektů je prázdný --}}
                <p class="text-gray-400 col-span-full text-center text-lg">
                    Zatím nebyly schváleny žádné projekty. Buďte první, kdo nahraje svůj!
                </p>
            @endforelse
            {{-- KONEC SMYČKY --}}

        </main>
    </div>

</body>
</html>