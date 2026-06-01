<?php

use App\Models\User;

test('authenticated user can create a task for themselves', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('tasks.store'), [
            'title' => 'Self Task',
            'description' => 'This is a task for myself',
            'status' => 'pending',
            'team_id' => null,
            'assign_to' => $user->id,
        ]);

    $response->assertRedirect(route('tasks.index'));
    $response->assertSessionHas('success', 'Task created successfully.');

    $this->assertDatabaseHas('tasks', [
        'title' => 'Self Task',
        'created_by' => $user->id,
        'assign_to' => $user->id,
        'team_id' => null,
    ]);
});
