<?php

namespace App\Services;

use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {
    }

    public function updateProfile(ProfileUpdateRequest $request, User $user): RedirectResponse
    {
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $dirty = $user->getDirty();
        $user->save();

        if (! empty($dirty)) {
            $this->activityLogService->log(
                userId: (int) $user->id,
                action: 'profile.updated',
                description: 'Updated profile details.',
                subject: $user,
                properties: ['changes' => array_keys($dirty)]
            );
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function deleteAccount(Request $request, User $user): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $this->activityLogService->log(
            userId: (int) $user->id,
            action: 'profile.deleted',
            description: "Deleted user account: {$user->email}"
        );

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

