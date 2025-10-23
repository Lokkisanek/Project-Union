<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->title }} | Project Union</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 min-h-screen text-white antialiased">
    
    @include('layouts.navigation-public') 

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <h1 class="text-4xl font-bold mb-6">{{ $project->title }}</h1>
        
        {{-- Zde pokračuje zbytek tvého kódu pro detail projektu (Steam-like design) --}}
        {{-- Zkontroluj, že máš správně div tagy atd. --}}
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- DETAIL: Levá strana (Obrázek/Popis) --}}
            <div class="lg:col-span-2 space-y-6">
            <!-- ... zbytek kódu ... -->