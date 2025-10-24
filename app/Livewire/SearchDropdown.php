<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;

class SearchDropdown extends Component
{
    public $search = '';

public function render()
{
    $searchResults = collect();

    // Hledáme, až když uživatel zadá alespoň 2 znaky
    if (strlen($this->search) >= 2) {
        
        // "HLOUPÝ" DOTAZ: Najdi cokoliv, co obsahuje hledaný text, bez ohledu na schválení
        $searchResults = Project::with('category')
            ->where('title', 'like', '%' . $this->search . '%')
            ->take(7)
            ->get();
    }

    return view('livewire.search-dropdown', [
        'searchResults' => $searchResults,
    ]);
}
}