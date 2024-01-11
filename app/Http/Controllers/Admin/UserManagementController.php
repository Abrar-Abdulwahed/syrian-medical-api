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
use App\Http\Traits\PaginateResponseTrait;
use App\Models\PendingUpdateProfileRequest;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Admin\UserActivationRequest;
use App\Notifications\AdminReviewNotificationMail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserManagementController extends Controller
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
            return $this->getUsersAction->__invoke($request, ['patientProfile'], $query);
        }

        else if($type === UserType::SERVICE_PROVIDER->value){
            $query = $query->where('type', $type);
            return $this->getUsersAction->__invoke($request, ['serviceProviderProfile'], $query);
        }

        // users in general
        return $this->getUsersAction->__invoke($request, ['patientProfile', 'serviceProviderProfile'], $query);
    }

    public function showRegistrationRequests(Request $request)
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

    public function accept(UserActivationRequest $request, $id)
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

    public function refuse(UserActivationRequest $request, $id)
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

    public function showUserProfileUpdateRequests(Request $request)
    {
        try {
            $pageSize = $request->per_page ?? 1;

            $pendingUpdates = PendingUpdateProfileRequest::paginate($pageSize);

            if ($pendingUpdates->isEmpty()) {
                return $this->returnSuccess('No pending updates found', 200);
            }

            $updates = $pendingUpdates->map(function ($pendingUpdate) {
                // $user = User::findOrFail($pendingUpdate->user_id);

                return [
                    'profile' => route('show.user', [$pendingUpdate->user_id]),
                    'user' => $pendingUpdate->user_id, //new UserResource($user),
                    'updates' => json_decode($pendingUpdate->updates, true)
                ];
            });
            [$meta, $links] = $this->paginateResponse($pendingUpdates);
            return $this->returnAllDataJSON($updates, $meta, $links, 'Pending updates retrieved successfully');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }


    // public function showUserProfileUpdateRequests()
    // {
    //     try {
    //         $pendingUpdates = PendingUpdateProfileRequest::all();

    //         if ($pendingUpdates->isEmpty()) {
    //             return $this->returnSuccess('No pending updates found', 200);
    //         }

    //         $updates = $pendingUpdates->map(function ($pendingUpdate) {
    //             return [
    //                 'user_id' => $pendingUpdate->user_id,
    //                 'updates' => json_decode($pendingUpdate->updates, true)
    //             ];
    //         });

    //         return $this->returnJSON($updates, 'All pending updates');
    //     } catch (\Exception $e) {
    //         return $this->returnWrong($e->getMessage());
    //     }
    // }

    public function acceptUserProfileUpdateRequests()
    {

    }
}
