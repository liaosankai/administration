<?php

namespace Larapacks\Administration\Http\Controllers;

use Larapacks\Administration\Http\Requests\UserRoleRequest;
use Larapacks\Authorization\Authorization;

class UserRoleController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:admin.users,admin.roles');
    }

    /**
     * Adds roles to the specified user.
     *
     * @param UserRoleRequest $request
     * @param int             $userId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRoleRequest $request, $userId)
    {
        $user = Authorization::user()->findOrFail($userId);

        if ($request->persist($user)) {
            flash()->success('Successfully added roles.');

            return redirect()->route('admin.users.show', [$userId]);
        }

        flash()->error("You didn't select any roles.");

        return redirect()->route('admin.users.show', [$userId]);
    }

    /**
     * Removes the specified role from the specified user.
     *
     * @param int $userId
     * @param int $roleId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($userId, $roleId)
    {
        $user = Authorization::user()->findOrFail($userId);

        $role = $user->roles()->findOrFail($roleId);

        // We'll grab all users that are administrators.
        $users = Authorization::user()->whereHas('roles', function ($q) use ($role) {
            return $q->whereName($role->name);
        })->count();

        // If the user is an administrator and the role being removed is an
        // administrator role, we need to verify that there are other
        // administrators in the system before allowing the removal.
        if ($user->isAdministrator() && $role->isAdministrator() && $users <= 1) {
            flash()->important()->error(
                'This account is the only administrator. You must have one other administrator.'
            );
        } elseif ($user->roles()->detach($role)) {
            flash()->success('Successfully removed role.');
        } else {
            flash()->error('There was an issue removing this role. Please try again.');
        }

        return redirect()->back();
    }
}
