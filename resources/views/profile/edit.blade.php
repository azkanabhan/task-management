<x-app-layout>
    <div class="page-container">
        <x-page-header title="Profile Settings" description="Manage your account settings and preferences." />

        <div class="max-w-3xl space-y-6">
            {{-- Profile Information --}}
            <div class="card p-6 sm:p-8 animate-fade-in">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Update Password --}}
            <div class="card p-6 sm:p-8 animate-fade-in">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Delete Account --}}
            <div class="rounded-xl border border-red-200 dark:border-red-500/20 bg-red-50/50 dark:bg-red-500/5 p-6 sm:p-8 animate-fade-in">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
