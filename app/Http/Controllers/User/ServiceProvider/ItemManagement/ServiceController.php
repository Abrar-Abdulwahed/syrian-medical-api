<?php

namespace App\Http\Controllers\User\ServiceProvider\ItemManagement;

use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Http\Controllers\User\BaseUserController;
use App\Services\Items\ServiceService;
use App\Http\Requests\ServiceProvider\ServiceStoreRequest;
use App\Http\Requests\ServiceProvider\ServiceUpdateRequest;

class ServiceController extends BaseUserController
{
    public function __construct(protected ServiceService $serviceService)
    {
        parent::__construct();
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
