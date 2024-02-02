<?php

namespace App\Http\Controllers\Admin\SupervisorManagement;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Actions\GetUsersDataAction;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Resources\AdminResource;
use App\Http\Requests\Admin\SupervisorStoreRequest;
use App\Http\Requests\Admin\SupervisorUpdateRequest;

class SupervisorController extends BaseAdminController
{
    public function __construct(protected GetUsersDataAction $getUsersAction)
    {
        $this->middleware('permission:add_supervisor')->only('store');
    }

    public function index(Request $request)
    {
        $pageSize = $request->per_page ?? 10;
        $supervisors = Admin::supervisors()->paginate($pageSize);
        [$meta, $links] = $this->paginateResponse($supervisors);
        return $this->returnAllDataJSON(AdminResource::collection($supervisors), $meta, $links, 'Data retrieved successfully');
    }

    public function store(SupervisorStoreRequest $request)
    {
        Admin::create($request->validated());
        return $this->returnSuccess('Supervisor added successfully');
    }

    public function show(Admin $supervisor)
    {
        return $this->returnJSON(new AdminResource($supervisor), 'User data retrieved successfully');
    }

    public function update(SupervisorUpdateRequest $request, Admin $supervisor)
    {
        $supervisor->update($request->validated());
        return $this->returnSuccess('Supervisor data updated successfully');
    }

    public function destroy(Admin $supervisor)
    {
        $supervisor->delete();
        return $this->returnSuccess('Supervisor has been deleted successfully');
    }

    public function activate(Request $request, Admin $supervisor)
    {
        try {
            $supervisor->forceFill(['activated' => 1])->save();
            return $this->returnSuccess('This supervisor has been activated!');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function deactivate(Request $request, Admin $supervisor)
    {
        try {
            $supervisor->forceFill(['activated' => 0])->save();
            return $this->returnSuccess('This supervisor has been deactivated!');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
