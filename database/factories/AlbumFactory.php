<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(["name" => "string", "desc" => "string", "slug" => "string"])] public function definition()
    {
        return [
            "name" => $this->faker->name,
            "desc" => Str::random(32),
            "slug" => $this->faker->slug
        ];
    }
}
