<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subcategory>
 */
class SubcategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(["category_id" => "int", "title" => "string", "description" => "string", "slug" => "string"])] public function definition()
    {
        return [
            "category_id" => (int)rand(0,10),
            "title" => $this->faker->title,
            "description" => Str::random(32),
            "slug" => $this->faker->slug
        ];
    }
}
