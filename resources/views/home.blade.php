<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Union | Přehled Maturitních Projektů</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
        <style>
            /* Hide default scrollbars for our horizontal scrollers */
            .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            .hide-scrollbar::-webkit-scrollbar { display: none; }
            /* Ensure main container doesn't produce horizontal scroll */
            html, body { overflow-x: hidden; }
        </style>
</head>

<body class="bg-gray-900 text-white antialiased">
    
    @include('layouts.navigation-public') 

    {{-- Full-bleed hero (outside centered container so background color stretches full width) --}}
    @include('components.hero-banner', ['heroProject' => $heroProject ?? null, 'heroColor' => $heroColor ?? null])

    <div class="container mx-auto px-4 py-8 pt-20">
        
        {{-- HLAVNÍ DOPORUČENÉ PROJEKTY --}}
        @if ($featured->isNotEmpty())

            
            <div x-data="{ 
                    activeSlide: 0, 
                    slides: {{ $featured->count() }},
                    init() {
                        if (this.slides > 1) {
                            setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.slides; }, 6000); 
                        }
                    }
                }"
                class="relative max-w-7xl mx-auto"
            >
                @if ($featured->count() > 1)
                    <button @click="activeSlide = (activeSlide - 1 + slides) % slides" 
                            class="absolute -left-16 top-1/2 transform -translate-y-1/2 bg-gray-700/50 p-3 hover:bg-gray-600 transition-colors rounded-full z-20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button @click="activeSlide = (activeSlide + 1) % slides" 
                            class="absolute -right-16 top-1/2 transform -translate-y-1/2 bg-gray-700/50 p-3 hover:bg-gray-600 transition-colors rounded-full z-20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @endif

                <div class="relative mb-12 h-[28rem] bg-gray-800 rounded-lg shadow-2xl overflow-hidden border border-gray-700"> 
                    @foreach ($featured as $project)
                        <div 
                            x-show="activeSlide === {{ $loop->index }}" 
                            x-transition:enter="transition ease-out duration-1000"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-700"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute inset-0 grid grid-cols-4"
                            style="display: none;"
                        >
                            <a href="{{ route('projects.show', $project) }}" class="col-span-3 h-full relative block group">
                                @if ($project->main_image)
                                    <img src="{{ asset('storage/' . $project->main_image) }}" 
                                         alt="{{ $project->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full bg-gray-700 flex items-center justify-center text-gray-300 text-2xl">
                                        Hlavní obrázek chybí
                                    </div>
                                @endif
                                {{-- Pravostranný fade, plynule přechází obrázek do info panelu --}}
                                <div class="absolute inset-y-0 right-0 w-1/3 bg-gradient-to-l from-black/70 to-transparent pointer-events-none rounded-l-lg"></div>
                            </a>

                            <div class="col-span-1 h-full bg-gray-800 p-6 flex flex-col z-10">
                                <a href="{{ route('projects.show', $project) }}">
                                    <h3 class="text-2xl font-bold mb-2 text-white hover:text-indigo-400 transition-colors">{{ $project->title }}</h3>
                                </a>
                                <div class="text-sm text-gray-400 space-y-1 mb-4">
                                    <p>Autor: {{ $project->author_name }}</p>
                                    @if ($project->category)
                                        <p>Kategorie: <span class="font-semibold text-indigo-400">{{ $project->category->name }}</span></p>
                                    @endif
                                    <p>Hodnocení: <span class="font-bold text-red-500">{{ $project->likes }} ❤️</span></p>
                                </div>
                                <p class="text-sm text-gray-300 line-clamp-6">{{ $project->description }}</p>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20">
                        @foreach (range(0, $featured->count() - 1) as $index)
                            <button @click="activeSlide = {{ $index }}" 
                                    :class="activeSlide === {{ $index }} ? 'bg-white' : 'bg-gray-500 hover:bg-gray-400'" 
                                    class="w-3 h-3 rounded-full transition-colors"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Promo řádek (velké bannery) --}}
        <div class="max-w-7xl mx-auto mt-8">
            @include('components.promo-row', ['title' => 'Doporučené promo', 'projects' => $editorsPicks, 'seeAllUrl' => route('home')])
        </div>


        {{-- DALŠÍ DOPORUČENÉ PROJEKTY --}}
        <div class="mt-16 max-w-7xl mx-auto">

            
            <div x-data="{ 
                    activeSlide: 0, 
                    slides: {{ ceil($projects->count() / 4) }} 
                 }"
                 class="relative"
            >
            <button @click="activeSlide = (activeSlide - 1 + slides) % slides" 
                        class="absolute -left-16 top-1/2 transform -translate-y-1/2 bg-gray-700/50 p-3 hover:bg-gray-600 transition-colors rounded-full z-20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button @click="activeSlide = (activeSlide + 1) % slides" 
                        class="absolute -right-16 top-1/2 transform -translate-y-1/2 bg-gray-700/50 p-3 hover:bg-gray-600 transition-colors rounded-full z-20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                <div class="relative overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-in-out"
                         :style="{ transform: `translateX(-${activeSlide * 100}%)` }">
                        
                        @foreach ($projects->chunk(4) as $chunk)
                            <div class="w-full flex-shrink-0 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach ($chunk as $project)
                                    <a href="{{ route('projects.show', $project) }}" 
                                       class="relative block bg-gray-800/50 rounded-lg shadow-xl overflow-hidden border border-gray-700 hover:border-indigo-500 transition-all duration-300 group">
                                        <div class="h-40 overflow-hidden">
                                            @if ($project->main_image)
                                                <img src="{{ asset('storage/' . $project->main_image) }}" 
                                                     alt="{{ $project->title }}" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full bg-indigo-900/40 flex items-center justify-center p-2 text-center">
                                                    {{ $project->title }}
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Jemný pravostranný fade na obrázku dlaždice --}}
                                        <div class="absolute inset-y-0 right-0 w-1/3 bg-gradient-to-l from-gray-900/70 to-transparent pointer-events-none rounded-r-lg"></div>
                                        <div class="p-4">
                                            <h3 class="text-lg font-semibold text-white group-hover:text-indigo-400">{{ $project->title }}</h3>
                                            <div class="mt-2 flex justify-between items-center text-sm text-gray-400">
                                                <span>{{ $project->category->name ?? '' }}</span>
                                                <span class="text-red-500 font-bold">{{ $project->likes }} ❤️</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                
            </div>
        </div>


        {{-- KATEGORIE --}}
        <div class="mt-16 max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" 
                       class="relative h-32 rounded-lg overflow-hidden group shadow-lg">
                        <div class="absolute inset-0 bg-indigo-800 bg-opacity-70 group-hover:bg-opacity-90 transition-all duration-300"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <h3 class="text-xl font-bold text-white">{{ $category->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

        {{-- Nové "Steam-like" řádky (Top rated, New releases, Editors picks) --}}
        <div class="mt-12 max-w-7xl mx-auto">
            @include('components.row-carousel', ['title' => 'Nejlépe hodnocené', 'projects' => $topRated, 'seeAllUrl' => route('home')])
            @include('components.row-carousel', ['title' => 'Nové přírůstky', 'projects' => $newest, 'seeAllUrl' => route('home')])
            @include('components.row-carousel', ['title' => 'Doporučené redakcí', 'projects' => $editorsPicks, 'seeAllUrl' => route('home')])
        </div>

    @livewireScripts
</body>
</html>
