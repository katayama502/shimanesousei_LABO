<?php

namespace App\Policies;

use App\Models\Sponsorship;
use App\Models\User;

class SponsorshipPolicy
{
    public function view(User $user, Sponsorship $sponsorship): bool
    {
        return $this->isCompanyMember($user, $sponsorship) || $this->isProjectOwner($user, $sponsorship);
    }

    public function updateStatus(User $user, Sponsorship $sponsorship): bool
    {
        return $user->role === 'admin' || $this->isCompanyMember($user, $sponsorship) || $this->isProjectOwner($user, $sponsorship);
    }

    public function message(User $user, Sponsorship $sponsorship): bool
    {
        return $this->view($user, $sponsorship);
    }

    public function sponsor(User $user): bool
    {
        return $user->role === 'company';
    }

    protected function isCompanyMember(User $user, Sponsorship $sponsorship): bool
    {
        if ($user->role !== 'company') {
            return false;
        }

        return $user->organizations()->where('organizations.id', $sponsorship->company_org_id)->exists();
    }

    protected function isProjectOwner(User $user, Sponsorship $sponsorship): bool
    {
        if ($user->role !== 'club') {
            return false;
        }

        return $user->organizations()->where('organizations.id', $sponsorship->project->organization_id)->exists();
    }
}
