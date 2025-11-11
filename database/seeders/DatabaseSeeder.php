<?php

namespace Database\Seeders;

use App\Practice;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Sheet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::table('sheets')->insert([
            ['id'=>1, 'column'=>1, 'row'=>'a'],
            ['id'=>2, 'column'=>2, 'row'=>'a'],
            ['id'=>3, 'column'=>3, 'row'=>'a'],
            ['id'=>4, 'column'=>4, 'row'=>'a'],
            ['id'=>5, 'column'=>5, 'row'=>'a'],
            ['id'=>6, 'column'=>1, 'row'=>'b'],
            ['id'=>7, 'column'=>2, 'row'=>'b'],
            ['id'=>8, 'column'=>3, 'row'=>'b'],
            ['id'=>9, 'column'=>4, 'row'=>'b'],
            ['id'=>10,'column'=>5, 'row'=>'b'],
            ['id'=>11,'column'=>1, 'row'=>'c'],
            ['id'=>12,'column'=>2, 'row'=>'c'],
            ['id'=>13,'column'=>3, 'row'=>'c'],
            ['id'=>14,'column'=>4, 'row'=>'c'],
            ['id'=>15,'column'=>5, 'row'=>'c'],
        ]);
    }
}
