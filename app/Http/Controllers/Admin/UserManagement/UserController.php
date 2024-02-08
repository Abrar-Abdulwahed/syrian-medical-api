<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Http\Traits\FileTrait;
use App\Actions\GetUsersDataAction;
use App\Actions\SearchAction;
use App\Http\Resources\UserResource;
use App\Http\Traits\PaginateResponseTrait;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Requests\Admin\UserActivationRequest;

class UserController extends BaseAdminController
{
    use FileTrait, PaginateResponseTrait;
    public function __construct(protected GetUsersDataAction $getUsersAction, protected SearchAction $searchAction)
    {
        parent::__construct();
        $this->middleware('permission:block_user')->only('activation');
    }

    public function index(Request $request)
    {
        $query = User::query();
        // filter by search
        $query = $this->searchAction->searchAction($query, $request->query('search'));
        return $this->getUsersAction->getData($request, [], $query);
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->returnJSON(new UserResource($user->loadMissing(['patientProfile', 'serviceProviderProfile'])), __('message.data_retrieved', ['item' => __('message.user')]));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function activation(UserActivationRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->forceFill(['activated' => $request->activated])->save();
            $msg = $request->activated ? 'Service Provider has been activated!' : 'Service Provider has been deactivated!';
            return $this->returnSuccess($msg);
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
