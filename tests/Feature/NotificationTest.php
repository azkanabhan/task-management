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
use App\Notifications\TaskUnassignedNotification;
use App\Notifications\TaskUpdatedNotification;
use App\Notifications\TaskDeletedNotification;
use App\Notifications\TeamJoinRequestedNotification;
use App\Notifications\TeamMemberApprovedNotification;
use App\Notifications\TeamMemberRejectedNotification;
use App\Events\TaskUpdated;
use App\Events\TaskDeleted;
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
        ->from(route('notifications.index'))
        ->post(route('notifications.readAll'))
        ->assertRedirect(route('notifications.index'));

    $this->assertEquals(0, $user->unreadNotifications()->count());
});

test('TaskStatusUpdated event sends TaskStatusUpdatedNotification to assignee if updated by creator', function () {
    Notification::fake();

    $creator  = User::factory()->create();
    $assignee = User::factory()->create();
    $task     = Task::factory()->create(['created_by' => $creator->id, 'assign_to' => $assignee->id, 'status' => 'pending']);

    event(new TaskStatusUpdated($task, $creator->id));

    Notification::assertSentTo($assignee, TaskStatusUpdatedNotification::class);
    Notification::assertNotSentTo($creator, TaskStatusUpdatedNotification::class);
});

test('TaskUpdated event sends TaskUpdatedNotification to assignee when creator updates task details', function () {
    Notification::fake();

    $creator  = User::factory()->create();
    $assignee = User::factory()->create();
    $task     = Task::factory()->create(['created_by' => $creator->id, 'assign_to' => $assignee->id]);

    event(new TaskUpdated($task, $creator->id, $assignee->id));

    Notification::assertSentTo($assignee, TaskUpdatedNotification::class);
});

test('TaskUpdated event handles assignee changes by notifying both previous and new assignees', function () {
    Notification::fake();

    $creator  = User::factory()->create();
    $prevAssignee = User::factory()->create();
    $newAssignee = User::factory()->create();
    $task     = Task::factory()->create(['created_by' => $creator->id, 'assign_to' => $newAssignee->id]);

    event(new TaskUpdated($task, $creator->id, $prevAssignee->id));

    Notification::assertSentTo($prevAssignee, TaskUnassignedNotification::class);
    Notification::assertSentTo($newAssignee, TaskAssignedNotification::class);
});

test('TaskDeleted event sends TaskDeletedNotification to assignee when a task is deleted by creator', function () {
    Notification::fake();

    $creator  = User::factory()->create();
    $assignee = User::factory()->create();

    event(new TaskDeleted('Some Task Title', $creator->id, $assignee->id, $creator->id));

    Notification::assertSentTo($assignee, TaskDeletedNotification::class);
});

test('TaskAssignedNotification creates mail message correctly', function () {
    $creator  = User::factory()->create();
    $assignee = User::factory()->create();
    $task     = Task::factory()->create(['created_by' => $creator->id, 'assign_to' => $assignee->id]);

    $notification = new TaskAssignedNotification($task);
    $mailMessage = $notification->toMail($assignee);

    expect($mailMessage->subject)->toBe("New Task Assigned: \"{$task->title}\"");
    expect($mailMessage->view)->toBe('emails.task_assigned');
});

test('TaskUpdatedNotification creates mail message correctly', function () {
    $creator  = User::factory()->create();
    $assignee = User::factory()->create();
    $task     = Task::factory()->create(['created_by' => $creator->id, 'assign_to' => $assignee->id]);

    $notification = new TaskUpdatedNotification($task, $creator->id);
    $mailMessage = $notification->toMail($assignee);

    expect($mailMessage->subject)->toBe("Task Updated: \"{$task->title}\"");
    expect($mailMessage->view)->toBe('emails.task_updated');
});

