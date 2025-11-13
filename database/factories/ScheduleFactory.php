<?php

namespace Database\Factories;

use App\Models\Movie;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $hour = $this->faker->numberBetween(0, 23);
        $start = CarbonImmutable::create(2000, 1, 1, $hour, 0, 0); // 年を2000に固定
        $end = $start->copy()->addHours(2);

        return [
            'movie_id' => Movie::factory(),
            'start_time' => $start->format('Y-m-d H:i:s'),
            'end_time'   => $end->format('Y-m-d H:i:s'),
        ];
    }
}
