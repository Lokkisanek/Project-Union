<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vítejte | Project Union</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white antialiased">
    
    {{-- Zahrneme veřejné navigační menu --}}
    @include('layouts.navigation-public') 

    <div class="container mx-auto px-4 py-8">
        
        {{-- NOVÝ CAROUSEL/HIGHLIGHT SEKCE --}}
        <h1 class="text-4xl font-bold mb-6 text-indigo-400">Nejžhavější projekty</h1>
        
        <div class="relative mb-12 h-96 bg-gray-800 rounded-lg shadow-2xl overflow-hidden">
            
            @forelse ($featured as $project)
                {{-- Tento div je položka carouselu. Využijeme loop index pro třídy, které by řídily animaci (zde jen statický výpis) --}}
                <div class="absolute inset-0 transition-opacity duration-700 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}">
                    
                    {{-- Obrázek v pozadí --}}
                    @if ($project->image_path)
                        <img src="{{ asset('storage/' . $project->image_path) }}" alt="{{ $project->title }}" class="w-full h-full object-cover opacity-50">
                    @else
                        <div class="w-full h-full bg-gray-700 flex items-center justify-center">Obrázek chybí</div>
                    @endif
                    
                    {{-- Přechodový efekt a obsah --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 p-8 w-full md:w-1/2">
                        <h2 class="text-5xl font-extrabold mb-2 tracking-tight">{{ $project->title }}</h2>
                        <p class="text-gray-300 mb-4 line-clamp-2">{{ $project->description }}</p>
                        
                        <a href="{{ route('projects.show', $project) }}" class="inline-block bg-indigo-600 text-white py-2 px-6 rounded-md font-semibold hover:bg-indigo-700 transition-colors">
                            Zobrazit detail projektu &rarr;
                        </a>
                    </div>
                </div>
            @empty
                 <div class="flex items-center justify-center h-full text-xl text-gray-400">Zatím nejsou žádné Featured projekty označené administrátorem.</div>
            @endforelse
        </div>
        
        {{-- ODKAZ NA VŠECHNY PROJEKTY --}}
        <div class="text-center pb-16">
            <h2 class="text-3xl font-bold mb-4">Všechny projekty v databázi</h2>
            <a href="{{ route('home') }}" class="inline-block bg-gray-700 text-white py-3 px-8 text-lg rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                Prohlédnout celý seznam &rarr;
            </a>
        </div>
    </div>
</body>
</html>