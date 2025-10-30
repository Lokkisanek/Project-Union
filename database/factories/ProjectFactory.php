<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory; // <<< DŮLEŽITÉ PŘIDAT

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Vytvoříme lokální instanci Fakeru, aby fungovala v případě chyby
        $faker = $this->faker; 

        // Získáme ID existujících kategorií, abychom mohli náhodně jednu vybrat
        $categoryIds = Category::pluck('id')->toArray();

        return [
            // Používáme lokální $faker
            'author_name' => $faker->name(), 
            'author_email' => $faker->unique()->safeEmail(), 
            'title' => $faker->words(3, true),
            'description' => $faker->paragraph(5),
            
            // Kontrola, zda existují kategorie, než se pokusíme vybrat
            'category_id' => !empty($categoryIds) ? $faker->randomElement($categoryIds) : null,
            
            'web_link' => $faker->optional()->url(),
            'file_path' => null,
            
            'main_image' => 'project_images/' . $faker->randomElement(['placeholder1.png', 'placeholder2.png', 'placeholder3.png']),
            
            'is_approved' => $faker->boolean(80),
            'likes' => $faker->numberBetween(0, 500),
        ];
    }
}