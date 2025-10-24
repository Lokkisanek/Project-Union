<x-app-layout>
   
@include('layouts.navigation-public') 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Formulář pro nahrávání více souborů a dat --}}
                    <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <h3 class="text-xl font-bold mb-4 border-b pb-2">1. Základní informace o autorovi</h3>

                        <!-- Jméno autora -->
                        <div class="mb-4">
                            <label for="author_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jméno a příjmení autora / autorů</label>
                            <input type="text" name="author_name" id="author_name" value="{{ old('author_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('author_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Email autora -->
                        <div class="mb-6 border-b pb-4">
                            <label for="author_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Školní email pro ověření</label>
                            <input type="email" name="author_email" id="author_email" value="{{ old('author_email') }}" required placeholder="jmeno.prijmeni@spst.eu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('author_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <h3 class="text-xl font-bold mb-4 border-b pb-2">2. Detaily projektu</h3>

                        <!-- Název projektu -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Název projektu</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Popis -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stručný popis projektu</label>
                            <textarea name="description" id="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description') }}</textarea>
                            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- SELECT BOX pro Kategorie -->
<div class="mb-6">
    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Žánr / Typ projektu (Povinné)</label>
    <select name="category_id" id="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        
        {{-- Důležité: Option pro default, nastavíme disabled, aby vynutilo výběr --}}
        <option value="" disabled selected>Vyberte typ...</option>
        
        {{-- Procházíme kategorie (nyní je jisté, že $categories existuje) --}}
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
                        
                        <h3 class="text-xl font-bold mb-4 border-b pb-2">3. Soubory a Galerie</h3>

                        <!-- MAIN FOTO (POVINNÉ) -->
                        <div class="mb-4 p-4 border border-gray-700 rounded-lg bg-gray-700/30">
                            <label for="main_image" class="block text-sm font-medium text-gray-200">HLAVNÍ NÁHLED (POVINNÝ, formát 16:9)</label>
                            <input type="file" name="main_image" id="main_image" required accept="image/*" class="mt-1 block w-full text-sm text-gray-400 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                            @error('main_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- GALERIE (4 OBRÁZKY/VIDEA) -->
                        <div class="mb-6 p-4 border border-gray-700 rounded-lg bg-gray-700/30">
                            <label class="block text-sm font-medium text-gray-200 mb-2">GALERIE (4 doplňkové screenshoty/náhledy - povinné)</label>
                            
                            <div class="space-y-2">
                                @for ($i = 0; $i < 4; $i++)
                                     <input type="file" name="gallery_images[]" required accept="image/*" class="block w-full text-sm text-gray-400 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-600 file:text-white hover:file:bg-gray-500">
                                @endfor
                            </div>
                            @error('gallery_images')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            @error('gallery_images.*')<p class="text-red-500 text-xs mt-1">Chyba v jednom ze souborů galerie.</p>@enderror
                        </div>

                        <!-- Soubor nebo Odkaz na web -->
                        <div class="mb-4 p-4 border border-indigo-700 rounded-lg bg-indigo-700/20">
                            <p class="text-sm font-semibold text-indigo-300 mb-2">POVINNÉ: MUSÍTE VYPLNIT ALESPOŇ JEDNU Z TĚCHTO POLOŽEK:</p>

                            <!-- Odkaz na web (volitelný) -->
                            <div class="mb-2">
                                <label for="web_link" class="block text-sm font-medium text-gray-300">Odkaz na hotovou webovou stránku</label>
                                <input type="url" name="web_link" id="web_link" value="{{ old('web_link') }}" placeholder="https://tvujprojekt.cz" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('web_link')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Projektový soubor (volitelný) -->
                            <div>
                                <label for="main_file" class="block text-sm font-medium text-gray-300">Nahrát hlavní projektový soubor (ZIP, DOCX, PDF)</label>
                                <input type="file" name="main_file" id="main_file" class="mt-1 block w-full text-sm text-gray-400 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-600 file:text-white hover:file:bg-gray-500">
                                @error('main_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            
                            {{-- Zobrazení hromadné chyby, pokud chybí soubor i odkaz --}}
                            @error('submission')<p class="text-red-500 text-sm mt-4 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Nahrát projekt
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>