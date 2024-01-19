<?php

namespace App\Services\Items;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\FileTrait;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\PaginateResponseTrait;
use App\Http\Resources\ServiceReviewResource;
use App\Http\Requests\ServiceProvider\ServiceStoreRequest;
use App\Http\Requests\ServiceProvider\ServiceUpdateRequest;

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
            return $this->returnSuccess('Service has been added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function update($data, ProviderService $providerService)
    {
        DB::beginTransaction();
        try {
            $providerServiceData = collect($data)->except(['dates', 'times'])->toArray();
            $providerService->update($providerServiceData);
            $dates = $data['dates'];
            $times = $data['times'];
            $this->saveAvailability($dates, $times, $providerService);
            DB::commit();
            return $this->returnSuccess('Service has been updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function destroy(User $user, ProviderService $providerService)
    {
        $user->services()->detach($providerService->service_id);
        return $this->returnSuccess('Service has been deleted successfully');
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
