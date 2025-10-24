<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @include('layouts.navigation-public') {{-- Zahrneme nové navigační menu --}}
        </h2>
    </x-slot>
     @include('layouts.navigation-public') 
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                        
                    {{-- Zpráva o úspěchu po akci --}}
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Zkontrolujeme, jestli vůbec nějaké projekty čekají na schválení --}}
                    @if ($projects->isEmpty())
                        <p>Žádné projekty nečekají na schválení. Dobrá práce!</p>
                    @else
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Autor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Název projektu</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Soubory / Odkazy</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Akce</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-700">
                                    
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $project->author_name }}
                                                <div class="text-xs text-gray-500">{{ $project->author_email }}</div>
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $project->title }}</td>
                                            
                                            {{-- Sloupec pro zobrazení souborů a odkazů --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                @if ($project->file_path)
                                                    <a href="{{ asset('storage/' . $project->file_path) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline block">
                                                        Stáhnout soubor
                                                    </a>
                                                @endif
                                                @if ($project->image_path)
                                                    <a href="{{ asset('storage/' . $project->image_path) }}" target="_blank" class="text-pink-600 dark:text-pink-400 hover:underline block">
                                                        Zobrazit obrázek
                                                    </a>
                                                @endif
                                                @if ($project->web_link)
                                                    <a href="{{ $project->web_link }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline block">
                                                        Web odkaz
                                                    </a>
                                                @endif
                                            </td>
                                            
                                            {{-- Sloupec s akčními tlačítky --}}
                                            {{-- Zkus nahradit tu <td...></td...> buňku tímto --}}
<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    <form action="{{ route('admin.projects.approve', $project) }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="text-green-600">Schválit</button>
    </form>

    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600" onclick="return confirm('Opravdu chcete smazat?')">Smazat</button>
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