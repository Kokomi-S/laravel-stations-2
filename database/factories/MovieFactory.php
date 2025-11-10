<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Genre;

class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->word,
            'image_url' => $this->faker->imageUrl(),
            'genre_id' => Genre::factory(),
            'published_year' => $this->faker->year,
            'is_showing' => $this->faker->boolean,
            'description' => $this->faker->realText(20),
        ];
    }
}
