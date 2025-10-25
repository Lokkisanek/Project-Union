<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
// Kolem řádku 14
public function run(): void
{
    $this->call([
        \Database\Seeders\CategorySeeder::class, // Volání CategorySeederu
        \Database\Seeders\ProjectSeeder::class,  // Volání ProjectSeederu
    ]);
}
}
