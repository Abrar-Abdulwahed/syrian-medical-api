<?php

namespace App\Http\Controllers\User\ServiceProvider;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        return $this->returnAllDataJSON(ServiceResource::collection($services), $meta, $links, 'Data retrieved successfully');
    }

    public function store(ServiceStoreRequest $request)
    {
        DB::beginTransaction();
        // syncWithoutDetaching: no repeated service, no detach existing ones
        $request->user()->services()->syncWithoutDetaching([$request->service_id => $request->safe()->except(['date', 'times'])]);
        $service = ProviderService::where('service_id', $request->service_id)->first();
        $availabilityData = [
            'date' => $request->input('date'),
            'times' => $request->input('times'),
        ];
        $service->availabilities()->create($availabilityData);
        return $this->returnSuccess('Service has been added successfully');
    }

    public function show(ProviderService $providerService)
    {
        $this->authorize('view', $providerService);
        return $this->returnJSON(new ServiceResource($providerService), 'Data retrieved successfully');
    }

    public function update(ServiceUpdateRequest $request, ProviderService $providerService)
    {
        $this->authorize('update', $providerService);
        $providerService->update($request->validated());
        $providerService->availabilities()->create([
            $request->safe()->only(['date', 'times']),
        ]);
        return $this->returnSuccess('Service has been updated successfully');
    }

    public function destroy(Request $request, ProviderService $providerService)
    {
        $this->authorize('delete', $providerService);
        $request->user()->services()->detach($providerService->service_id);
        return $this->returnSuccess('Service has been deleted successfully');
    }
}
