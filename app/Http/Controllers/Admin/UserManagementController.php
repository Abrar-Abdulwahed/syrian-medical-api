<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Actions\GetUsersDataAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Builder;

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

    public function show()
    {
        
    }

    public function serviceProviderActivation(Request $request)
    {
        return $this->getUsersAction->__invoke($request, ['serviceProviderProfile'], UserType::SERVICE_PROVIDER->value);
    }
}
