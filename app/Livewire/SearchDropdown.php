<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;

class SearchDropdown extends Component
{
    public $search = '';

    public function render()
    {
        $searchResults = collect(); // Ve výchozím stavu prázdná kolekce

        // Hledáme, až když uživatel zadá alespoň 2 znaky
        if (strlen($this->search) >= 2) {
            
            // Převedeme hledaný text na malá písmena pro porovnání
            $searchTerm = strtolower($this->search);

            $searchResults = Project::where('is_approved', true) // Hledáme jen ve schválených
                ->with('category') // Zároveň načteme i kategorii pro zobrazení
                ->where(function ($query) use ($searchTerm) {
                    
                    // Hledáme v názvu projektu (bez ohledu na velikost písmen)
                    $query->whereRaw('LOWER(title) LIKE ?', ["%{$searchTerm}%"])
                          
                          // Hledáme i v autorovi (bez ohledu na velikost písmen)
                          ->orWhereRaw('LOWER(author_name) LIKE ?', ["%{$searchTerm}%"])

                          // Hledáme i v názvu kategorie
                          ->orWhereHas('category', function ($subQuery) use ($searchTerm) {
                              $subQuery->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                          });
                })
                ->take(7) // Omezíme počet výsledků v dropdownu na 7
                ->get();
        }

        return view('livewire.search-dropdown', [
            'searchResults' => $searchResults,
        ]);
    }
}