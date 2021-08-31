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
            'parent_id' => $this->faker->boolean(50) ? $this->faker->numberBetween(1, 20) : null,
            'status' => $this->faker->randomElement(['todo', 'done']),
            'priority' => $this->faker->numberBetween(0, 5),
            'title' => $this->faker->realText(10),
            'description' => $this->faker->realText(15),
            'user_id' => $this->faker->numberBetween(1, 3),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'completion_time' => $this->faker->dateTimeThisYear(),
        ];
    }
}
