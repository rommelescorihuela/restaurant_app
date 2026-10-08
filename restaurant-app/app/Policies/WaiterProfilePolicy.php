<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WaiterProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class WaiterProfilePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WaiterProfile');
    }

    public function view(AuthUser $authUser, WaiterProfile $waiterProfile): bool
    {
        return $authUser->can('View:WaiterProfile');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WaiterProfile');
    }

    public function update(AuthUser $authUser, WaiterProfile $waiterProfile): bool
    {
        return $authUser->can('Update:WaiterProfile');
    }

    public function delete(AuthUser $authUser, WaiterProfile $waiterProfile): bool
    {
        return $authUser->can('Delete:WaiterProfile');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:WaiterProfile');
    }

    public function restore(AuthUser $authUser, WaiterProfile $waiterProfile): bool
    {
        return $authUser->can('Restore:WaiterProfile');
    }

    public function forceDelete(AuthUser $authUser, WaiterProfile $waiterProfile): bool
    {
        return $authUser->can('ForceDelete:WaiterProfile');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WaiterProfile');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WaiterProfile');
    }

    public function replicate(AuthUser $authUser, WaiterProfile $waiterProfile): bool
    {
        return $authUser->can('Replicate:WaiterProfile');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WaiterProfile');
    }

}