<?php

namespace Myrtle\Core\Docks\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Myrtle\Docks\DocksDock;

class DocksDockPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user has access to Docks Dock Administrative Routes
     *
     * @param  \App\User $user
     * @return bool
     */
    public function accessAdmin(User $user)
    {
        return $user->allPermissions->contains(function ($ability) use ($user) {
            return $ability->name === DocksDock::class . '.access-admin';
        });
    }

    /**
     * Determine if the user can install Docks
     *
     * @param  \App\User $user
     * @return bool
     */
    public function install(User $user)
    {
        return $user->allPermissions->contains(function ($ability) use ($user) {
            return $ability->name === DocksDock::class . '.install';
        });
    }

    /**
     * Determine if the user can uninstall Docks
     *
     * @param  \App\User $user
     * @return bool
     */
    public function uninstall(User $user)
    {
        return $user->allPermissions->contains(function ($ability) use ($user) {
            return $ability->name === DocksDock::class . '.uninstall';
        });
    }

    /**
     * Determine if the user can enable Docks
     *
     * @param  \App\User $user
     * @return bool
     */
    public function enable(User $user)
    {
        return $user->allPermissions->contains(function ($ability) use ($user) {
            return $ability->name === DocksDock::class . '.enable';
        });
    }

    /**
     * Determine if the user can disable Docks
     *
     * @param  \App\User $user
     * @return bool
     */
    public function disable(User $user)
    {
        return $user->allPermissions->contains(function ($ability) use ($user) {
            return $ability->name === DocksDock::class . '.disable';
        });
    }

    /**
     * Determine if the user can view Docks
     *
     * @param  \App\User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->allPermissions->contains(function ($ability) use ($user) {
            return $ability->name === DocksDock::class . '.view';
        });
    }

    /**
     * Determine if the user can edit Dock Settings
     *
     * @param  \App\User $user
     * @return bool
     */
    public function editSettings(User $user)
    {
        return $user->allPermissions->contains(function ($ability) {
            return $ability->name === DocksDock::class . '.edit-settings';
        });
    }

    /**
     * Determine if the user can view Dock Settings
     *
     * @param  \App\User $user
     * @return bool
     */
    public function viewSettings(User $user)
    {
        return $user->allPermissions->contains(function ($ability) {
            return $ability->name === DocksDock::class . '.view';
        });
    }

    /**
     * Determine if the user can edit Dock Permissions
     *
     * @param  \App\User $user
     * @return bool
     */
    public function editPermissions(User $user)
    {
        return $user->allPermissions->contains(function ($ability) {
            return $ability->name === DocksDock::class . '.edit-settings';
        });
    }

    /**
     * Determine if the user can view Dock Permissions
     *
     * @param  \App\User $user
     * @return bool
     */
    public function viewPermissions(User $user)
    {
        return $user->allPermissions->contains(function ($ability) {
            return $ability->name === DocksDock::class . '.view';
        });
    }
}
