<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Models\User;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Actions\SearchAction;
use App\Http\Traits\FileTrait;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Traits\PaginateResponseTrait;
use App\Http\Requests\Admin\UserActivationRequest;
use App\Http\Controllers\Admin\BaseAdminController;

class UserController extends BaseAdminController
{
    use FileTrait, PaginateResponseTrait;
    public function __construct(protected SearchAction $searchAction)
    {
        parent::__construct();
        $this->middleware('permission:block_user')->only('activation');
    }

    public function index(Request $request)
    {
        $query = User::query();
        // filter by search
        $query = $this->searchAction->searchAction($query, $request->query('search'));
        return $this->returnJSON(UserResource::collection($query->get()), __('message.data_retrieved', ['item' => __('message.users')]));
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->isPatient())
                $user->loadCount([
                    'reservations as total_orders_count',
                    'reservations as completed_orders_count' => fn (Builder $query) => $query->where('status', OrderStatus::ACCEPTED->value),
                    'reservations as canceled_orders_count' => fn (Builder $query)  => $query->where('status', OrderStatus::CANCELED->value),
                ]);
            if ($user->isServiceProvider())
                $user->loadCount([
                    'orders as total_orders_count',
                    'orders as completed_orders_count' => fn (Builder $query) => $query->where('status', OrderStatus::ACCEPTED->value),
                    'orders as canceled_orders_count' => fn (Builder $query)  => $query->where('status', OrderStatus::CANCELED->value),
                ]);
            return $this->returnJSON(new UserResource($user->loadMissing(['patientProfile', 'serviceProviderProfile'])), __('message.data_retrieved', ['item' => __('message.user')]));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function activation(UserActivationRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->forceFill(['activated' => $request->activated])->save();
            $msg = $request->activated ? __('message.activated', ['item' => __('message.provider')]) : __('message.deactivated', ['item' => __('message.provider')]);
            return $this->returnSuccess($msg);
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
