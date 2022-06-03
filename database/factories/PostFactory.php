<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(["subcategory_id" => "int", "title" => "string", "desc" => "string", "slug" => "string", "artist" => "string", "lyric" => "string", "img" => "\Illuminate\Http\Testing\File"])] public function definition()
    {
        return [
            "title" => $this->faker->title,
            "desc" => Str::random(),
            "slug" => $this->faker->slug,
            "artist" => Str::random(16),
            "lyric" => Str::random(),
        ];
    }
}
