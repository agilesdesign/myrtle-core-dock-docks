<?php

namespace Myrtle\Core\Docks\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocksPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
		//
    }

	public function before(User $user)
	{
		if ($this->admin($user))
		{
			return true;
		}
	}

	public function admin(User $user)
	{
		return $user->allPermissions->contains(function($ability, $key) {
			return $ability->name === 'docks.admin';
		});
	}

    public function view(User $user)
	{
		return $user->allPermissions->contains(function($ability, $key) use ($user) {
			return $ability->name === 'docks.view' || $this->admin($user);
		});
	}

	public function install(User $user)
	{
		return $user->allPermissions->contains(function($ability, $key) {
			return $ability->name === 'docks.install';
		});
	}

	public function uninstall(User $user)
	{
		return $user->allPermissions->contains(function($ability, $key) {
			return $ability->name === 'docks.uninstall';
		});
	}

	public function enable(User $user)
	{
		return $user->allPermissions->contains(function($ability, $key) {
			return $ability->name === 'docks.enable';
		});
	}

	public function disable(User $user)
	{
		return $user->allPermissions->contains(function($ability, $key) {
			return $ability->name === 'docks.disable';
		});
	}
}
