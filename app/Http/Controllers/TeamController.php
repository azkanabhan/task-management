<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\TeamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function __construct(
        private readonly TeamService $teamService,
        private readonly ActivityLogService $activityLogService,
    ) {
    }

    public function index(Request $request): View
    {
        $userId = (int) $request->user()->id;

        $myTeams = $this->teamService->getUserTeams($userId);
        $joinableTeams = $this->teamService->getJoinableTeams($userId);
        $pendingRequestTeamIds = $this->teamService->getPendingRequestTeamIds($userId);
        $ownedTeams = $this->teamService->getOwnedTeamsWithMembersAndRequests($userId);

        return view('teams.index', compact('myTeams', 'joinableTeams', 'pendingRequestTeamIds', 'ownedTeams'));
    }

    public function create(): View
    {
        return view('teams.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->teamService->validateTeamData($request);
        $team = $this->teamService->createTeamForUser($request->user(), $validated);
        $this->activityLogService->log(
            userId: (int) $request->user()->id,
            action: 'team.created',
            description: "Created team: {$team->name}",
            subject: $team
        );

        return redirect()->route('teams.index')->with('success', 'Team created successfully.');
    }

    public function join(Request $request, Team $team): RedirectResponse
    {
        $this->teamService->joinTeam($request->user(), $team);
        $this->activityLogService->log(
            userId: (int) $request->user()->id,
            action: 'team.join.requested',
            description: "Requested to join team: {$team->name}",
            subject: $team
        );

        return redirect()->route('teams.index')->with('success', 'Join request sent successfully.');
    }

    public function approve(Request $request, Team $team): RedirectResponse
    {
        Gate::authorize('manageMembership', $team);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $this->teamService->approveRequest($team, (int) $validated['user_id']);
        $member = User::query()->find($validated['user_id']);
        $this->activityLogService->log(
            userId: (int) $request->user()->id,
            action: 'team.member.approved',
            description: "Approved member request for {$member?->name} in team: {$team->name}",
            subject: $team,
            properties: ['member_id' => (int) $validated['user_id']]
        );

        return redirect()->route('teams.index')->with('success', 'Member request approved.');
    }

    public function reject(Request $request, Team $team): RedirectResponse
    {
        Gate::authorize('manageMembership', $team);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $this->teamService->rejectRequest($team, (int) $validated['user_id']);
        $member = User::query()->find($validated['user_id']);
        $this->activityLogService->log(
            userId: (int) $request->user()->id,
            action: 'team.member.rejected',
            description: "Rejected member request for {$member?->name} in team: {$team->name}",
            subject: $team,
            properties: ['member_id' => (int) $validated['user_id']]
        );

        return redirect()->route('teams.index')->with('success', 'Member request rejected.');
    }

    public function updateRole(Request $request, Team $team): RedirectResponse
    {
        Gate::authorize('manageMembership', $team);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'role' => ['required', 'in:admin,member'],
        ]);

        $this->teamService->updateMemberRole($team, (int) $validated['user_id'], $validated['role']);
        $member = User::query()->find($validated['user_id']);
        $this->activityLogService->log(
            userId: (int) $request->user()->id,
            action: 'team.member.role.updated',
            description: "Changed {$member?->name} role to {$validated['role']} in team: {$team->name}",
            subject: $team,
            properties: [
                'member_id' => (int) $validated['user_id'],
                'role' => $validated['role'],
            ]
        );

        return redirect()->route('teams.index')->with('success', 'Member role updated.');
    }

    public function kick(Request $request, Team $team): RedirectResponse
    {
        Gate::authorize('manageMembership', $team);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $this->teamService->kickMember($team, (int) $validated['user_id']);
        $member = User::query()->find($validated['user_id']);
        $this->activityLogService->log(
            userId: (int) $request->user()->id,
            action: 'team.member.kicked',
            description: "Removed {$member?->name} from team: {$team->name}",
            subject: $team,
            properties: ['member_id' => (int) $validated['user_id']]
        );

        return redirect()->route('teams.index')->with('success', 'Member removed from team.');
    }
}
