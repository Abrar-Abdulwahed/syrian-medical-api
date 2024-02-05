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
        parent::__construct();
        $this->middleware('permission:add_supervisor')->only('store');
    }

    public function index(Request $request)
    {
        $pageSize = $request->per_page ?? 10;
        $supervisors = Admin::supervisors()->paginate($pageSize);
        [$meta, $links] = $this->paginateResponse($supervisors);
        return $this->returnAllDataJSON(AdminResource::collection($supervisors), $meta, $links, __('message.data_retrieved', ['item' => __('message.supervisors')]));
    }

    public function store(SupervisorStoreRequest $request)
    {
        Admin::create($request->validated());
        return $this->returnSuccess(__('message.data_added', ['item' => __('message.supervisor')]));
    }

    public function show(Admin $supervisor)
    {
        return $this->returnJSON(new AdminResource($supervisor),  __('message.data_retrieved', ['item' => __('message.supervisor')]));
    }

    public function update(SupervisorUpdateRequest $request, Admin $supervisor)
    {
        $supervisor->update($request->validated());
        return $this->returnSuccess(__('message.data_updated', ['item' => __('message.supervisor')]));
    }

    public function destroy(Admin $supervisor)
    {
        $supervisor->delete();
        return $this->returnSuccess(__('message.data_deleted', ['item' => __('message.supervisor')]));
    }

    public function activate(Request $request, Admin $supervisor)
    {
        try {
            $supervisor->forceFill(['activated' => 1])->save();
            return $this->returnSuccess(__('message.activated', ['item' => __('message.supervisor')]));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function deactivate(Request $request, Admin $supervisor)
    {
        try {
            $supervisor->forceFill(['activated' => 0])->save();
            return $this->returnSuccess(__('message.deactivated', ['item' => __('message.supervisor')]));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
