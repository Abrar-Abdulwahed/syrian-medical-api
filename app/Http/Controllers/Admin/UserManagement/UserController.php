<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Http\Traits\FileTrait;
use App\Actions\GetUsersDataAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Traits\PaginateResponseTrait;
use App\Http\Requests\Admin\UserActivationRequest;

class UserController extends Controller
{
    use FileTrait, PaginateResponseTrait;
    public function __construct(protected GetUsersDataAction $getUsersAction){
        $this->middleware(['auth:sanctum', 'activated', 'verified', 'is-admin']);
    }

    public function index(Request $request)
    {
        $type = $request->query('type');
        $query = User::query();

        if($type === UserType::PATIENT->value){
            $query = $query->where('type', $type);
            return $this->getUsersAction->getData($request, ['patientProfile'], $query);
        }

        else if($type === UserType::SERVICE_PROVIDER->value){
            $query = $query->where('type', $type);
            return $this->getUsersAction->getData($request, ['serviceProviderProfile'], $query);
        }

        // users in general
        return $this->getUsersAction->getData($request, ['patientProfile', 'serviceProviderProfile'], $query);
    }

    public function show($id)
    {
        try{
            $user = User::findOrFail($id);
            return $this->returnJSON(new UserResource($user->loadMissing(['patientProfile', 'serviceProviderProfile'])), 'User Data retrieved!');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function activation(UserActivationRequest $request, $id)
    {
        try{
            $user = User::findOrFail($id);
            $user->forceFill(['activated' => $request->activated])->save();
            $msg = $request->activated? 'Service Provider has been activated!' : 'Service Provider has been deactivated!';
            return $this->returnSuccess($msg);
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
