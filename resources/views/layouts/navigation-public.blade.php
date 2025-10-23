<nav class="bg-gray-800 dark:bg-gray-900 border-b border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex">
                {{-- Logo / Název --}}
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('intro') }}" class="text-xl font-bold text-white hover:text-indigo-400 transition-colors">
                        Project Union
                    </a>
                </div>

                {{-- Navigační Odkazy --}}
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('intro')" :active="request()->routeIs('intro')" class="text-gray-300 hover:text-white">
                        {{ __('Úvod') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-gray-300 hover:text-white">
                        {{ __('Projekty') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('contacts')" :active="request()->routeIs('contacts')" class="text-gray-300 hover:text-white">
                        {{ __('Kontakty') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('projects.create')" :active="request()->routeIs('projects.create')" class="text-green-400 hover:text-green-300 font-semibold">
                        {{ __('Přidat projekt') }}
                    </x-nav-link>
                </div>
            </div>

            {{-- Admin Login --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white">
                    Admin Login
                </a>
            </div>
        </div>
    </div>
</nav>