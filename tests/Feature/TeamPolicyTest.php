<?php

use App\Models\Team;
use App\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->team = Team::create(['name' => 'Test Team']);
    $this->team->users()->attach($this->owner->id, [
        'role' => 'owner',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);

    $this->nonOwner = User::factory()->create();
});

test('owner can approve join request', function () {
    $requester = User::factory()->create();
    $this->team->users()->attach($requester->id, [
        'role' => 'member',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->owner)
        ->post(route('teams.approve', $this->team), [
            'user_id' => $requester->id,
        ]);

    $response->assertRedirect(route('teams.index'));
    $response->assertSessionHas('success', 'Member request approved.');

    $this->assertDatabaseHas('team_users', [
        'team_id' => $this->team->id,
        'user_id' => $requester->id,
        'status' => 'accepted',
        'role' => 'member',
    ]);
});

test('non-owner cannot approve join request', function () {
    $requester = User::factory()->create();
    $this->team->users()->attach($requester->id, [
        'role' => 'member',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->nonOwner)
        ->post(route('teams.approve', $this->team), [
            'user_id' => $requester->id,
        ]);

    $response->assertStatus(403);

    $this->assertDatabaseHas('team_users', [
        'team_id' => $this->team->id,
        'user_id' => $requester->id,
        'status' => 'pending',
    ]);
});

test('owner can reject join request', function () {
    $requester = User::factory()->create();
    $this->team->users()->attach($requester->id, [
        'role' => 'member',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->owner)
        ->post(route('teams.reject', $this->team), [
            'user_id' => $requester->id,
        ]);

    $response->assertRedirect(route('teams.index'));
    $response->assertSessionHas('success', 'Member request rejected.');

    $this->assertDatabaseHas('team_users', [
        'team_id' => $this->team->id,
        'user_id' => $requester->id,
        'status' => 'rejected',
    ]);
});

test('non-owner cannot reject join request', function () {
    $requester = User::factory()->create();
    $this->team->users()->attach($requester->id, [
        'role' => 'member',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->nonOwner)
        ->post(route('teams.reject', $this->team), [
            'user_id' => $requester->id,
        ]);

    $response->assertStatus(403);

    $this->assertDatabaseHas('team_users', [
        'team_id' => $this->team->id,
        'user_id' => $requester->id,
        'status' => 'pending',
    ]);
});

test('owner can update member role', function () {
    $member = User::factory()->create();
    $this->team->users()->attach($member->id, [
        'role' => 'member',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);

    $response = $this->actingAs($this->owner)
        ->post(route('teams.role.update', $this->team), [
            'user_id' => $member->id,
            'role' => 'admin',
        ]);

    $response->assertRedirect(route('teams.index'));
    $response->assertSessionHas('success', 'Member role updated.');

    $this->assertDatabaseHas('team_users', [
        'team_id' => $this->team->id,
        'user_id' => $member->id,
        'role' => 'admin',
    ]);
});

test('non-owner cannot update member role', function () {
    $member = User::factory()->create();
    $this->team->users()->attach($member->id, [
        'role' => 'member',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);

    $response = $this->actingAs($this->nonOwner)
        ->post(route('teams.role.update', $this->team), [
            'user_id' => $member->id,
            'role' => 'admin',
        ]);

    $response->assertStatus(403);

    $this->assertDatabaseHas('team_users', [
        'team_id' => $this->team->id,
        'user_id' => $member->id,
        'role' => 'member',
    ]);
});

test('owner can kick member', function () {
    $member = User::factory()->create();
    $this->team->users()->attach($member->id, [
        'role' => 'member',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);

    $response = $this->actingAs($this->owner)
        ->post(route('teams.kick', $this->team), [
            'user_id' => $member->id,
        ]);

    $response->assertRedirect(route('teams.index'));
    $response->assertSessionHas('success', 'Member removed from team.');

    $this->assertDatabaseMissing('team_users', [
        'team_id' => $this->team->id,
        'user_id' => $member->id,
    ]);
});

test('non-owner cannot kick member', function () {
    $member = User::factory()->create();
    $this->team->users()->attach($member->id, [
        'role' => 'member',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);

    $response = $this->actingAs($this->nonOwner)
        ->post(route('teams.kick', $this->team), [
            'user_id' => $member->id,
        ]);

    $response->assertStatus(403);

    $this->assertDatabaseHas('team_users', [
        'team_id' => $this->team->id,
        'user_id' => $member->id,
    ]);
});
