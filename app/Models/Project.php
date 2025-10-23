<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_name',
        'author_email',
        'title',
        'description',
        'file_path',
        'web_link',      // NOVÝ
        'image_path',
        'is_approved', // Povolíme měnit i status schválení
        'likes',        // NOVÉ
    'is_featured', 
    ];
}