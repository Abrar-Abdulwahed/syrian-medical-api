<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Actions\GetUsersDataAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Admin\UserActivationRequest;

class UserManagementController extends Controller
{
    public function __construct(protected GetUsersDataAction $getUsersAction){}

    public function index(Request $request)
    {
        return $this->getUsersAction->__invoke($request, ['patientProfile', 'serviceProviderProfile']);
    }

    public function patients(Request $request)
    {
        return $this->getUsersAction->__invoke($request, ['patientProfile'], UserType::PATIENT->value);
    }

    public function serviceProviders(Request $request)
    {
        return $this->getUsersAction->__invoke($request, ['serviceProviderProfile'], UserType::SERVICE_PROVIDER->value);
    }

    public function show(User $user)
    {
        return $this->returnJSON(new UserResource($user->loadMissing(['patientProfile', 'serviceProviderProfile'])), 'User Data retrieved!');
    }

    public function ServiceProviderActivation(UserActivationRequest $request, User $user)
    {
        $user->forceFill(['activated' => $request->activated])->save();
        $msg = $request->activated ? 'Service Provider has been activated!' : 'Service Provider has been deactivated!';
        return $this->returnSuccess($msg);
    }
}
