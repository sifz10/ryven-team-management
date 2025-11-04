<?php

namespace App\Policies;

use App\Models\PersonalNote;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PersonalNotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonalNote $personalNote): bool
    {
        return $user->id === $personalNote->user_id;
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
    public function update(User $user, PersonalNote $personalNote): bool
    {
        return $user->id === $personalNote->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonalNote $personalNote): bool
    {
        return $user->id === $personalNote->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonalNote $personalNote): bool
    {
        return $user->id === $personalNote->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonalNote $personalNote): bool
    {
        return $user->id === $personalNote->user_id;
    }
}
