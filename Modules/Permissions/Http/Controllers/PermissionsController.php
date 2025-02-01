<?php

namespace Modules\Permissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\APIAuth\Entities\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if(! auth('api')->user()->can('index_roles_and_permissions'))
            abort(401, 'You don\'t have permission to do this action');

        return response([
            'message' => __('success_action', ['model' => __('permissions_roles'), 'action' => __('retrieved')]),
            'permissions' => Permission::all(),
            'roles' => Role::all(),
        ]);
    }

    public function showRole(Role $role)
    {
        if (!auth('api')->user()->can('show_role'))
            abort(401, 'You don\'t have permission to do this action');

        $role['permissions'] = $role->permissions;

        return response([
            'message' => __('success_action', ['model' => __('role'), 'action' => __('retrieved')]),
            'roles' => $role
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function storeRole(Request $request)
    {
        if (!auth('api')->user()->can('create_role'))
            abort(401, 'You don\'t have permission to do this action');

        $request->validate([
            'name' => 'required|min:2|max:191'
        ]);

        $role = Role::create(['name' => $request->name]);

        return response([
            'message' => __('success_action', ['model' => __('role'), 'action' => __('created')]),
            'roles' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateRole(Request $request, Role $role)
    {
        if (!auth('api')->user()->can('update_role'))
            abort(401, 'You don\'t have permission to do this action');

        $request->validate([
            'name' => 'required|min:2|max:191'
        ]);

        $role->update(['name' => $request->name]);

        return response([
            'message' => __('success_action', ['model' => __('role'), 'action' => __('updated')]),
            'roles' => $role->fresh()
        ]);
    }

    public function assignPermissionsToRole(Request $request, Role $role)
    {
        if (!auth('api')->user()->can('assign_permssion_to_role'))
            abort(401, 'You don\'t have permission to do this action');

        $request->validate([
            'premssions' => 'required|array',
            'permissions.*' => 'required|numeric'
        ]);

        $role->givePermissionTo($request->premssions);

        $role->refresh();

        return response([
            'message' => __('success_action', ['model' => __('permissions'), 'action' => __('assigned')]),
            'roles' => $role
        ]);
    }

    public function assignRoleToUser(Request $request)
    {
        if (!auth('api')->user()->can('assign_role_to_user'))
            abort(401, 'You don\'t have permission to do this action');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($request->user_id)->assignRole(Role::findOrFail($request->role_id));

        return response([
            'message' => __('success_action', ['model' => __('role'), 'action' => __('assigned')]),
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroyRole(Role $role)
    {
        if (!auth('api')->user()->can('delete_role'))
            abort(401, 'You don\'t have permission to do this action');

        $role->delete();

        return response([
            'message' => __('success_action', ['model' => __('role'), 'action' => __('deleted')])
        ]);
    }
}
