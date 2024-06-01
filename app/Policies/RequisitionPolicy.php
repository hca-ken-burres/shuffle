<?php

namespace App\Policies;

use App\Models\Requisition;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RequisitionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // The index page will not work unless true is returned from this
    // public function viewAny(User $user): bool
    // {
    //     return $user->can('edit requisitions');
    // }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Requisition $requisition): bool
    {
        return $requisition->user()->is($user) ||
            $user->can('edit requisitions');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Requisition $requisition): bool
    {
        return $user->can('edit requisitions') || (
            $user->is($requisition->user) && $requisition->isDraft()
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Requisition $requisition): bool
    {
        return $user->can('edit requisitions') || (   
            $user->is($requisition->user)
            && $requisition->isDraft()
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Requisition $requisition): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Requisition $requisition): bool
    {
        //
    }
}
