<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Http\Traits\FileTrait;
use App\Actions\GetUsersDataAction;
use App\Http\Controllers\Controller;
use App\Http\Traits\PaginateResponseTrait;
use App\Http\Requests\Admin\UserActivationRequest;
use App\Notifications\AdminReviewNotificationMail;

class ApplicantController extends Controller
{
    use FileTrait, PaginateResponseTrait;
    public function __construct(protected GetUsersDataAction $getUsersAction)
    {
        $this->middleware(['auth:sanctum', 'activated', 'verified', 'is-admin']);
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
            // Notify service provider
            $user->notify(new AdminReviewNotificationMail(false));
            $this->removeDirectory($user->attachment_path);
            $user->delete();
            return $this->returnSuccess('Service Provider has been deleted from database!');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
