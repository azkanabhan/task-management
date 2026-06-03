<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class TeamInvitationController extends Controller
{
    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {
    }

    /**
     * Invite a user to a team.
     */
    public function store(Request $request, Team $team): RedirectResponse
    {
        Gate::authorize('manageMembership', $team);

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'in:member,admin'],
        ]);

        $email = strtolower($validated['email']);

        // Check if the user is already a member of the team
        $isMember = $team->users()->where('email', $email)->wherePivot('status', 'accepted')->exists();
        if ($isMember) {
            return redirect()->back()->withErrors(['email' => 'User is already a member of this team.']);
        }

        // Check if the user has a pending join request
        $hasPendingRequest = $team->users()->where('email', $email)->wherePivot('status', 'pending')->exists();
        if ($hasPendingRequest) {
            return redirect()->back()->withErrors(['email' => 'User has a pending request to join this team.']);
        }

        // Check if the email already has a pending invitation
        $hasPendingInvite = $team->invitations()->where('email', $email)->exists();
        if ($hasPendingInvite) {
            return redirect()->back()->withErrors(['email' => 'An invitation has already been sent to this email address.']);
        }

        // Create invitation
        $invitation = $team->invitations()->create([
            'email' => $email,
            'role' => $validated['role'],
            'token' => Str::random(40),
        ]);

        // Send notification email
        Notification::route('mail', $email)->notify(
            new TeamInvitationNotification($invitation, $request->user()->name)
        );

        $this->activityLogService->log(
            userId: (int) $request->user()->id,
            action: 'team.invitation.sent',
            description: "Invited {$email} to join team {$team->name} as {$validated['role']}",
            subject: $team,
            properties: ['invited_email' => $email, 'role' => $validated['role']]
        );

        return redirect()->route('teams.index')->with('success', 'Invitation sent successfully.');
    }

    /**
     * Accept a team invitation.
     */
    public function accept(Request $request, TeamInvitation $invitation): RedirectResponse
    {
        if (strtolower($request->user()->email) !== strtolower($invitation->email)) {
            abort(403, 'This invitation is not for your email address.');
        }

        $team = $invitation->team;
        $userId = (int) $request->user()->id;

        DB::transaction(function () use ($team, $userId, $invitation) {
            $existingPivot = DB::table('team_users')
                ->where('team_id', $team->id)
                ->where('user_id', $userId)
                ->first();

            if ($existingPivot) {
                DB::table('team_users')
                    ->where('team_id', $team->id)
                    ->where('user_id', $userId)
                    ->update([
                        'role' => $invitation->role,
                        'status' => 'accepted',
                        'joined_at' => now(),
                        'updated_at' => now(),
                    ]);
            } else {
                $team->users()->attach($userId, [
                    'role' => $invitation->role,
                    'status' => 'accepted',
                    'joined_at' => now(),
                ]);
            }

            $invitation->delete();
        });

        $this->activityLogService->log(
            userId: $userId,
            action: 'team.invitation.accepted',
            description: "Accepted invitation to join team {$team->name}",
            subject: $team
        );

        return redirect()->route('teams.index')->with('success', "You have joined team {$team->name}!");
    }

    /**
     * Decline a team invitation.
     */
    public function decline(Request $request, TeamInvitation $invitation): RedirectResponse
    {
        if (strtolower($request->user()->email) !== strtolower($invitation->email)) {
            abort(403, 'This invitation is not for your email address.');
        }

        $team = $invitation->team;
        $invitation->delete();

        $this->activityLogService->log(
            userId: (int) $request->user()->id,
            action: 'team.invitation.declined',
            description: "Declined invitation to join team {$team->name}",
            subject: $team
        );

        return redirect()->route('teams.index')->with('success', 'Invitation declined.');
    }

    /**
     * Cancel/revoke a team invitation.
     */
    public function destroy(Request $request, TeamInvitation $invitation): RedirectResponse
    {
        $team = $invitation->team;
        Gate::authorize('manageMembership', $team);

        $invitation->delete();

        $this->activityLogService->log(
            userId: (int) $request->user()->id,
            action: 'team.invitation.revoked',
            description: "Cancelled invitation for {$invitation->email} to join team {$team->name}",
            subject: $team,
            properties: ['invited_email' => $invitation->email]
        );

        return redirect()->route('teams.index')->with('success', 'Invitation revoked.');
    }
}
