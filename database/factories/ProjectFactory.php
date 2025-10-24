<?php

namespace Database\Factories;

use App\Models\Category; // <<< TENTO ŘÁDEK TAM CHYBÍ

use Illuminate\Database\Eloquent\Factories\Factory;

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
        // Získáme ID existujících kategorií, abychom mohli náhodně jednu vybrat
        $categoryIds = Category::pluck('id')->toArray();

        return [
            'author_name' => fake()->name(),
            'author_email' => fake()->unique()->safeEmail(),
            'title' => fake()->words(3, true),
            'description' => fake()->paragraph(5),
            'category_id' => fake()->randomElement($categoryIds),
            'web_link' => fake()->optional()->url(),
            'file_path' => null,
            'main_image' => 'project_images/' . fake()->randomElement(['placeholder1.jpg', 'placeholder2.jpg', 'placeholder3.jpg']),
            'is_approved' => fake()->boolean(80),
            'likes' => fake()->numberBetween(0, 500),
        ];
    }
}