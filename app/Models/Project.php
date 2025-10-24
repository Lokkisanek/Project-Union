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
        'author_name', 'author_email', 'title', 'description', 
    'file_path', 'web_link', 'image_path', 'is_approved',
    'likes', 'is_featured', // NOVÉ POLE
    'main_image', // NOVÉ
    'category_id', // NOVÉ
    ];

    public function category() {
    return $this->belongsTo(Category::class);
}
public function gallery() {
    return $this->hasMany(ProjectGallery::class);
}
}