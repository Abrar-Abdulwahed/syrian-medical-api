<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Actions\GetUsersDataAction;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Traits\PaginateResponseTrait;
use App\Http\Requests\Admin\UserActivationRequest;
use App\Notifications\AdminReviewNotificationMail;

class ApplicantController extends BaseAdminController
{
    use PaginateResponseTrait;
    public function __construct(protected GetUsersDataAction $getUsersAction)
    {
        $this->middleware('permission:accept_registration_request')->except('index');
    }

    public function index(Request $request)
    {
        $applicantsQuery = User::where(['type' => UserType::SERVICE_PROVIDER->value, 'activated' => 0]);
        return $this->getUsersAction->getData($request, ['serviceProviderProfile'], $applicantsQuery);
    }

    public function accept(UserActivationRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->forceFill(['activated' => 1])->save();

            // Notify service provider
            $user->notify(new AdminReviewNotificationMail(true));
            return $this->returnSuccess('Service Provider has been activated!');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function refuse(UserActivationRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            $this->removeDirectory($user->attachment_path);
            // Notify service provider
            $user->notify(new AdminReviewNotificationMail(false));
            return $this->returnSuccess('Service Provider has been deleted from database!');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
