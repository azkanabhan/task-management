<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $creator = User::factory()->create();

        return [
            'title'       => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph(),
            'status'      => $this->faker->randomElement(['pending', 'in_progress', 'done']),
            'deadline'    => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'created_by'  => $creator->id,
            'assign_to'   => $creator->id,
            'category_id' => null,
            'team_id'     => null,
        ];
    }
}
