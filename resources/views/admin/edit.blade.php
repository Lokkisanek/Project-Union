<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Úprava projektu: {{ $project->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Formulář pro úpravu textových informací --}}
                    <form method="POST" action="{{ route('admin.projects.update', $project) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Levý sloupec --}}
                            <div class="space-y-4">
                                <!-- Jméno autora, Email, Název, Odkaz na web... -->
                                <div>
                                    <label for="author_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jméno autora</label>
                                    <input type="text" name="author_name" id="author_name" value="{{ old('author_name', $project->author_name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @error('author_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="author_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Školní email</label>
                                    <input type="email" name="author_email" id="author_email" value="{{ old('author_email', $project->author_email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @error('author_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Název projektu</label>
                                    <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="web_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Odkaz na webovou stránku (URL)</label>
                                    <input type="url" name="web_link" id="web_link" value="{{ old('web_link', $project->web_link) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @error('web_link')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            {{-- Pravý sloupec --}}
                            <div class="space-y-4">
                                <!-- Popis, Počet lajků, Featured checkbox -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Popis projektu</label>
                                    <textarea name="description" id="description" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $project->description) }}</textarea>
                                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="likes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Počet lajků (ruční úprava)</label>
                                    <input type="number" name="likes" id="likes" value="{{ old('likes', $project->likes) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @error('likes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="pt-2">
                                    <label for="is_featured" class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" id="is_featured" name="is_featured" value="1" @if(old('is_featured', $project->is_featured)) checked @endif class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Zobrazit v Carouselu (Doporučené)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 flex justify-end gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 ...">Zpět</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 ...">Uložit změny</button>
                        </div>
                    </form>

                    {{-- NOVÁ SEKCE PRO NÁHLED OBRÁZKŮ --}}
                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Nahrané obrázky (náhled)</h3>
                        
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Hlavní obrázek:</p>
                            @if ($project->main_image)
                                <a href="{{ asset('storage/' . $project->main_image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $project->main_image) }}" class="mt-2 w-48 h-27 object-cover rounded hover:opacity-75 transition-opacity">
                                </a>
                            @else
                                <p class="text-sm text-gray-500">Hlavní obrázek nebyl nahrán.</p>
                            @endif
                        </div>

                        <div class="mt-6">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Galerie:</p>
                            @if ($project->gallery->isNotEmpty())
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                    @foreach ($project->gallery as $galleryImage)
                                        <a href="{{ asset('storage/' . $galleryImage->path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $galleryImage->path) }}" class="w-full h-32 object-cover rounded hover:opacity-75 transition-opacity">
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">Projekt nemá žádné obrázky v galerii.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>