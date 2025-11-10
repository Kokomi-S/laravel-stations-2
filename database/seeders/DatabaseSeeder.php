<?php

namespace Database\Seeders;

use App\Practice;
use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Practice::factory(10)->create();
        Movie::factory(100)->create();
        Genre::factory(10)->create();
    }
}
