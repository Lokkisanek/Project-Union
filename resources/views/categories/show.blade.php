<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategorie: {{ $category->name }} | Project Union</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-900 min-h-screen text-white antialiased">
    @include('layouts.navigation-public')

    <div class="container mx-auto px-4 py-8 pt-20">
        <a href="{{ route('home') }}" class="text-indigo-400 hover:underline mb-6 inline-block">&larr; Zpět na přehled</a>

        <div class="max-w-4xl mx-auto mb-6">
            <h1 class="text-3xl font-bold text-white mt-3">Kategorie: {{ $category->name }}</h1>
            @if($category->description)
                <p class="text-gray-300 mt-2">{{ $category->description }}</p>
            @endif
        </div>

        @if($projects->isEmpty())
            <div class="max-w-4xl mx-auto bg-gray-800 p-6 rounded-lg border border-gray-700 text-gray-300">
                Zatím žádné schválené projekty v této kategorii.
            </div>
        @else
            <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="relative block bg-gray-800/50 rounded-lg shadow-xl overflow-hidden border border-gray-700 hover:border-indigo-500 transition-all duration-300 group">
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

            <div class="mt-8 max-w-4xl mx-auto">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

    @livewireScripts
</body>
</html>
