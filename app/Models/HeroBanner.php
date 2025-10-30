<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroBanner extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'is_active'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
