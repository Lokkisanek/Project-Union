@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Upravit Hero Banner</h1>

    @if(session('success'))
        <div class="mb-4 text-green-500">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.hero.update') }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label class="block text-sm text-gray-700 mb-2">Vyber projekt pro hero banner</label>
            <select name="project_id" class="w-full bg-gray-800 text-white p-3 rounded">
                <option value="">-- žádný --</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ optional($hero->project)->id == $project->id ? 'selected' : '' }}>{{ $project->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <button class="bg-indigo-600 text-white py-2 px-4 rounded">Uložit</button>
        </div>
    </form>
</div>
@endsection
