<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Admin Dashboard: Projekty Čekající na Schválení
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Odkaz pro zobrazení VŠECH projektů (pro úpravu těch schválených) --}}
                    {{-- Zatím nefunguje, dokud nedoděláme routu index v AdminControlleru, ale odkaz tu je --}}
                  
                    
                    {{-- Zpráva o úspěchu po akci --}}
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Kontrola, zda vůbec nějaké projekty čekají --}}
                    @if ($projects->isEmpty())
                        <p class="text-lg">Žádné projekty nečekají na schválení. Dobrá práce!</p>
                    @else
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                               <thead class="bg-gray-50 dark:bg-gray-700">
    <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Autor / Název</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Soubory / Odkazy</th>
        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Akce</th>
    </tr>
</thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-700">
    
    @foreach ($projects as $project)
        <tr>
            <td class="px-6 py-4">
                <strong>{{ $project->title }}</strong>
                <div class="text-xs text-gray-500">{{ $project->author_name }} ({{ $project->author_email }})</div>
            </td>

            {{-- NOVÝ SLOUPEC: STATUS --}}
            <td class="px-6 py-4">
                @if ($project->is_approved)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Schváleno</span>
                @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Čeká na schválení</span>
                @endif
            </td>
            
            {{-- Sloupec pro soubory a odkazy (stejný jako dříve) --}}
           <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
    
    {{-- Odkaz na soubor projektu (PDF, ZIP atd.) --}}
    @if ($project->file_path)
        <a href="{{ asset('storage/' . $project->file_path) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline block">
            Stáhnout soubor
        </a>
    @endif
    
    {{-- Odkaz pro náhled na obrázek projektu --}}
    @if ($project->image_path)
        <a href="{{ asset('storage/' . $project->image_path) }}" target="_blank" class="text-pink-600 dark:text-pink-400 hover:underline block">
            Zobrazit obrázek
        </a>
    @endif

    {{-- Odkaz na webovou stránku --}}
    @if ($project->web_link)
        <a href="{{ $project->web_link }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline block">
            Web odkaz
        </a>
    @endif
    
</td>
            {{-- Sloupec s akčními tlačítky (Upravit, Schválit (podmíněné), Smazat) --}}
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-1 justify-end items-center">
                
                {{-- TLAČÍTKO UPRAVIT (VŽDY ZOBRAZENO) --}}
                <a href="{{ route('admin.projects.edit', $project) }}" class="text-blue-600 dark:text-blue-400 hover:underline transition-all">
                    Upravit
                </a>

                {{-- TLAČÍTKO SCHVÁLIT (POUZE POKUD NENÍ SCHVÁLENO) --}}
                @if (!$project->is_approved)
                    <form action="{{ route('admin.projects.approve', $project) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-green-600 dark:text-green-400 hover:underline hover:font-semibold transition-all ml-3">Schválit</button>
                    </form>
                @endif

                {{-- Formulář pro MAZÁNÍ (VŽDY ZOBRAZENO) --}}
                <form action="{{ route('admin.projects.destroy', $project) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('OPRAVDU chcete projekt Smazat?')" class="text-red-600 dark:text-red-400 hover:underline hover:font-semibold transition-all ml-3">Smazat</button>
                </form>
            </td>
        </tr>
    @endforeach
    
</tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>