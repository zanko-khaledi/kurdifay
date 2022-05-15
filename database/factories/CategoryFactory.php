<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(["title" => "string", "description" => "mixed", "slug" => "string"])] public function definition()
    {
        return [
            "title" => $this->faker->title,
            "description" => Str::random(32),
            "slug" => $this->faker->slug
        ];
    }
}
