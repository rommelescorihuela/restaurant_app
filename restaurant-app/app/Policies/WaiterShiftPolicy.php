<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WaiterShift;
use Illuminate\Auth\Access\HandlesAuthorization;

class WaiterShiftPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WaiterShift');
    }

    public function view(AuthUser $authUser, WaiterShift $waiterShift): bool
    {
        return $authUser->can('View:WaiterShift');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WaiterShift');
    }

    public function update(AuthUser $authUser, WaiterShift $waiterShift): bool
    {
        return $authUser->can('Update:WaiterShift');
    }

    public function delete(AuthUser $authUser, WaiterShift $waiterShift): bool
    {
        return $authUser->can('Delete:WaiterShift');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:WaiterShift');
    }

    public function restore(AuthUser $authUser, WaiterShift $waiterShift): bool
    {
        return $authUser->can('Restore:WaiterShift');
    }

    public function forceDelete(AuthUser $authUser, WaiterShift $waiterShift): bool
    {
        return $authUser->can('ForceDelete:WaiterShift');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WaiterShift');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WaiterShift');
    }

    public function replicate(AuthUser $authUser, WaiterShift $waiterShift): bool
    {
        return $authUser->can('Replicate:WaiterShift');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WaiterShift');
    }

}