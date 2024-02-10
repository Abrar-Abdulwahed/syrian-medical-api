<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PendingUpdateProfileRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'link' =>  route('admin.show.pending.update.request', $this->id),
            'user_profile' => route('admin.show.user', [$this->user_id]),
            'changes' => json_decode($this->changes, true),
        ];
    }
}
