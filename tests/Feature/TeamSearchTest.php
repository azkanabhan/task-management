<?php

use App\Models\Team;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    
    // Create some teams
    $this->teamA = Team::create(['name' => 'Team Alpha']);
    $this->teamB = Team::create(['name' => 'Team Beta']);
    
    // User is already a member of Team B
    $this->teamB->users()->attach($this->user->id, [
        'role' => 'member',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);
});

test('joinable teams list is empty by default when not searching', function () {
    $response = $this->actingAs($this->user)->get(route('teams.index'));

    $response->assertStatus(200);
    $response->assertViewHas('joinableTeams', function ($teams) {
        return $teams->isEmpty();
    });
    $response->assertSee('Enter a team name or exact Team Code (UID) above to find teams to join.');
});

test('searching by exact code returns matching joinable team', function () {
    $response = $this->actingAs($this->user)->get(route('teams.index', [
        'search' => $this->teamA->code,
    ]));

    $response->assertStatus(200);
    $response->assertViewHas('joinableTeams', function ($teams) {
        return $teams->count() === 1 && $teams->first()->id === $this->teamA->id;
    });
    $response->assertSee('Team Alpha');
});

test('searching by part of name returns matching joinable team', function () {
    $response = $this->actingAs($this->user)->get(route('teams.index', [
        'search' => 'Alpha',
    ]));

    $response->assertStatus(200);
    $response->assertViewHas('joinableTeams', function ($teams) {
        return $teams->count() === 1 && $teams->first()->id === $this->teamA->id;
    });
    $response->assertSee('Team Alpha');
});

test('searching does not return teams the user is already in', function () {
    $response = $this->actingAs($this->user)->get(route('teams.index', [
        'search' => 'Beta',
    ]));

    $response->assertStatus(200);
    $response->assertViewHas('joinableTeams', function ($teams) {
        return $teams->isEmpty();
    });
    $response->assertSee('No teams found matching your search.');
});
