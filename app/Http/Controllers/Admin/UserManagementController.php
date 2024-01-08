<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Http\Traits\FileTrait;
use App\Actions\GetUsersDataAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Admin\UserActivationRequest;
use App\Notifications\AdminReviewNotificationMail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserManagementController extends Controller
{
    use FileTrait;
    public function __construct(protected GetUsersDataAction $getUsersAction){}

    public function index(Request $request)
    {
        $type = $request->query('type');
        $query = User::query();

        if($type === UserType::PATIENT->value){
            $query = $query->where('type', $type);
            return $this->getUsersAction->__invoke($request, ['patientProfile'], $query);
        }

        else if($type === UserType::SERVICE_PROVIDER->value){
            $query = $query->where('type', $type);
            return $this->getUsersAction->__invoke($request, ['serviceProviderProfile'], $query);
        }

        // users in general
        return $this->getUsersAction->__invoke($request, ['patientProfile', 'serviceProviderProfile'], $query);
    }

    public function listApplicant(Request $request)
    {
        $applicantsQuery = User::where(['type' => UserType::SERVICE_PROVIDER->value, 'activated' => 0]);
        return $this->getUsersAction->__invoke($request, ['serviceProviderProfile'], $applicantsQuery);
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

    public function ServiceProviderAccept(UserActivationRequest $request, $id)
    {
        try{
            $user = User::findOrFail($id);
            $user->forceFill(['activated' => 1])->save();

            // Notify service provider
            Notification::send($user, new AdminReviewNotificationMail(true));
            return $this->returnSuccess('Service Provider has been activated!');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function ServiceProviderRefuse(UserActivationRequest $request, $id)
    {
        try{
            $user = User::findOrFail($id);
            // Notify service provider
            Notification::send($user, new AdminReviewNotificationMail(false));
            $this->removeDirectory($user->attachment_path);
            $user->delete();
            return $this->returnSuccess('Service Provider has been deleted from database!');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
