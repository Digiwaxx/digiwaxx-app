<?php

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Logo Authorization Policy
 *
 * Controls who can manage company logos
 */
class LogoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any logos
     */
    public function viewAny($user): bool
    {
        // All authenticated users can view logos
        return true;
    }

    /**
     * Determine if the user can view a specific logo
     */
    public function view($user, $logo): bool
    {
        // All authenticated users can view individual logos
        return true;
    }

    /**
     * Determine if the user can create logos
     */
    public function create($user): bool
    {
        // Only admins can create logos
        return $this->isAdmin($user);
    }

    /**
     * Determine if the user can update a logo
     */
    public function update($user, $logo): bool
    {
        // Only admins can update logos
        return $this->isAdmin($user);
    }

    /**
     * Determine if the user can delete a logo
     */
    public function delete($user, $logo): bool
    {
        // Only admins can delete logos
        return $this->isAdmin($user);
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
