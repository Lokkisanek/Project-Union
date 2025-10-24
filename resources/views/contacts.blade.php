<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakty a Vedení Projektů | Project Union</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Odkaz na Font Awesome je odstraněn --}}
</head>
<body class="bg-gray-900 text-white antialiased">
    
    @include('layouts.navigation-public')

    <div class="container mx-auto px-4 py-12 pt-20">

        
        <div class="flex justify-center">
            
            <div class="">
                
                @forelse ($contacts as $contact)
                    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl text-center transform hover:-translate-y-2 transition-transform duration-300">
                        
                        {{-- Profilový obrázek --}}
                        @if ($contact->profile_image)
                            <img src="{{ asset('storage/' . $contact->profile_image) }}" alt="{{ $contact->name }}" class="w-28 h-28 object-cover rounded-full mx-auto mb-4 border-4 border-indigo-500">
                        @else
                            <div class="w-28 h-28 bg-gray-700 rounded-full mx-auto mb-4 flex items-center justify-center border-4 border-gray-600">
                                <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 3.582-8 8h16c0-4.418-3.582-8-8-8z"></path></svg>
                            </div>
                        @endif

                        <h2 class="text-2xl font-semibold">{{ $contact->name }}</h2>
                        <p class="text-indigo-400 font-medium mb-4">{{ $contact->position }}</p>
                        
                        <div class="mt-4 text-left inline-block text-gray-400">
                            <p class="text-sm">Email: <a href="mailto:{{ $contact->email }}" class="text-blue-400 hover:underline">{{ $contact->email }}</a></p>
                            @if ($contact->phone)
                                <p class="text-sm">Tel: {{ $contact->phone }}</p>
                            @endif
                        </div>
                        
                        {{-- SOCIÁLNÍ ODKAZY S EMOJI --}}
                        <div class="mt-6 flex justify-center space-x-4">
                            
                            @if ($contact->github_link)
                                <a href="{{ $contact->github_link }}" target="_blank" title="GitHub" class="text-gray-400 hover:text-white transition-colors text-2xl">
                                    🐙
                                </a>
                            @endif
                            
                            @if ($contact->linkedin_link)
                                <a href="{{ $contact->linkedin_link }}" target="_blank" title="LinkedIn" class="text-blue-500 hover:text-blue-400 transition-colors text-2xl">
                                    🔗
                                </a>
                            @endif

                            @if ($contact->instagram_link)
                                <a href="{{ $contact->instagram_link }}" target="_blank" title="Instagram" class="text-pink-500 hover:text-pink-400 transition-colors text-2xl">
                                    📸
                                </a>
                            @endif
                            
                            @if ($contact->portfolio_link)
                                <a href="{{ $contact->portfolio_link }}" target="_blank" title="Portfolio" class="text-indigo-400 hover:text-indigo-300 transition-colors text-2xl">
                                    🌐
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-400">Žádné kontakty k zobrazení.</p>
                @endforelse
            </div>
        </div>
        
    </div>
</body>
</html>