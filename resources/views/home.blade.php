<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Union | Přehled Maturitních Projektů</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

    @livewireStyles
</head>
<body class="bg-gray-900 text-white antialiased">

    {{-- Zahrnutí veřejného navigačního menu --}}
    @include('layouts.navigation-public') 

    <div class="container mx-auto px-4 py-8 pt-20">
        
        {{-- CAROUSEL SEKCE: Zobrazí se jen, pokud existují doporučené projekty --}}
        @if ($featured->isNotEmpty())
            <h1 class="text-3xl font-bold mb-6 text-indigo-400">Doporučené projekty</h1>
            
            <div x-data="{ 
                    activeSlide: 0, 
                    slides: {{ $featured->count() }},
                    init() {
                        if (this.slides > 1) {
                             setInterval(() => {
                                this.activeSlide = (this.activeSlide + 1) % this.slides;
                            }, 6000); 
                        }
                    }
                }" 
                class="relative mb-12 h-96 bg-gray-800 rounded-lg shadow-2xl overflow-hidden"
            >
                
                @foreach ($featured as $project)
                    <div 
                        x-show="activeSlide === {{ $loop->index }}" 
                        x-transition:enter.duration.700ms
                        x-transition:leave.duration.700ms
                        class="absolute inset-0 flex"
                        style="display: none;"
                    >
                        {{-- LEVÁ ČÁST: VELKÝ NÁHLED --}}
                        <div class="w-full lg:w-4/6 h-full relative">
                            @if ($project->main_image)
                                <img src="{{ asset('storage/' . $project->main_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover opacity-60">
                            @else
                                <div class="w-full h-full bg-gray-700 flex items-center justify-center text-gray-300 text-2xl">Hlavní obrázek chybí</div>
                            @endif
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
                            
                            <div class="absolute bottom-0 left-0 p-6 w-full">
                                <h2 class="text-4xl font-extrabold mb-2 tracking-tight text-white">{{ $project->title }}</h2>
                                <p class="text-gray-300 mb-4 line-clamp-2">{{ $project->description }}</p>
                                
                                <a href="{{ route('projects.show', $project) }}" class="inline-block bg-indigo-600 text-white py-2 px-6 rounded-md font-semibold hover:bg-indigo-700 transition-colors">
                                    Zobrazit detail projektu &rarr;
                                </a>
                            </div>
                        </div>

                        {{-- PRAVÁ ČÁST: MALÉ INFO --}}
                        <div class="hidden lg:block lg:w-2/6 h-full bg-gray-900/50 backdrop-blur-sm p-4 space-y-2 relative">
                            <h3 class="text-xl font-bold mb-2">{{ $project->title }}</h3>
                            <p class="text-sm text-gray-400">Autor: {{ $project->author_name }}</p>
                            <p class="text-sm text-gray-400">Hodnocení: {{ $project->likes }} ❤️</p>
                            <div class="pt-4 absolute bottom-4 w-full pe-8">
                                <a href="{{ route('projects.show', $project) }}" class="block w-full text-center bg-green-600 text-white py-2 rounded font-semibold hover:bg-green-700">
                                    Přejít na stránku
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Navigační šipky a Indikátory --}}
                @if ($featured->count() > 1)
                    <button @click="activeSlide = (activeSlide - 1 + slides) % slides" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black/50 p-3 hover:bg-black/80 transition-colors rounded-r-lg z-10">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button @click="activeSlide = (activeSlide + 1) % slides" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black/50 p-3 hover:bg-black/80 transition-colors rounded-l-lg z-10">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                @endif
                
                <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                    @for ($i = 0; $i < $featured->count(); $i++)
                        <button @click="activeSlide = {{ $i }}" :class="activeSlide === {{ $i }} ? 'bg-white' : 'bg-gray-500 hover:bg-gray-400'" class="w-3 h-3 rounded-full transition-colors"></button>
                    @endfor
                </div>
            </div>
        @endif
        
        
        {{-- ZOBRAZENÍ VÝSLEDKŮ VYHLEDÁVÁNÍ --}}
        @if (request()->input('search'))
            <p class="text-center text-gray-400 my-8 text-xl">
                Zobrazeny výsledky vyhledávání pro: <span class="text-white font-semibold">"{{ request()->input('search') }}"</span>
            </p>
        @endif

        {{-- VÝPIS VŠECH OSTATNÍCH PROJEKTŮ --}}
        <div class="mt-16 border-t border-gray-700 pt-8">
            <h2 class="text-3xl font-bold mb-6">Kompletní nabídka projektů</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                @forelse ($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="block bg-gray-800 rounded-lg shadow-xl overflow-hidden hover:bg-gray-700 transition-colors duration-200 group">
                        
                        <div class="h-40 overflow-hidden">
                            @if ($project->main_image)
                                <img src="{{ asset('storage/' . $project->main_image) }}" alt="{{ $project->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-indigo-900 flex items-center justify-center text-gray-400 p-2 text-center">
                                    {{ $project->title }}
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-1 text-indigo-400 group-hover:text-white transition-colors">{{ $project->title }}</h3>
                            
                            @if ($project->category)
                                <span class="text-xs font-bold uppercase text-gray-500 tracking-wider">{{ $project->category->name }}</span>
                            @endif
                            
                            <div class="mt-3 flex justify-between items-center text-sm text-gray-500">
                                <span>Autor: {{ $project->author_name }}</span>
                                <span class="text-red-500 font-bold">{{ $project->likes }} ❤️</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-400 col-span-full text-center">Pro tento dotaz nebyly nalezeny žádné další projekty.</p>
                @endforelse
            </div>
        </div>
    </div>
    
    @livewireScripts
</body>
</html>