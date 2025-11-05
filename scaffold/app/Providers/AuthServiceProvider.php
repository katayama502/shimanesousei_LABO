<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Sponsorship;
use App\Policies\ProjectPolicy;
use App\Policies\SponsorshipPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Sponsorship::class => SponsorshipPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('manageOrganization', [ProjectPolicy::class, 'manageOrganization']);
        Gate::define('sponsor', [ProjectPolicy::class, 'sponsor']);
        Gate::define('message', [SponsorshipPolicy::class, 'message']);
        Gate::define('updateStatus', [SponsorshipPolicy::class, 'updateStatus']);
        Gate::define('admin-access', fn ($user) => $user->role === 'admin');
        Gate::define('club-only', fn ($user) => $user->role === 'club');
        Gate::define('company-only', fn ($user) => $user->role === 'company');
    }
}
