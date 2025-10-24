<?php

namespace Database\Seeders;

use App\Models\Project; // <<< TENTO ŘÁDEK TAM CHYBÍ

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vytvoří 20 falešných projektů pomocí naší ProjectFactory
        Project::factory()->count(20)->create();
    }
}