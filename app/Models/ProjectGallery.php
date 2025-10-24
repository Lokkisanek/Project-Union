<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectGallery extends Model
{
    use HasFactory;

    // TÍMTO ŘÍKÁME LARAVELU, ŽE TABULKA SE JMENUJE 'project_gallery' (jednotné číslo)
    protected $table = 'project_gallery';

    // Povolíme hromadné přiřazení pro tyto sloupce
    protected $fillable = [
        'project_id',
        'path',
        'sort_order',
    ];
    
    // ... (zbytek modelu)
}