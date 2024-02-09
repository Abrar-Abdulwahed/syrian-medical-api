<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Actions\SearchAction;
use App\Http\Resources\Applicant\ApplicantListResource;
use App\Http\Requests\Admin\UserActivationRequest;
use App\Notifications\AdminReviewNotificationMail;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Resources\Applicant\ApplicantReviewResource;

class ApplicantController extends BaseAdminController
{
    public function __construct(protected SearchAction $searchAction)
    {
        parent::__construct();
        $this->middleware('permission:accept_registration_request')->except('index');
    }

    public function index(Request $request)
    {
        $query = User::where(['type' => UserType::SERVICE_PROVIDER->value, 'activated' => 0]);
        // filter by search
        $query = $this->searchAction->searchAction($query, $request->query('search'));
        return $this->returnJSON(ApplicantListResource::collection($query->get()), __('message.data_retrieved', ['item' => __('message.registration_requests')]));
    }

    public function accept(UserActivationRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->forceFill(['activated' => 1])->save();

            // Notify service provider
            $user->notify(new AdminReviewNotificationMail(true));
            return $this->returnSuccess(__('message.activated', ['item' => __('message.registration_request')]));
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
            return $this->returnSuccess(__('message.data_deleted'), ['item' => __('message.registration_request')]);
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function show(User $user)
    {
        return $this->returnJSON(new ApplicantReviewResource($user));
    }
}
