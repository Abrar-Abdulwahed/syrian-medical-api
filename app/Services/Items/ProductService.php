<?php

namespace App\Services\Items;

use App\Models\User;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Http\Traits\FileTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\ServiceProvider\ProductStoreRequest;
use App\Http\Requests\ServiceProvider\ProductUpdateRequest;

class ProductService
{
    use ApiResponseTrait, FileTrait;
    public function store(User $user, $data)
    {
        DB::beginTransaction();
        try {
            $product = $user->products()->create($data);
            if (isset($data['thumbnail'])) {
                $fileName = $this->uploadFile($data['thumbnail'], $product->attachment_path);
                $product->update(['thumbnail' => $fileName]);
            }
            DB::commit();
            return $this->returnSuccess(__('message.data_added', ['item' => __('message.product')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function update($data, Product $product)
    {
        // Check if this service is under reservation
        if ($product->reservations()->whereRelation('morphReservation', 'status', OrderStatus::PENDING->value)->exists()) {
            return $this->returnWrong(__('message.under_reservation', ['item' => __('message.product')]));
        }

        if (isset($data['thumbnail'])) {
            $fileName = $this->uploadFile($data['thumbnail'], $product->attachment_path, $product->thumbnail);
            $data['thumbnail'] = $fileName;
        }
        $product->update($data);
        return $this->returnSuccess(__('message.data_updated', ['item' => __('message.product')]));
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            // Check if this service is under reservation
            if ($product->reservations()->whereRelation('morphReservation', 'status', OrderStatus::PENDING->value)->exists()) {
                return $this->returnWrong(__('message.under_reservation', ['item' => __('message.product')]));
            }
            $product->delete();
            $this->removeDirectory($product->attachment_path);
            DB::commit();
            return $this->returnSuccess(__('message.data_deleted', ['item' => __('message.product')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }
}
