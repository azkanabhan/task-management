<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Task::create([
                'title' => 'Setup project board',
                'description' => 'Create initial task board and assign priorities.',
                'status' => 'pending',
                'user_id' => $user->id,
                'category_id' => 1,
            ]);

            Task::create([
                'title' => 'Implement auth flow',
                'description' => 'Add login, logout, and session handling.',
                'status' => 'in_progress',
                'user_id' => $user->id,
                'category_id' => 2,
            ]);
        }
    }
}
