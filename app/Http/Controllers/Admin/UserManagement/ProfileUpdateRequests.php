<?php

namespace App\Http\Controllers\Admin\UserManagement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PendingUpdateProfileRequest;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Resources\PendingUpdateProfileRequestResource;
use App\Notifications\AdminReviewProfileChangeNotification;


class ProfileUpdateRequests extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:accept_registration_request')->except('index');
    }

    public function index()
    {
        try {
            $pendingChanges = PendingUpdateProfileRequest::get();

            if ($pendingChanges->isEmpty()) {
                return $this->returnSuccess(__('message.no_found', ['item' => __('message.pending_requests')]), 200);
            }
            return $this->returnJSON(PendingUpdateProfileRequestResource::collection($pendingChanges), 'Pending changes retrieved successfully');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function show(PendingUpdateProfileRequest $pending)
    {
        return $this->returnJSON(new PendingUpdateProfileRequestResource($pending), 'Pending changes retrieved successfully');
    }

    public function accept(PendingUpdateProfileRequest $pending)
    {
        DB::beginTransaction();
        try {
            $changes = json_decode($pending->changes, true);

            $userChanges = collect($changes)->only(['firstname', 'lastname', 'email'])->all();
            $profileChanges = collect($changes)->except(['firstname', 'lastname', 'email'])->all();
            if (!$pending->user->isActivated()) {
                DB::rollBack();
                return $this->returnWrong(__('message.user_not_activated'));
            }

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

    public function refuse(PendingUpdateProfileRequest $pending)
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
