<?php

namespace App\Http\Controllers\Admin\ItemManagement;

use App\Models\User;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use App\Services\Items\ServiceService;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Requests\ServiceProvider\ServiceStoreRequest;
use App\Http\Requests\ServiceProvider\ServiceUpdateRequest;

class ServiceController extends BaseAdminController
{
    public function __construct(protected ServiceService $serviceService)
    {
        parent::__construct();
    }

    public function store(ServiceStoreRequest $request)
    {
        $user = User::find($request->provider_id);
        return $this->serviceService->store($user, $request->validated());
    }

    public function update(ServiceUpdateRequest $request, ProviderService $providerService)
    {
        DB::beginTransaction();
        try {
            $providerService->forceFill(['provider_id' => $providerService->provider_id])->save();
            $result = $this->serviceService->update($request->validated(), $providerService);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function destroy(ProviderService $providerService)
    {
        $user = User::find($providerService->provider_id);
        return $this->serviceService->destroy($user, $providerService);
    }
}
