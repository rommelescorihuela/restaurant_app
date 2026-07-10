<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WasteRecord;
use Illuminate\Auth\Access\HandlesAuthorization;

class WasteRecordPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WasteRecord');
    }

    public function view(AuthUser $authUser, WasteRecord $wasteRecord): bool
    {
        return $authUser->can('View:WasteRecord');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WasteRecord');
    }

    public function update(AuthUser $authUser, WasteRecord $wasteRecord): bool
    {
        return $authUser->can('Update:WasteRecord');
    }

    public function delete(AuthUser $authUser, WasteRecord $wasteRecord): bool
    {
        return $authUser->can('Delete:WasteRecord');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:WasteRecord');
    }

    public function restore(AuthUser $authUser, WasteRecord $wasteRecord): bool
    {
        return $authUser->can('Restore:WasteRecord');
    }

    public function forceDelete(AuthUser $authUser, WasteRecord $wasteRecord): bool
    {
        return $authUser->can('ForceDelete:WasteRecord');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WasteRecord');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WasteRecord');
    }

    public function replicate(AuthUser $authUser, WasteRecord $wasteRecord): bool
    {
        return $authUser->can('Replicate:WasteRecord');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WasteRecord');
    }

}