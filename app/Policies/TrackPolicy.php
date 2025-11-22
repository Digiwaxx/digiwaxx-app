<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admin;
use App\Models\Tracks;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Track Authorization Policy
 *
 * Controls who can view, create, update, and delete tracks
 */
class TrackPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any tracks
     */
    public function viewAny($user): bool
    {
        // All authenticated users can view tracks
        return true;
    }

    /**
     * Determine if the user can view a specific track
     */
    public function view($user, Tracks $track): bool
    {
        // Users can view active tracks or tracks they uploaded
        return $track->active == 1 ||
               $this->isTrackOwner($user, $track) ||
               $this->isAdmin($user);
    }

    /**
     * Determine if the user can create tracks
     */
    public function create($user): bool
    {
        // Only admins and verified clients can create tracks
        if ($this->isAdmin($user)) {
            return true;
        }

        // Check if client is verified (not deleted, active)
        if (isset($user->active) && $user->active == 1 && $user->deleted == 0) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can update a track
     */
    public function update($user, Tracks $track): bool
    {
        // Admins can update any track
        if ($this->isAdmin($user)) {
            return true;
        }

        // Users can only update tracks they created
        return $this->isTrackOwner($user, $track);
    }

    /**
     * Determine if the user can delete a track
     */
    public function delete($user, Tracks $track): bool
    {
        // Only admins or the original uploader can delete
        return $this->isAdmin($user) || $this->isTrackOwner($user, $track);
    }

    /**
     * Determine if the user can restore a deleted track
     */
    public function restore($user, Tracks $track): bool
    {
        // Only admins can restore deleted tracks
        return $this->isAdmin($user);
    }

    /**
     * Check if user is the track owner
     */
    private function isTrackOwner($user, Tracks $track): bool
    {
        if (!isset($user->id) || !isset($track->addedby)) {
            return false;
        }

        return $user->id == $track->addedby;
    }

    /**
     * Check if user is an admin
     */
    private function isAdmin($user): bool
    {
        // Check if user is Admin model instance
        if ($user instanceof Admin) {
            return true;
        }

        // Check if user has admin role
        if (isset($user->user_role) && in_array($user->user_role, ['admin', 'super_admin'])) {
            return true;
        }

        return false;
    }
}
