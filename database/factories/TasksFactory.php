<?php

namespace Database\Factories;

use App\Models\Tasks;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TasksFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tasks::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'parent_id' => rand(0,20),
            'status' => rand(0, 1),
            'priority' => rand(0, 5),
            'title' => Str::random(10),
            'description' => Str::random(15),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'completion_time' => Str::random(15),
        ];
    }
}
