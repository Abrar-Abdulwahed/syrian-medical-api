<?php

namespace App\Http\Controllers\User\ServiceProvider;

use Illuminate\Http\Request;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Items\ServiceService;
use App\Http\Resources\ServiceReviewResource;
use App\Http\Resources\ProviderServiceListResource;
use App\Http\Requests\ServiceProvider\ServiceStoreRequest;
use App\Http\Requests\ServiceProvider\ServiceUpdateRequest;

class ServiceController extends Controller
{
    public function __construct(protected ServiceService $serviceService)
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
    public function show(ProviderService $providerService)
    {
        $this->authorize('view', $providerService);
        return $this->returnJSON(new ServiceReviewResource($providerService), 'Data retrieved successfully');
    }

    public function store(ServiceStoreRequest $request)
    {
        return $this->serviceService->store($request->user(), $request->validated());
    }

    public function update(ServiceUpdateRequest $request, ProviderService $providerService)
    {
        $this->authorize('update', $providerService);
        return $this->serviceService->update($request->validated(), $providerService);
    }

    public function destroy(Request $request, ProviderService $providerService)
    {
        $this->authorize('delete', $providerService);
        return $this->serviceService->destroy($request->user(), $providerService);
    }
}
