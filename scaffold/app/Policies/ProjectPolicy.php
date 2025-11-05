<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'club' || $user->role === 'admin';
    }

    public function view(User $user, Project $project): bool
    {
        return $this->isProjectOwner($user, $project) || $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'club';
    }

    public function update(User $user, Project $project): bool
    {
        return $this->isProjectOwner($user, $project);
    }

    public function delete(User $user, Project $project): bool
    {
        return $this->isProjectOwner($user, $project);
    }

    protected function isProjectOwner(User $user, Project $project): bool
    {
        if ($user->role !== 'club') {
            return false;
        }

        return $user->organizations()
            ->where('organizations.id', $project->organization_id)
            ->exists();
    }

    public function manageOrganization(User $user, Organization $organization): bool
    {
        return $user->role === 'club' && $user->organizations()->where('organizations.id', $organization->id)->exists();
    }

    public function sponsor(User $user, Project $project): bool
    {
        return $user->role === 'company';
    }
}
