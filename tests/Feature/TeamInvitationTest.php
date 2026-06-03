<?php

use App\Models\Team;
use App\Models\User;
use App\Models\TeamInvitation;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->team = Team::create(['name' => 'Test Team']);
    $this->team->users()->attach($this->owner->id, [
        'role' => 'owner',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);

    $this->nonOwner = User::factory()->create();
    $this->invitee = User::factory()->create();
});

test('owner can invite a user to the team', function () {
    Notification::fake();

    $response = $this->actingAs($this->owner)
        ->post(route('teams.invite', $this->team), [
            'email' => $this->invitee->email,
            'role' => 'member',
        ]);

    $response->assertRedirect(route('teams.index'));
    $response->assertSessionHas('success', 'Invitation sent successfully.');

    $this->assertDatabaseHas('team_invitations', [
        'team_id' => $this->team->id,
        'email' => strtolower($this->invitee->email),
        'role' => 'member',
    ]);

    Notification::assertSentTo(
        new \Illuminate\Notifications\AnonymousNotifiable,
        TeamInvitationNotification::class
    );
});

test('non-owner cannot invite a user to the team', function () {
    $response = $this->actingAs($this->nonOwner)
        ->post(route('teams.invite', $this->team), [
            'email' => $this->invitee->email,
            'role' => 'member',
        ]);

    $response->assertStatus(403);
    $this->assertDatabaseMissing('team_invitations', [
        'team_id' => $this->team->id,
        'email' => strtolower($this->invitee->email),
    ]);
});

test('cannot invite user if already a member', function () {
    $this->team->users()->attach($this->invitee->id, [
        'role' => 'member',
        'status' => 'accepted',
        'joined_at' => now(),
    ]);

    $response = $this->actingAs($this->owner)
        ->post(route('teams.invite', $this->team), [
            'email' => $this->invitee->email,
            'role' => 'member',
        ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertDatabaseMissing('team_invitations', [
        'team_id' => $this->team->id,
        'email' => strtolower($this->invitee->email),
    ]);
});

test('cannot invite user if already has a pending join request', function () {
    $this->team->users()->attach($this->invitee->id, [
        'role' => 'member',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->owner)
        ->post(route('teams.invite', $this->team), [
            'email' => $this->invitee->email,
            'role' => 'member',
        ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertDatabaseMissing('team_invitations', [
        'team_id' => $this->team->id,
        'email' => strtolower($this->invitee->email),
    ]);
});

test('cannot invite user if already has a pending invitation', function () {
    TeamInvitation::create([
        'team_id' => $this->team->id,
        'email' => strtolower($this->invitee->email),
        'role' => 'member',
        'token' => 'test-token',
    ]);

    $response = $this->actingAs($this->owner)
        ->post(route('teams.invite', $this->team), [
            'email' => $this->invitee->email,
            'role' => 'member',
        ]);

    $response->assertSessionHasErrors(['email']);
});

test('invited user can accept invitation', function () {
    $invitation = TeamInvitation::create([
        'team_id' => $this->team->id,
        'email' => strtolower($this->invitee->email),
        'role' => 'admin',
        'token' => 'test-token-accept',
    ]);

    $response = $this->actingAs($this->invitee)
        ->post(route('teams.invitations.accept', $invitation));

    $response->assertRedirect(route('teams.index'));
    $response->assertSessionHas('success', "You have joined team {$this->team->name}!");

    $this->assertDatabaseHas('team_users', [
        'team_id' => $this->team->id,
        'user_id' => $this->invitee->id,
        'status' => 'accepted',
        'role' => 'admin',
    ]);

    $this->assertDatabaseMissing('team_invitations', [
        'id' => $invitation->id,
    ]);
});

test('invited user can decline invitation', function () {
    $invitation = TeamInvitation::create([
        'team_id' => $this->team->id,
        'email' => strtolower($this->invitee->email),
        'role' => 'member',
        'token' => 'test-token-decline',
    ]);

    $response = $this->actingAs($this->invitee)
        ->post(route('teams.invitations.decline', $invitation));

    $response->assertRedirect(route('teams.index'));
    $response->assertSessionHas('success', 'Invitation declined.');

    $this->assertDatabaseMissing('team_users', [
        'team_id' => $this->team->id,
        'user_id' => $this->invitee->id,
    ]);

    $this->assertDatabaseMissing('team_invitations', [
        'id' => $invitation->id,
    ]);
});

test('owner can revoke sent invitation', function () {
    $invitation = TeamInvitation::create([
        'team_id' => $this->team->id,
        'email' => 'invitee@example.com',
        'role' => 'member',
        'token' => 'test-token-revoke',
    ]);

    $response = $this->actingAs($this->owner)
        ->delete(route('teams.invitations.destroy', $invitation));

    $response->assertRedirect(route('teams.index'));
    $response->assertSessionHas('success', 'Invitation revoked.');

    $this->assertDatabaseMissing('team_invitations', [
        'id' => $invitation->id,
    ]);
});

test('non-owner cannot revoke sent invitation', function () {
    $invitation = TeamInvitation::create([
        'team_id' => $this->team->id,
        'email' => 'invitee@example.com',
        'role' => 'member',
        'token' => 'test-token-revoke',
    ]);

    $response = $this->actingAs($this->nonOwner)
        ->delete(route('teams.invitations.destroy', $invitation));

    $response->assertStatus(403);
    $this->assertDatabaseHas('team_invitations', [
        'id' => $invitation->id,
    ]);
});