test('TaskUnassignedNotification creates mail message correctly', function () {
    $creator  = User::factory()->create();
    $assignee = User::factory()->create();

    $notification = new TaskUnassignedNotification('Unassigned Task Title', $creator->id);
    $mailMessage = $notification->toMail($assignee);

    expect($mailMessage->subject)->toBe("Unassigned from Task: \"Unassigned Task Title\"");
    expect($mailMessage->view)->toBe('emails.task_unassigned');
});

test('TaskDeletedNotification creates mail message correctly', function () {
    $creator  = User::factory()->create();
    $assignee = User::factory()->create();

    $notification = new TaskDeletedNotification('Deleted Task Title', $creator->id);
    $mailMessage = $notification->toMail($assignee);

    expect($mailMessage->subject)->toBe("Task Deleted: \"Deleted Task Title\"");
    expect($mailMessage->view)->toBe('emails.task_deleted');
});

test('TeamJoinRequestedNotification creates mail message correctly', function () {
    $owner = User::factory()->create();
    $team  = Team::create(['name' => 'Test Team']);
    $team->users()->attach($owner->id, ['role' => 'owner', 'status' => 'accepted', 'joined_at' => now()]);
    $requester = User::factory()->create();

    $notification = new TeamJoinRequestedNotification($team, $requester);
    $mailMessage = $notification->toMail($owner);

    expect($mailMessage->subject)->toBe("Join Request for Team: \"Test Team\"");
    expect($mailMessage->view)->toBe('emails.team_join_requested');
});

test('TeamMemberRoleUpdatedNotification creates mail message correctly', function () {
    $team  = Team::create(['name' => 'Test Team']);
    $member = User::factory()->create();

    $notification = new \App\Notifications\TeamMemberRoleUpdatedNotification($team, 'admin');
    $mailMessage = $notification->toMail($member);

    expect($mailMessage->subject)->toBe("Role Updated in Team: \"Test Team\"");
    expect($mailMessage->view)->toBe('emails.team_member_role_updated');
});

test('TeamMemberKickedNotification creates mail message correctly', function () {
    $team  = Team::create(['name' => 'Test Team']);
    $member = User::factory()->create();

    $notification = new \App\Notifications\TeamMemberKickedNotification($team);
    $mailMessage = $notification->toMail($member);

    expect($mailMessage->subject)->toBe("Removed from Team: \"Test Team\"");
    expect($mailMessage->view)->toBe('emails.team_member_kicked');
});

test('updating member role sends TeamMemberRoleUpdatedNotification', function () {
    Notification::fake();
    Event::fake([\App\Events\TeamMemberRoleUpdated::class]);

    $owner = User::factory()->create();
    $team  = Team::create(['name' => 'Test Team']);
    $team->users()->attach($owner->id, ['role' => 'owner', 'status' => 'accepted', 'joined_at' => now()]);
    $member = User::factory()->create();
    $team->users()->attach($member->id, ['role' => 'member', 'status' => 'accepted', 'joined_at' => now()]);

    $response = $this->actingAs($owner)->post(route('teams.role.update', $team), [
        'user_id' => $member->id,
        'role' => 'admin',
    ]);

    $response->assertRedirect(route('teams.index'));
    Event::assertDispatched(\App\Events\TeamMemberRoleUpdated::class);
});

test('kicking member sends TeamMemberKickedNotification', function () {
    Notification::fake();
    Event::fake([\App\Events\TeamMemberKicked::class]);

    $owner = User::factory()->create();
    $team  = Team::create(['name' => 'Test Team']);
    $team->users()->attach($owner->id, ['role' => 'owner', 'status' => 'accepted', 'joined_at' => now()]);
    $member = User::factory()->create();
    $team->users()->attach($member->id, ['role' => 'member', 'status' => 'accepted', 'joined_at' => now()]);

    $response = $this->actingAs($owner)->post(route('teams.kick', $team), [
        'user_id' => $member->id,
    ]);

    $response->assertRedirect(route('teams.index'));
    Event::assertDispatched(\App\Events\TeamMemberKicked::class);
});
