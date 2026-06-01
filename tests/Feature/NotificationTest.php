<?php

use App\Events\TaskCreated;
use App\Events\TaskStatusUpdated;
use App\Events\TeamJoinRequested;
use App\Events\TeamMemberApproved;
use App\Events\TeamMemberRejected;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskStatusUpdatedNotification;
use App\Notifications\TeamJoinRequestedNotification;
use App\Notifications\TeamMemberApprovedNotification;
use App\Notifications\TeamMemberRejectedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

// ──────────────────────────────────────────────
// Event dispatch tests
// ──────────────────────────────────────────────

test('creating a task dispatches TaskCreated event', function () {
    Event::fake([TaskCreated::class]);

    $creator  = User::factory()->create();
    $assignee = User::factory()->create();

    $this->actingAs($creator)->post(route('tasks.store'), [
        'title'       => 'Test Task',
        'status'      => 'pending',
        'team_id'     => null,
        'assign_to'   => $assignee->id,
    ]);

    Event::assertDispatched(TaskCreated::class, fn ($e) => $e->task->title === 'Test Task');
});

test('updating task status dispatches TaskStatusUpdated event', function () {
    Event::fake([TaskStatusUpdated::class]);

    $creator  = User::factory()->create();
    $assignee = User::factory()->create();
    $task = Task::factory()->create([
        'created_by' => $creator->id,
        'assign_to'  => $assignee->id,
        'status'     => 'pending',
    ]);

    $this->actingAs($assignee)->put(route('tasks.update', $task->id), [
        'status' => 'in_progress',
    ]);

    Event::assertDispatched(TaskStatusUpdated::class, fn ($e) => $e->task->id === $task->id);
});

test('joining a team dispatches TeamJoinRequested event', function () {
    Event::fake([TeamJoinRequested::class]);

    $owner = User::factory()->create();
    $team  = Team::create(['name' => 'Test Team']);
    $team->users()->attach($owner->id, ['role' => 'owner', 'status' => 'accepted', 'joined_at' => now()]);

    $user = User::factory()->create();

    $this->actingAs($user)->post(route('teams.join', $team));

    Event::assertDispatched(TeamJoinRequested::class, fn ($e) => $e->team->id === $team->id && $e->requester->id === $user->id);
});

test('approving a member dispatches TeamMemberApproved event', function () {
    Event::fake([TeamMemberApproved::class]);

    $owner   = User::factory()->create();
    $team    = Team::create(['name' => 'Test Team']);
    $team->users()->attach($owner->id, ['role' => 'owner', 'status' => 'accepted', 'joined_at' => now()]);
    $member  = User::factory()->create();
    $team->users()->attach($member->id, ['role' => 'member', 'status' => 'pending']);

    $this->actingAs($owner)->post(route('teams.approve', $team), ['user_id' => $member->id]);

    Event::assertDispatched(TeamMemberApproved::class, fn ($e) => $e->member->id === $member->id);
});

test('rejecting a member dispatches TeamMemberRejected event', function () {
    Event::fake([TeamMemberRejected::class]);

    $owner  = User::factory()->create();
    $team   = Team::create(['name' => 'Test Team']);
    $team->users()->attach($owner->id, ['role' => 'owner', 'status' => 'accepted', 'joined_at' => now()]);
    $member = User::factory()->create();
    $team->users()->attach($member->id, ['role' => 'member', 'status' => 'pending']);

    $this->actingAs($owner)->post(route('teams.reject', $team), ['user_id' => $member->id]);

    Event::assertDispatched(TeamMemberRejected::class, fn ($e) => $e->member->id === $member->id);
});

// ──────────────────────────────────────────────
// Notification dispatch tests
// ──────────────────────────────────────────────

test('TaskCreated event sends TaskAssignedNotification to assignee', function () {
    Notification::fake();

    $creator  = User::factory()->create();
    $assignee = User::factory()->create();
    $task     = Task::factory()->create(['created_by' => $creator->id, 'assign_to' => $assignee->id]);

    event(new TaskCreated($task));

    Notification::assertSentTo($assignee, TaskAssignedNotification::class);
    Notification::assertNotSentTo($creator, TaskAssignedNotification::class);
});

test('TaskCreated event does not notify when creator assigns to themselves', function () {
    Notification::fake();

    $creator = User::factory()->create();
    $task    = Task::factory()->create(['created_by' => $creator->id, 'assign_to' => $creator->id]);

    event(new TaskCreated($task));

    Notification::assertNotSentTo($creator, TaskAssignedNotification::class);
});

test('TaskStatusUpdated event sends TaskStatusUpdatedNotification to creator', function () {
    Notification::fake();

    $creator  = User::factory()->create();
    $assignee = User::factory()->create();
    $task     = Task::factory()->create(['created_by' => $creator->id, 'assign_to' => $assignee->id, 'status' => 'in_progress']);

    event(new TaskStatusUpdated($task, $assignee->id));

    Notification::assertSentTo($creator, TaskStatusUpdatedNotification::class);
    Notification::assertNotSentTo($assignee, TaskStatusUpdatedNotification::class);
});

test('TeamJoinRequested event sends TeamJoinRequestedNotification to team owner', function () {
    Notification::fake();

    $owner  = User::factory()->create();
    $team   = Team::create(['name' => 'Test Team']);
    $team->users()->attach($owner->id, ['role' => 'owner', 'status' => 'accepted', 'joined_at' => now()]);
    $requester = User::factory()->create();

    event(new TeamJoinRequested($team, $requester));

    Notification::assertSentTo($owner, TeamJoinRequestedNotification::class);
});

test('TeamMemberApproved event sends notification to the approved member', function () {
    Notification::fake();

    $team   = Team::create(['name' => 'Test Team']);
    $member = User::factory()->create();

    event(new TeamMemberApproved($team, $member));

    Notification::assertSentTo($member, TeamMemberApprovedNotification::class);
});

test('TeamMemberRejected event sends notification to the rejected member', function () {
    Notification::fake();

    $team   = Team::create(['name' => 'Test Team']);
    $member = User::factory()->create();

    event(new TeamMemberRejected($team, $member));

    Notification::assertSentTo($member, TeamMemberRejectedNotification::class);
});

// ──────────────────────────────────────────────
// Notification controller tests
// ──────────────────────────────────────────────

test('authenticated user can view notification index page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('notifications.index'))->assertOk();
});

test('unread endpoint returns JSON with count and notifications', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['created_by' => $user->id, 'assign_to' => $user->id]);
    $user->notify(new TaskAssignedNotification($task));

    $response = $this->actingAs($user)
        ->getJson(route('notifications.unread'));

    $response->assertOk()
        ->assertJsonStructure(['count', 'notifications']);
});

test('marking a notification as read redirects and marks it read', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['created_by' => $user->id, 'assign_to' => $user->id]);
    $user->notify(new TaskAssignedNotification($task));
    $notification = $user->notifications()->first();

    $this->actingAs($user)
        ->post(route('notifications.read', $notification->id))
        ->assertRedirect();

    $this->assertNotNull($notification->fresh()->read_at);
});

test('marking all as read clears the unread count', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['created_by' => $user->id, 'assign_to' => $user->id]);
    $user->notify(new TaskAssignedNotification($task));
    $user->notify(new TaskAssignedNotification($task));

    $this->assertEquals(2, $user->unreadNotifications()->count());

    $this->actingAs($user)
        ->post(route('notifications.readAll'))
        ->assertRedirect(route('notifications.index'));

    $this->assertEquals(0, $user->unreadNotifications()->count());
});
