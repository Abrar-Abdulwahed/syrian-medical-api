<?php

namespace App\Http\Controllers\Admin\UserManagement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\PaginateResponseTrait;
use App\Models\PendingUpdateProfileRequest;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Notifications\AdminReviewProfileChangeNotification;


class ProfileUpdateRequests extends BaseAdminController
{
    use PaginateResponseTrait;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:accept_registration_request')->except('index');
    }

    public function index(Request $request)
    {
        try {
            $pageSize = $request->per_page ?? 10;

            $pendingChanges = PendingUpdateProfileRequest::paginate($pageSize);

            if ($pendingChanges->isEmpty()) {
                return $this->returnSuccess(__('message.no_found', ['item' => __('message.pending_requests')]), 200);
            }

            $changes = $pendingChanges->map(function ($pendingChange) {
                // $user = User::findOrFail($pendingChange->user_id);

                return [
                    'profile' => route('admin.show.user', [$pendingChange->user_id]),
                    'user_id' => $pendingChange->user_id, //new UserResource($user),
                    'changes' => json_decode($pendingChange->changes, true)
                ];
            });
            [$meta, $links] = $this->paginateResponse($pendingChanges);
            return $this->returnAllDataJSON($changes, $meta, $links, 'Pending changes retrieved successfully');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function accept(Request $request, PendingUpdateProfileRequest $pending)
    {
        DB::beginTransaction();
        try {
            $changes = json_decode($pending->changes, true);

            $userChanges = collect($changes)->only(['firstname', 'lastname', 'email'])->all();
            $profileChanges = collect($changes)->except(['firstname', 'lastname', 'email'])->all();

            $pending->user()->update($userChanges);
            $pending->user->serviceProviderProfile()->update($profileChanges);

            // Delete the pending change request
            $pending->delete();
            $pending->user->notify(new AdminReviewProfileChangeNotification(true));
            DB::commit();
            return $this->returnSuccess(__('message.accepted', ['item' => __('message.user_changes')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function refuse(Request $request, PendingUpdateProfileRequest $pending)
    {
        DB::beginTransaction();
        try {
            $pending->delete();
            $pending->user->notify(new AdminReviewProfileChangeNotification(false));
            DB::commit();
            return $this->returnSuccess(__('message.rejected', ['item' => __('message.user_changes')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }
}
