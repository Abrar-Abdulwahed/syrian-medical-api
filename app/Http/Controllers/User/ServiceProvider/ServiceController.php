<?php

namespace App\Http\Controllers\User\ServiceProvider;

use Illuminate\Http\Request;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceReviewResource;
use App\Http\Resources\ProviderServiceListResource;
use App\Http\Requests\ServiceProvider\ServiceStoreRequest;
use App\Http\Requests\ServiceProvider\ServiceUpdateRequest;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
    }

    public function index(Request $request)
    {
        $pageSize = $request->per_page ?? 10;
        $services = $request->user()->services()->paginate($pageSize);
        [$meta, $links] = $this->paginateResponse($services);
        return $this->returnAllDataJSON(ProviderServiceListResource::collection($services), $meta, $links, 'Data retrieved successfully');
    }

    public function store(ServiceStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->user()->services()->syncWithoutDetaching([$request->service_id => $request->safe()->except(['dates', 'times'])]);
            $service = ProviderService::where(['service_id' => $request->service_id, 'provider_id' => $request->user()->id])->first();
            $dates = $request->safe()->only('dates')["dates"];
            $times = $request->safe()->only('times')["times"];
            $this->saveAvailability($dates, $times, $service);
            DB::commit();
            return $this->returnSuccess('Service has been added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function show(ProviderService $providerService)
    {
        $this->authorize('view', $providerService);
        return $this->returnJSON(new ServiceReviewResource($providerService), 'Data retrieved successfully');
    }

    public function update(ServiceUpdateRequest $request, ProviderService $providerService)
    {
        try {
            $this->authorize('update', $providerService);
            $providerService->update($request->safe()->except(['dates', 'times']));
            $dates = $request->safe()->only('dates')["dates"];
            $times = $request->safe()->only('times')["times"];
            $this->saveAvailability($dates, $times, $providerService);
            return $this->returnSuccess('Service has been updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function destroy(Request $request, ProviderService $providerService)
    {
        $this->authorize('delete', $providerService);
        $request->user()->services()->detach($providerService->service_id);
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
