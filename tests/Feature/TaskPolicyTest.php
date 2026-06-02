<?php

use App\Models\Team;
use App\Models\User;

beforeEach(function () {
    $this->team = Team::create(['name' => 'Task Team']);

    // Create team owner
    $this->owner = User::factory()->create();
    $this->team->users()->attach($this->owner->id, [
        'role' => 'owner',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);

    // Create team admin
    $this->admin = User::factory()->create();
    $this->team->users()->attach($this->admin->id, [
        'role' => 'admin',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);

    // Create team member
    $this->member = User::factory()->create();
    $this->team->users()->attach($this->member->id, [
        'role' => 'member',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);
});

test('owner can assign tasks to anyone on the team', function () {
    // Owner to Admin
    $response = $this->actingAs($this->owner)->post(route('tasks.store'), [
        'title' => 'Owner to Admin Task',
        'status' => 'pending',
        'team_id' => $this->team->id,
        'assign_to' => $this->admin->id,
    ]);
    $response->assertRedirect(route('tasks.index'));

    // Owner to Member
    $response = $this->actingAs($this->owner)->post(route('tasks.store'), [
        'title' => 'Owner to Member Task',
        'status' => 'pending',
        'team_id' => $this->team->id,
        'assign_to' => $this->member->id,
    ]);
    $response->assertRedirect(route('tasks.index'));
});

test('admin can assign tasks to members or other admins, but not to owners', function () {
    // Admin to Member (allowed)
    $response = $this->actingAs($this->admin)->post(route('tasks.store'), [
        'title' => 'Admin to Member Task',
        'status' => 'pending',
        'team_id' => $this->team->id,
        'assign_to' => $this->member->id,
    ]);
    $response->assertRedirect(route('tasks.index'));

    // Admin to Owner (denied)
    $response = $this->actingAs($this->admin)->post(route('tasks.store'), [
        'title' => 'Admin to Owner Task',
        'status' => 'pending',
        'team_id' => $this->team->id,
        'assign_to' => $this->owner->id,
    ]);
    $response->assertStatus(403);
});

test('member can assign tasks to other members, but not to admins or owners', function () {
    // Member to Member (allowed)
    $anotherMember = User::factory()->create();
    $this->team->users()->attach($anotherMember->id, [
        'role' => 'member',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);

    $response = $this->actingAs($this->member)->post(route('tasks.store'), [
        'title' => 'Member to Member Task',
        'status' => 'pending',
        'team_id' => $this->team->id,
        'assign_to' => $anotherMember->id,
    ]);
    $response->assertRedirect(route('tasks.index'));

    // Member to Admin (denied)
    $response = $this->actingAs($this->member)->post(route('tasks.store'), [
        'title' => 'Member to Admin Task',
        'status' => 'pending',
        'team_id' => $this->team->id,
        'assign_to' => $this->admin->id,
    ]);
    $response->assertStatus(403);

    // Member to Owner (denied)
    $response = $this->actingAs($this->member)->post(route('tasks.store'), [
        'title' => 'Member to Owner Task',
        'status' => 'pending',
        'team_id' => $this->team->id,
        'assign_to' => $this->owner->id,
    ]);
    $response->assertStatus(403);
});
