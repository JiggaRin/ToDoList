<?php

namespace Database\Seeders;

use App\Models\Tasks;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tasks::factory()->count(20)->create();
    }
}
