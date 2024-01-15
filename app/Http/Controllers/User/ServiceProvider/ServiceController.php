<?php

namespace App\Http\Controllers\User\ServiceProvider;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ProviderProfileService;
use App\Http\Resources\ServiceResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\ServiceProvider\ServiceStoreRequest;
use App\Http\Requests\ServiceProvider\ServiceUpdateRequest;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
        $this->authorizeResource(ProviderProfileService::class, 'service');
    }

    public function index(Request $request)
    {
        $services = $request->user()->serviceProviderProfile->services;
        return $this->returnJSON(ServiceResource::collection($services), 'Data retrieved successfully');
    }

    public function store(ServiceStoreRequest $request)
    {
        // syncWithoutDetaching: no repeated service, no detach existing ones
        $request->user()->serviceProviderProfile->services()->syncWithoutDetaching([$request->service_id => $request->validated()]);
        return $this->returnSuccess('Service has been added successfully');
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $pivotRecord = DB::table('provider_profile_service')->where('id', $id)->first();
        if (!$pivotRecord) {
            throw new ModelNotFoundException('Record not found');
        }
        $service = $user->serviceProviderProfile->services()->findOrFail($pivotRecord->service_id);
        return $this->returnJSON(new ServiceResource($service), 'Data retrieved successfully');
    }

    public function update(ServiceUpdateRequest $request, string $id)
    {
        $pivotQuery = DB::table('provider_profile_service')->where('id', $id);
        if (!$pivotQuery->first()) {
            throw new ModelNotFoundException('Record not found');
        }
        $pivotQuery->update($request->validated());
        return $this->returnSuccess('Service has been updated successfully');
    }

    public function destroy(Request $request, string $id)
    {
        $pivotRecord = DB::table('provider_profile_service')->where('id', $id)->first();
        if (!$pivotRecord) {
            throw new ModelNotFoundException('Record not found');
        }
        $request->user()->serviceProviderProfile->services()->detach($pivotRecord->service_id);
        return $this->returnSuccess('Service has been deleted successfully');
    }
}
