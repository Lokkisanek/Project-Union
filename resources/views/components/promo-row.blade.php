<div x-data class="my-8">
    <div class="flex items-center justify-between mb-3 px-2">
        <h3 class="text-xl font-bold text-white">{{ $title }}</h3>
        @if(isset($seeAllUrl))
            <a href="{{ $seeAllUrl }}" class="text-sm text-gray-400 hover:text-white">Zobrazit vše &rarr;</a>
        @endif
    </div>

    <div class="relative">
        <button @click="$refs.scroller.scrollBy({ left: -760, behavior: 'smooth' })"
                class="absolute left-0 top-1/2 -translate-y-1/2 z-20 bg-gray-800/60 p-2 rounded-full hidden md:inline-flex">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <div class="overflow-hidden">
            <div x-ref="scroller" class="flex gap-6 overflow-x-auto scroll-smooth py-2 px-6 hide-scrollbar">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="w-96 flex-shrink-0 bg-gradient-to-b from-gray-800/60 to-gray-900/60 rounded-lg shadow-lg overflow-hidden border border-gray-700 hover:scale-105 transform transition">
                        <div class="h-44 bg-gray-900 overflow-hidden">
                            @if($project->main_image)
                                <img src="{{ asset('storage/' . $project->main_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">{{ $project->title }}</div>
                            @endif
                        </div>
                        <div class="p-4 bg-gradient-to-t from-black/40 via-transparent to-transparent">
                            <h4 class="text-lg font-bold text-white line-clamp-2">{{ $project->title }}</h4>
                            <p class="text-sm text-gray-300 mt-2 line-clamp-2">{{ \Illuminate\Support\Str::limit($project->description, 80) }}</p>
                            <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
                                <span>{{ $project->category->name ?? '' }}</span>
                                <span class="text-red-400 font-bold">{{ $project->likes }} ❤️</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <button @click="$refs.scroller.scrollBy({ left: 760, behavior: 'smooth' })"
                class="absolute right-0 top-1/2 -translate-y-1/2 z-20 bg-gray-800/60 p-2 rounded-full hidden md:inline-flex">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</div>
