<div x-data="{ open: true }" @click.away="open = false" class="relative">
    
    {{-- Vyhledávací pole navázané na Livewire. wire:model.live.debounce.300ms posílá požadavek 300ms po dopsání. --}}
    <input 
        wire:model.live.debounce.300ms="search" 
        @focus="open = true"
        type="search" 
        placeholder="Vyhledat projekt..." 
        class="w-56 px-4 py-1.5 border border-gray-600 rounded bg-gray-700 text-white text-sm focus:outline-none focus:border-indigo-500"
        autocomplete="off"
    >

    {{-- Dropdown s výsledky se zobrazí, jen pokud je v poli pro hledání text --}}
    @if (strlen($search) >= 2)
        <div x-show="open" class="absolute z-50 w-full mt-1 bg-gray-700 border border-gray-600 rounded shadow-lg max-h-96 overflow-y-auto">
            <div wire:loading class="p-3 text-gray-400">
    Hledám...
</div>
            {{-- Projdeme všechny výsledky a zobrazíme je --}}
            @forelse ($searchResults as $project)
                <a href="{{ route('projects.show', $project) }}" class="block p-3 hover:bg-gray-600 transition-colors">
                    <div class="flex items-center">
                        
                        {{-- Náhledový obrázek --}}
                        @if ($project->main_image)
                            <img src="{{ asset('storage/' . $project->main_image) }}" loading="lazy" class="w-16 h-9 object-cover rounded mr-3">
                        @else
                            <div class="w-16 h-9 bg-gray-800 rounded mr-3 flex items-center justify-center text-xs text-gray-400">Bez obrázku</div>
                        @endif
                        
                        <div>
                            <div class="font-semibold text-white">{{ $project->title }}</div>
                            
                            {{-- Kategorie (Žánr) --}}
                            @if ($project->category)
                                <div class="text-xs text-gray-400">{{ $project->category->name }}</div>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-3 text-gray-400">Žádné výsledky pro "{{ $search }}"</div>
            @endforelse

            {{-- Odkaz na celostránkové vyhledávání, který odešle formulář --}}
            <div class="p-2 border-t border-gray-600 bg-gray-800 text-center">
                <a href="{{ route('home', ['search' => $search]) }}" class="text-indigo-400 hover:underline text-sm">
                    Zobrazit všechny výsledky na stránce...
                </a>
            </div>
        </div>
    @endif
</div>