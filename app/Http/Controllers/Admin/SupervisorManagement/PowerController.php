<?php

namespace App\Http\Controllers\Admin\SupervisorManagement;

use App\Models\Admin;
use App\Models\Permission;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Resources\PermissionResource;
use App\Http\Requests\Admin\AssignPermissionRequest;

class PowerController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:attach_detach_permission')->only('store');
    }

    public function index($id)
    {
        $supervisor = Admin::findOrFail($id);
        $permissions = Permission::all();

        $permissionResource = PermissionResource::collection($permissions);

        // add additional parameter(user) to resources
        $permissionResource->map(function ($i) use ($supervisor) {
            $i->hasPermission = $supervisor->hasPermission($i->name);
        });
        return $this->returnJSON($permissionResource, __('message.data_retrieved', ['item' => __('message.permissions')]));
    }

    public function store(AssignPermissionRequest $request, $id)
    {
        $user = Admin::findOrFail($id);
        $user->permissions()->sync($request->validated()['permissions']);
        return $this->returnSuccess('Permissions of this user saved successfully');
    }
}
