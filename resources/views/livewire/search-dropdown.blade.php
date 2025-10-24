<div x-data="{ open: true }" @click.away="open = false" class="relative">
    
    {{-- Vyhledávací pole navázané na Livewire --}}
    <input 
        wire:model.live.debounce.300ms="search" 
        @focus="open = true"
        type="search" 
        placeholder="Vyhledat projekt..." 
        class="w-56 px-4 py-1.5 border border-gray-600 rounded bg-gray-700 text-white text-sm focus:outline-none focus:border-indigo-500"
        autocomplete="off"
    >    

    {{-- Dropdown s výsledky (zobrazí se, až když se začne psát) --}}
   
        <div x-show="open" class="absolute z-50 w-full mt-1 bg-gray-700 border border-gray-600 rounded shadow-lg max-h-96 overflow-y-auto">
            @forelse ($searchResults as $project)
                <a href="{{ route('projects.show', $project) }}" class="block p-3 hover:bg-gray-600 transition-colors">
                    <div class="flex items-center">
                        <img src="{{ asset('storage/' . $project->main_image) }}" class="w-16 h-9 object-cover rounded mr-3">
                        <div>
                            <div class="font-semibold text-white">{{ $project->title }}</div>
                            <div class="text-xs text-gray-400">{{ $project->category->name }}</div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-3 text-gray-400">Žádné výsledky pro "{{ $search }}"</div>
            @endforelse

            {{-- Odkaz na celostránkové vyhledávání --}}
            <div class="p-2 border-t border-gray-600 bg-gray-700 text-center">
                <a href="{{ route('home', ['search' => $search]) }}" class="text-indigo-400 hover:underline text-sm">
                    Zobrazit všechny výsledky...
                </a>
            </div>
        </div>
 
</div>