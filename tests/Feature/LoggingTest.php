<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('updating profile details writes activity log', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'newemail@example.com',
        ]);

    $response->assertRedirect(route('profile.edit'));

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $user->id,
        'action' => 'profile.updated',
        'description' => 'Updated profile details.',
    ]);
});

test('deleting profile account writes activity log', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    $response->assertRedirect('/');

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => null, // user_id is set to null on delete
        'action' => 'profile.deleted',
        'description' => "Deleted user account: {$user->email}",
    ]);
});

test('logging in writes activity log', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $user->id,
        'action' => 'auth.login',
        'description' => 'User logged in.',
    ]);
});

test('logging out writes activity log', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('logout'));

    $response->assertRedirect('/');

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $user->id,
        'action' => 'auth.logout',
        'description' => 'User logged out.',
    ]);
});

test('registering writes activity log', function () {
    $response = $this->post(route('register'), [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('activity_logs', [
        'action' => 'auth.registered',
        'description' => 'User registered a new account.',
    ]);
});
