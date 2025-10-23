<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Musíme importovat model User pro práci s kontakty

class ContactController extends Controller
{
    /**
     * Zobrazí seznam administrátorů/učitelů, kteří mají vyplněný profil (Pozice/Role).
     */
    public function index()
    {


        $contacts = User::where('is_admin', true)
            ->whereNotNull('position')
            ->where('show_on_contacts', true) 
            ->orderBy('name')
            ->get();

        // Odešle data do view 'contacts.blade.php'
        return view('contacts', compact('contacts'));
    }
}