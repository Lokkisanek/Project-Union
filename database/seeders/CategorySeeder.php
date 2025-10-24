<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Web Aplikace', 'type' => 'web']);
        Category::create(['name' => 'Web Framework', 'type' => 'web']);
        Category::create(['name' => 'Desktop Hra (3D/2D)', 'type' => 'game']);
        Category::create(['name' => 'Mobilní Aplikace', 'type' => 'mobile']);
        Category::create(['name' => 'Hardware/IoT', 'type' => 'other']);
    }
}