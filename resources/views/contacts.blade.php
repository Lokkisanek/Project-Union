
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakty a Vedení Projektů | Project Union</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMD/cd2jIqXzFjM13aFOn4pBvQe2j04aTjC+1nQ6x+g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Font Awesome odkaz je ZDE ODSTRANĚN --}}
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    @include('layouts.navigation-public') {{-- Zahrneme nové navigační menu --}}

    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold mb-8 text-center">Kontakty a Vedení Projektů</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($contacts as $contact)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl text-center">
                    
                    {{-- Profilový obrázek --}}
                    @if ($contact->profile_image)
                        <img src="{{ asset('storage/' . $contact->profile_image) }}" alt="{{ $contact->name }}" class="w-24 h-24 object-cover rounded-full mx-auto mb-4">
                    @else
                        <div class="w-24 h-24 bg-gray-300 dark:bg-gray-700 rounded-full mx-auto mb-4 flex items-center justify-center">
                            {{-- Náhradní ikona uživatele --}}
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 3.582-8 8h16c0-4.418-3.582-8-8-8z"></path></svg>
                        </div>
                    @endif

                    <h2 class="text-2xl font-semibold">{{ $contact->name }}</h2>
                    <p class="text-indigo-600 dark:text-indigo-400 font-medium mb-4">{{ $contact->position }}</p>
                    
                    <div class="mt-4 text-left inline-block">
                        <p class="text-sm">Email: <a href="mailto:{{ $contact->email }}" class="text-blue-500 hover:underline">{{ $contact->email }}</a></p>
                        @if ($contact->phone)
                            <p class="text-sm">Tel: {{ $contact->phone }}</p>
                        @endif
                    </div>
                    
                    {{-- SOCIÁLNÍ ODKAZY (používáme text/emoji jako náhradu za Font Awesome) --}}
                    <div class="mt-6 flex justify-center space-x-4">
                        
                        @if ($contact->github_link)
                            <a href="{{ $contact->github_link }}" target="_blank" title="GitHub" class="text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors text-lg font-bold">
                                🐙 GitHub
                            </a>
                        @endif
                        
                        @if ($contact->linkedin_link)
                            <a href="{{ $contact->linkedin_link }}" target="_blank" title="LinkedIn" class="text-blue-700 hover:text-blue-900 transition-colors text-lg font-bold">
                                🔗 LinkedIn
                            </a>
                        @endif

                        @if ($contact->instagram_link)
                            <a href="{{ $contact->instagram_link }}" target="_blank" title="Instagram" class="text-pink-600 hover:text-pink-800 transition-colors text-lg font-bold">
                                📸 IG
                            </a>
                        @endif
                        
                        @if ($contact->portfolio_link)
                            <a href="{{ $contact->portfolio_link }}" target="_blank" title="Portfolio" class="text-indigo-600 hover:text-indigo-800 transition-colors text-lg font-bold">
                                🌐 Web
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('home') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Zpět na přehled projektů</a>
        </div>
    </div>
</body>
</html>