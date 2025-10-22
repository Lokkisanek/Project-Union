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

                    {{-- Formulář ODESÍLÁ DATA na routu admin.projects.update --}}
                    <form method="POST" action="{{ route('admin.projects.update', $project) }}">
                        @csrf
                        @method('PATCH') {{-- Klíčové: Metoda PATCH pro aktualizaci dat --}}

                        <!-- Jméno autora -->
                        <div class="mb-4">
                            <label for="author_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jméno autora</label>
                            <input type="text" name="author_name" id="author_name" 
                                   value="{{ old('author_name', $project->author_name) }}" 
                                   required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('author_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Email autora -->
                        <div class="mb-4">
                            <label for="author_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Školní email</label>
                            <input type="email" name="author_email" id="author_email" 
                                   value="{{ old('author_email', $project->author_email) }}" 
                                   required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('author_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <!-- Název projektu -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Název projektu</label>
                            <input type="text" name="title" id="title" 
                                   value="{{ old('title', $project->title) }}" 
                                   required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Odkaz na web -->
                        <div class="mb-4">
                            <label for="web_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Odkaz na hotovou webovou stránku (URL)</label>
                            <input type="url" name="web_link" id="web_link" 
                                   value="{{ old('web_link', $project->web_link) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('web_link')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Popis -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Popis projektu</label>
                            <textarea name="description" id="description" rows="4" 
                                      required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $project->description) }}</textarea>
                            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Zpět</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Uložit změny
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>