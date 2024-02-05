<?php

namespace App\Services\Items;

use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\FileTrait;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\PaginateResponseTrait;

class ServiceService
{
    use ApiResponseTrait, FileTrait, PaginateResponseTrait;
    public function store(User $user, $data)
    {
        DB::beginTransaction();
        try {
            $providerServiceData = collect($data)->except(['dates', 'times'])->toArray();
            $user->services()->syncWithoutDetaching([$data['service_id'] => $providerServiceData]);
            $service = ProviderService::where(['service_id' => $data['service_id'], 'provider_id' => $user->id])->first();
            $dates = $data['dates'];
            $times = $data['times'];
            $this->saveAvailability($dates, $times, $service);
            DB::commit();
            return $this->returnSuccess(__('message.data_added', ['item' => __('message.service')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function update($data, ProviderService $providerService)
    {
        DB::beginTransaction();

        try {
            // Check if this service is under reservation
            if ($providerService->reservations()->whereRelation('morphReservation', 'status', OrderStatus::PENDING->value)->exists()) {
                return $this->returnWrong(__('message.under_reservation', ['item' => __('message.service')]));
            }

            $providerServiceData = collect($data)->except(['dates', 'times'])->toArray();
            $providerService->update($providerServiceData);
            $dates = $data['dates'];
            $times = $data['times'];
            $this->saveAvailability($dates, $times, $providerService);
            DB::commit();
            return $this->returnSuccess(__('message.data_updated', ['item' => __('message.service')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function destroy(User $user, ProviderService $providerService)
    {
        // Check if this service is under reservation
        if ($providerService->reservations()->whereRelation('morphReservation', 'status', OrderStatus::PENDING->value)->exists()) {
            return $this->returnWrong(__('message.under_reservation', ['item' => __('message.service')]));
        }
        $user->services()->detach($providerService->service_id);
        return $this->returnSuccess(__('message.data_deleted', ['item' => __('message.service')]));
    }

    public function saveAvailability($dates, $times, $providerService)
    {
        $providerService->availabilities()->delete();
        collect($dates)->each(function ($date, $index) use ($providerService, $times) {
            $availabilityData = [
                'date' => $date,
                'times' => json_encode($times[$index]),
            ];
            $providerService->availabilities()->create($availabilityData);
        });
    }
}
